<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

set_exception_handler(function ($e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
    exit;
});

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "$errstr in $errfile on line $errline"
    ]);
    exit;
});

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

define("DB_USER", $_ENV["DB_USER"]);
define("DB_HOST", $_ENV["DB_HOST"]);
define("DB_PASSWORD", $_ENV["DB_PASSWORD"]);
define("DB_NAME", $_ENV["DB_NAME"]);
define("DB_CHARSET", $_ENV["DB_CHARSET"]);

// Verbindung zur Datenbank herstellen
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Fehlerbehandlung bei der Verbindung
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Database connection failed: " . $conn->connect_error
    ]);
    exit;
}

// SQL-Abfrage
$sql = "SELECT * FROM `Abteilung` ORDER BY `index` ASC";
$res = $conn->query($sql);

if (!$res) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Query failed: " . $conn->error
    ]);
    exit;
}

// Daten als JSON zurückgeben
$data = $res->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "status" => "success",
    "num_rows" => $res->num_rows,
    "data" => $data
]);