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
$Name = $input["Name"] ?? null;
$Webseite = $input["Webseite"] ?? null;
$Bild = $input["Bild"] ?? null;
$Online = $input["Online"] == "1" ? true : false;

$image = $_FILES[0] ?? null;

$queries = [
    "READ_ONE" => "SELECT * FROM Referenzen WHERE id = :id LIMIT 1;",
    "READ_ALL" => "SELECT * FROM Referenzen WHERE ORDER BY name ASC;",
    "UPDATE" => "UPDATE Referenzen SET Name = :Name, Webseite = :Webseite, Bild = :Bild, Online = :Online WHERE id = :id",
    "INSERT" => "INSERT INTO Referenzen (id, Name, Webseite, Bild, Online) VALUES (:id, :Name, :Webseite, :Bild, :Online);",
    "DELETE" => "DELETE FROM Referenzen WHERE id = :id;"
];

switch ($method) {
    case "GET":
        if (!empty($id) || isset($id)) {
            $stmt = $pdo->prepare($queries['READ_ONE']);
            $stmt = $pdo->prepare($query);
            $stmt->execute([":id" => $id]);
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
        if (empty($id) || !isset($id)) {
            http_response_code(400);
            echo json_encode(["error" => "id is missing"]);
            exit;
        }
        if (empty($Name)) {
            http_response_code(400);
            echo json_encode(["error" => "Name is missing"]);
            exit;
        }
        if (empty($Webseite)) {
            http_response_code(400);
            echo json_encode(["error" => "Webseite is missing"]);
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
            ":id" => $id,
            ":Name" => $Name,
            ":Webseite" => $Webseite,
            ":Bild" => $filename,
            ":Online" => $Online,
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
        if (empty($Name)) {
            http_response_code(400);
            echo json_encode(["error" => "Name is missing"]);
            exit;
        }
        if (empty($Webseite)) {
            http_response_code(400);
            echo json_encode(["error" => "Webseite is missing"]);
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

        $id = CUIDGenerator::gnerateCUID();
        $stmt = $pdo->prepare($queries['INSERT']);

        $stmt->execute([
            ":id" => $id,
            ":Name" => $Name,
            ":Webseite" => $Webseite,
            ":Bild" => $filename,
            ":Online" => $Online,
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

