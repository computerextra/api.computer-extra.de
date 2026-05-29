<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Nur POST-Methoden sind erlaubt"]);
    exit();
}

// .env Datei laden
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$data = file_get_contents('php://input');

try {
    $pdo = new PDO(
        "mysql:host=" . $_ENV['KI_DB_HOST'] . ";dbname=" . $_ENV['KI_DB_NAME'] . ";charset=utf8",
        $_ENV['KI_DB_USER'],
        $_ENV['KI_DB_PASSWORD']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Datenbankverbindung fehlgeschlagen: " . $e->getMessage()]);
    exit();
}

// Abfrage ausführen
try {
    $stmt = $pdo->prepare("insert into anruf (anrufer_name, transcript, angerufen, anrufer_nummer, ansprechpartner) values (:anrufer_name, :transcript, :angerufen, :anrufer_nummer, :ansprechpartner)", [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute(["anrufer_name" => $data["anrufer_name"], "transcript" => $data["transcript"], "angerufen" => $data["angerufen"], "anrufer_nummer" => $data["anrufer_nummer"], "ansprechpartner" => $data["ansprechpartner"]]);

    // Erfolgreiche Antwort
    http_response_code(200);
} catch (PDOException $e) {
    file_put_contents("error.txt", $data);
    file_put_contents("error.txt", $e, FILE_APPEND);
    http_response_code(500);
    echo json_encode(["error" => "Datenbankabfrage fehlgeschlagen: " . $e->getMessage()]);
}

exit();
