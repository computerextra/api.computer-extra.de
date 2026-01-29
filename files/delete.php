<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

declare(strict_types=1);

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "bootstrap.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

ensurePost();

$hash = (string) ($_POST["hash"] ?? '');
$password = (string) ($_POST["password"] ?? '');

if ($hash === '' || $password === '') {
    respondWithError("Etwas ist schiefgelaufen");
}

$pdo = db_connect();
$stmt = $pdo->prepare('SELECT file_hash, password_hash, original_name, mime_type, size_bytes, stored_path FROM files WHERE file_hash = :file_hash LIMIT 1');
$stmt->execute([":file_hash" => $hash]);
$row = $stmt->fetch();

// Generic failure to avoid revealing if hash or password was wrong
if (!$row || !is_array($row)) {
    respondWithError("Kein Eintrag", 404);
}

if (!password_verify($password, (string) $row["password_hash"])) {
    respondWithError("Unotherized", 403);
}

$id = (int) $row["id"];
$path = (string) $row["stored_path"];
if (!is_file($path) || !is_readable($path)) {
    respondWithError("Datei Fehler", 410);
}

@unlink($path);
$stmt = $pdo->prepare("DELETE FROM files WHERE id = :id;");
$stmt->execute([":id" => $id]);

http_response_code(200);
exit;