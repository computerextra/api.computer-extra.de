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
    $stmt = $pdo->prepare("select * from anruf where anrufer_nummer = :nummer;", [PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY]);
    $stmt->execute(["nummer" => $data["anrufer_nummer"]]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Lösche den Eintrag aus der Datenbank!
    $stmt = $pdo->prepare("delete from anruf where id = :id;");
    $stmt->execute(["id" => $result["id"]]);

    // Erfolgreiche Antwort
    http_response_code(200);
    echo json_encode($result);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Datenbankabfrage fehlgeschlagen: " . $e->getMessage()]);
}

exit();
