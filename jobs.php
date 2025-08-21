<?php
// CORS Headers für alle Clients erlauben
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Nur GET-Methoden erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["error" => "Nur GET-Methoden sind erlaubt"]);
    exit();
}

// .env Datei laden
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Datenbankverbindung
try {
    $pdo = new PDO(
        "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_NAME'] . ";charset=utf8",
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Datenbankverbindung fehlgeschlagen: " . $e->getMessage()]);
    exit();
}

// Abfrage ausführen
try {
    $stmt = $pdo->prepare("SELECT * FROM `Jobs` WHERE online = 1");
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Erfolgreiche Antwort
    http_response_code(200);
    echo json_encode([
        "success" => true,
        "data" => $result,
        "count" => count($result)
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Datenbankabfrage fehlgeschlagen: " . $e->getMessage()]);
}
