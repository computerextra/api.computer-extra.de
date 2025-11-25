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
$title = $input["title"] ?? null;
$subtitle = $input["subtitle"] ?? null;
$date_start = $input["date_start"] ?? null;
$date_stop = $input["date_stop"] ?? null;
$link = $input["link"] ?? null;
$anzeigen = $input["anzeigen"] == "1" ? true : false;

$image = $_FILES[0] ?? null;

$queries = [
    "READ_ONE" => "SELECT * FROM Angebot WHERE id = :id LIMIT 1;",
    "READ_ALL" => "SELECT * FROM Angebot ORDER BY title ASC;",
    "UPDATE" => "UPDATE Angebot SET title = :title, subtitle = :subtitle, date_start = :date_start, date_stop = " .
        ":date_stop, link = :link, image = :image, anzeigen = :anzeigen WHERE id = :id",
    "INSERT" => "INSERT INTO Angebot (id, title, subtitle, date_start, date_stop, link, image, anzeigen) VALUES " .
        "(:id, :title, :subtitle, :date_start, :date_stop, :link, :image, :anzeigen);",
    "DELETE" => "DELETE FROM Angebot WHERE id = :id;"
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
        if (empty($title)) {
            http_response_code(400);
            echo json_encode(["error" => "title is missing"]);
            exit;
        }
        if (empty($date_start)) {
            http_response_code(400);
            echo json_encode(["error" => "date_start is missing"]);
            exit;
        }
        if (empty($date_stop)) {
            http_response_code(400);
            echo json_encode(["error" => "date_stop is missing"]);
            exit;
        }
        if (empty($link)) {
            http_response_code(400);
            echo json_encode(["error" => "link is missing"]);
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
            ":title" => $title,
            ":subtitle" => $subtitle,
            ":date_start" => $date_start,
            ":date_stop" => $date_stop,
            ":link" => $link,
            ":image" => $filename,
            ":anzeigen" => $anzeigen
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
            ":title" => $title,
            ":subtitle" => $subtitle,
            ":date_start" => $date_start,
            ":date_stop" => $date_stop,
            ":link" => $link,
            ":image" => $filename,
            ":anzeigen" => $anzeigen
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

