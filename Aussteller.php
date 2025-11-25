<?php

declare(strict_types=1);

require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "config.php";
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . "helper.php";

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-API-Key');
header('Access-Control-Allow-Credentials: true');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
header("Content-Security-Policy: default-src 'self'");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$headers = getallheaders();
$apiKey = $headers['X-API-Key'] ?? $headers['x-api-key'] ?? null;
if (!checkApiKey($apiKey)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$method = $_SERVER["REQUEST_METHOD"];
[$_POST, $_FILES] = request_parse_body();

$input = file_get_contents("php://input");

$id = $input["id"] ?? null;
$Artikelnummer = $input["Artikelnummer"] ?? null;
$Artikelname = $input["Artikelname"] ?? null;
$Specs = $input["Specs"] ?? null;
$Preis = $input["Preis"] ?? null;
$Bild = $input["Bild"] ?? null;

$image = $_FILES[0] ?? null;

$queries = [
    "READ_ONE" => "SELECT * FROM Aussteller WHERE Artikelnummer = :Artikelnummer LIMIT 1;",
    "READ_ALL" => "SELECT * FROM Aussteller ORDER BY Artikelnummer ASC;",
    "UPDATE" => "UPDATE Aussteller SET Bild = :Bild WHERE Artikelnummer = :Artikelnummer",
];

switch ($method) {
    case "GET":
        if (!empty($Artikelnummer) || isset($Artikelnummer)) {
            $stmt = $pdo->prepare($queries['READ_ONE']);
            $stmt = $pdo->prepare($query);
            $stmt->execute([":Artikelnummer" => $Artikelnummer]);
        } else {
            $stmt = $pdo->prepare($queries['READ_ALL']);
            $stmt = $pdo->prepare($query);
            $stmt->execute();
        }
        $res = $stmt->fetchAll();
        echo json_encode(["data" => $res]);
        exit;

    case "POST":
    case "PATCH":
        // Update
        if (empty($Artikelnummer) || !isset($Artikelnummer)) {
            http_response_code(400);
            echo json_encode(["error" => "Artikelnummer is missing"]);
            exit;
        }
        if (empty($image)) {
            http_response_code(400);
            echo json_encode(["error" => "image is missing"]);
            exit;
        }
        $filename = uploadImage($image);
        if ($filename == null) {
            http_response_code(500);
            echo json_encode(["error" => "bild konnte nicht hochgeladen werden"]);
            exit;
        }

        $stmt = $pdo->prepare($queries['UPDATE']);
        $stmt->execute([
            ":Artikelnummer" => $Artikelnummer,
            ":Bild" => $filename,
        ]);
        echo json_encode(["message" => "OK"]);
        exit;

    case "PUT":
        // Create
        http_response_code(405);
        echo json_encode(["error" => "method not allowed"]);
        exit;

    case "DELETE":
        http_response_code(405);
        echo json_encode(["error" => "method not allowed"]);
        exit;

    default:
        http_response_code(405);
        echo json_encode(["error" => "method not allowed"]);
        exit;
}

