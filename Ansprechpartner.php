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

$input = file_get_contents("php://input");

$id = $input["id"] ?? null;
$name = $input["name"] ?? null;
$telefon = $input["telefon"] ?? null;
$mobil = $input["mobil"] ?? null;
$mail = $input["mail"] ?? null;
$lieferantId = $input["lieferantId"] ?? null;

$queries = [
    "READ_ONE" => "SELECT * FROM Ansprechpartner WHERE id = :id LIMIT 1;",
    "READ_ALL" => "SELECT * FROM Ansprechpartner WHERE lieferantId = :lieferantId ORDER BY name ASC;",
    "UPDATE" => "UPDATE Ansprechpartner SET name = :name, telefon = :telefon, mobil = :mobil, mail = :mail, lieferantId = :lieferantId WHERE id = :id",
    "INSERT" => "INSERT INTO Ansprechpartner (id, name, telefon, mobil, mail, lieferantId) VALUES (:id, :name, :telefon, :mail, :liefertantId);",
    "DELETE" => "DELETE FROM Ansprechpartner WHERE id = :id;"
];

switch ($method) {
    case "GET":
        if (!empty($id) || isset($id)) {
            $stmt = $pdo->prepare($queries['READ_ONE']);
            $stmt = $pdo->prepare($query);
            $stmt->execute([":id" => $id]);
        } else if (!empty($lieferantId) || isset($lieferantId)) {
            $stmt = $pdo->prepare($queries['READ_ALL']);
            $stmt = $pdo->prepare($query);
            $stmt->execute();
        } else {
            http_response_code(400);
            echo json_encode(["error" => "lieferantId or id is missing"]);
            exit;
        }
        $res = $stmt->fetchAll();
        echo json_encode(["data" => $res]);
        exit;

    case "POST":
    case "PATCH":
        // Update
        if (empty($id) || !isset($id)) {
            http_response_code(400);
            echo json_encode(["error" => "id is missing"]);
            exit;
        }
        if (empty($name)) {
            http_response_code(400);
            echo json_encode(["error" => "name is missing"]);
            exit;
        }
        if (empty($lieferantId)) {
            http_response_code(400);
            echo json_encode(["error" => "lieferantId is missing"]);
            exit;
        }

        $stmt = $pdo->prepare($queries['UPDATE']);
        $stmt->execute([
            ":id" => $id,
            ":name" => $name,
            ":telefon" => $telefon,
            ":mobil" => $mobil,
            ":mail" => $mail,
            ":lieferantId" => $lieferantId
        ]);
        echo json_encode(["message" => "OK"]);
        exit;

    case "PUT":
        // Create
        if (!empty($id) || isset($id)) {
            http_response_code(400);
            echo json_encode(["error" => "id is given"]);
            exit;
        }
        if (empty($name)) {
            http_response_code(400);
            echo json_encode(["error" => "name is missing"]);
            exit;
        }
        if (empty($lieferantId)) {
            http_response_code(400);
            echo json_encode(["error" => "lieferantId is missing"]);
            exit;
        }
        $id = CUIDGenerator::gnerateCUID();
        $stmt = $pdo->prepare($queries['INSERT']);
        $stmt->execute([
            ":id" => $id,
            ":name" => $name,
            ":telefon" => $telefon,
            ":mobil" => $mobil,
            ":mail" => $mail,
            ":lieferantId" => $lieferantId
        ]);
        echo json_encode(["message" => "OK"]);
        exit;

    case "DELETE":
        if (empty($id) || !isset($id)) {
            http_response_code(400);
            echo json_encode(["error" => "id is missing"]);
            exit;
        }
        $stmt = $pdo->prepare($queries['DELETE']);
        $stmt->execute([":id" => $id]);
        echo json_encode(["message" => "OK"]);
        exit;

    default:
        http_response_code(405);
        echo json_encode(["error" => "method not allowed"]);
        exit;
}

