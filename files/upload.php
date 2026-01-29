<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

declare(strict_types=1);
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "bootstrap.php";

ensurePost();

// 20 MB in bytes
$MAX_BYTES = 20 * 1024 * 1024;

// Validate presence
if (!isset($_FILES["file"]) || !isset($_POST["password"])) {
    respondWithError("Etwas ist schiefgelaufen");
}

$password = (string) ($_POST["password"] ?? "");
if ($password === "" || mb_strlen($password) < 6) {
    respondWithError("Etwas ist schiefgelaufen");
}

$upload = $_FILES["file"];
if (!is_array($upload) || (int) $upload["error"] !== UPLOAD_ERR_OK) {
    respondWithError("Etwas ist schiefgelaufen");
}

// Enforce Size
$size = (int) ($upload["size"] ?? 0);
if ($size <= 0 || $size > $MAX_BYTES) {
    respondWithError("Etwas ist schiefgelaufen");
}

// determine safe mime using finfo
$tmpPath = (string) $upload["tmp_name"];
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($tmpPath) ?: null;

$originalName = $upload["name"];
$clientName = (string) ($upload["name"] ?? "");
$clientName = basename(trim($clientName));
if ($originalName === "") {
    $originalName = "unbenannt";
}

// Generate random has filename and keep original extension (if any)
$hash = generateRandomHash(16); // 32 hex chars
$ext = pathinfo($originalName, PATHINFO_EXTENSION);
$storedFilename = $ext !== "" ? ($hash . "." . $ext) : $hash;
$storedPath = STORAGE_PATH . DIRECTORY_SEPARATOR . $storedFilename;

// Move uploaded file
if (!move_uploaded_file($tmpPath, $storedFilename)) {
    respondWithError("Etwas ist schiefgelaufen");
}

// Tighten permissions (best effort on non-windows)
@chmod($storedPath, 0640);

// Store metadata in DB with password hash
$pdo = db_connect();
$stmt = $pdo->prepare('INSERT INTO files (file_hash, password_hash, original_name, mime_type, size_bytes, stored_path, created_at) VALUES (:file_hash, :password_hash, :original_name, :mime_type, :size_bytes, :stored_path, :created_at)');
$ok = $stmt->execute([
    ':file_hash' => $hash,
    ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ':original_name' => $originalName,
    ':mime_type' => $mime,
    ':size_bytes' => $size,
    ':stored_path' => $storedPath,
    ':created_at' => nowIso8601(),
]);

if (!$ok) {
    @unlink($storedPath);
    respondWithError("Etwas ist schiefgelaufen");
}

// Repond to user with the hash
$url = "https://upload.computer-extra.de/" . htmlspecialchars($hash, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
header("Location: $url");