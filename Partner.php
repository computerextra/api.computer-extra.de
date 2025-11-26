<?php

declare(strict_types=1);

$config = new Config();

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


$method = $_SERVER["REQUEST_METHOD"];
[$_POST, $_FILES] = request_parse_body();

$input = file_get_contents("php://input");

$id = $input["id"] ?? null;
$name = $input["name"] ?? null;
$link = $input["link"] ?? null;
$image = $input["image"] ?? null;

$bild = $_FILES[0] ?? null;

$queries = [
    "READ_ONE" => "SELECT * FROM Partner WHERE id = :id LIMIT 1;",
    "READ_ALL" => "SELECT * FROM Partner WHERE ORDER BY name ASC;",
    "UPDATE" => "UPDATE Partner SET name = :name, link = :link, image = :image WHERE id = :id",
    "INSERT" => "INSERT INTO Partner (id, name, link, image) VALUES (:id, :name, :link, :image);",
    "DELETE" => "DELETE FROM Partner WHERE id = :id;"
];

switch ($method) {
    case "GET":
        if (!empty($id) || isset($id)) {
            $stmt = $config->pdo->prepare($queries['READ_ONE']);

            $stmt->execute([":id" => $id]);
        } else if (!empty($lieferantId) || isset($lieferantId)) {
            $stmt = $config->pdo->prepare($queries['READ_ALL']);

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
    // if (empty($id) || !isset($id)) {
    //     http_response_code(400);
    //     echo json_encode(["error" => "id is missing"]);
    //     exit;
    // }
    // if (empty($name)) {
    //     http_response_code(400);
    //     echo json_encode(["error" => "name is missing"]);
    //     exit;
    // }
    // if (empty($link)) {
    //     http_response_code(400);
    //     echo json_encode(["error" => "link is missing"]);
    //     exit;
    // }

    // if (empty($bild)) {
    //     http_response_code(400);
    //     echo json_encode(["error" => "bild is missing"]);
    //     exit;
    // }
    // $filename = uploadImage($bild);
    // if ($filename == null) {
    //     http_response_code(500);
    //     echo json_encode(["error" => "bild konnte nicht hochgeladen werden"]);
    //     exit;
    // }

    // $stmt = $pdo->prepare($queries['UPDATE']);
    // $stmt->execute([
    //     ":id" => $id,
    //     ":name" => $name,
    //     ":link" => $link,
    //     ":image" => $filename,
    // ]);
    // echo json_encode(["message" => "OK"]);
    // exit;

    case "PUT":
    // Create
    // if (!empty($id) || isset($id)) {
    //     http_response_code(400);
    //     echo json_encode(["error" => "id is given"]);
    //     exit;
    // }
    // if (empty($name)) {
    //     http_response_code(400);
    //     echo json_encode(["error" => "name is missing"]);
    //     exit;
    // }
    // if (empty($link)) {
    //     http_response_code(400);
    //     echo json_encode(["error" => "link is missing"]);
    //     exit;
    // }

    // if (empty($bild)) {
    //     http_response_code(400);
    //     echo json_encode(["error" => "bild is missing"]);
    //     exit;
    // }
    // $filename = uploadImage($bild);
    // if ($filename == null) {
    //     http_response_code(500);
    //     echo json_encode(["error" => "bild konnte nicht hochgeladen werden"]);
    //     exit;
    // }

    // $id = CUIDGenerator::gnerateCUID();
    // $stmt = $pdo->prepare($queries['INSERT']);
    // $stmt->execute([
    //     ":id" => $id,
    //     ":name" => $name,
    //     ":link" => $link,
    //     ":image" => $filename,
    // ]);
    // echo json_encode(["message" => "OK"]);
    // exit;

    case "DELETE":
    // if (empty($id) || !isset($id)) {
    //     http_response_code(400);
    //     echo json_encode(["error" => "id is missing"]);
    //     exit;
    // }
    // $stmt = $pdo->prepare($queries['DELETE']);
    // $stmt->execute([":id" => $id]);
    // echo json_encode(["message" => "OK"]);
    // exit;

    default:
        http_response_code(405);
        echo json_encode(["error" => "method not allowed"]);
        exit;
}

