<?php

declare(strict_types=1);

require_once dirname(__FILE__) . "config.php";
require_once dirname(__FILE__) . "helper.php";

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
$mitarbeiterId = $input["mitarbeiterId"] ?? null;
$Paypal = $input["Paypal"] == "1" ? true : false;
$Abonniert = $input["Abonniert"] == "1" ? true : false;
$Geld = $input["Geld"] ?? null;
$Pfand = $input["Pfand"] ?? null;
$Dinge = $input["Dinge"] ?? null;
$Abgeschickt = $input["Abgeschickt"] ?? null;
$Bild1 = $input["Bild1"] ?? null;
$Bild2 = $input["Bild2"] ?? null;
$Bild3 = $input["Bild3"] ?? null;

$image1 = $_FILES[0] ?? null;
$image2 = $_FILES[1] ?? null;
$image3 = $_FILES[2] ?? null;

$queries = [
    "READ_ONE" => "SELECT Mitarbeiter.name, Einkauf.* FROM Mitarbeiter LEFT JOIN Einkauf ON Mitarbeiter.einkaufId = Einkauf.id WHERE Mitarbeiter.id = :mitarbeiterId LIMIT 1;",
    "UPDATE" => "UPDATE Einkauf SET Paypal = :Paypal, Abonniert = :Abonniert, Geld = :Geld, Pfand = :Pfand, Dinge = :Dinge, Abgeschickt = NOW(), Bild1 = :Bild1, Bild2 = :Bild2, Bild3 = :Bild3 WHERE id = :id;",
    "INSERT" => "INSERT INTO Einkauf (id, Paypal, Abonniert, Geld, Pfand, Dinge, Abgeschickt, Bild1, Bild2, Bild3) VALUES (:id, :Paypal, :Abonniert, :Geld, :Pfand, :Dinge, NOW(), :Bild1, :Bild2, :Bild3);",
    "UPDATE_MITARBEITER" => "UPDATE Mitarbeiter SET einkaufId = :einkaufId WHERE id = :mitarbeiterId;",
    "DELETE" => "UPDATE Einkauf SET Abonniert = NULL, Abgeschickt = GESTERN WHERE id = :id;",
    // Custom
    "LISTE" => "SELECT Einkauf.*, Mitarbeiter.name, Mitarbeiter.id as MitarbeiterID" .
        "FROM `Einkauf` " .
        "LEFT JOIN Mitarbeiter ON Einkauf.id = Mitarbeiter.einkaufId " .
        "WHERE Einkauf.Abonniert = 1 OR (Einkauf.Abgeschickt >= CURDATE() - INTERVAL 1 DAY AND Einkauf.Abgeschickt <= NOW()) " .
        "ORDER BY Einkauf.Abgeschickt ASC;",
    "SKIP" => "UPDATE Einkauf SET Abgeschickt = CURDATE() - INTERVAL + DAY WHERE id = :id;",
];

switch ($method) {
    case "GET":
        if (!empty($mitarbeiterId) || isset($mitarbeiterId)) {
            $stmt = $pdo->prepare($queries['READ_ONE']);
            $stmt->execute([":mitarbeiterId" => $mitarbeiterId]);
            $res = $stmt->fetchAll();
            echo json_encode(["data" => $res]);
            exit;
        } else {
            $stmt = $pdo->prepare($queries['LISTE']);
            $stmt->execute();
            $res = $stmt->fetchAll();
            echo json_encode(["data" => $res]);
            exit;
        }

    case "POST":
        // Update
        if (empty($id) || !isset($id)) {
            http_response_code(400);
            echo json_encode(["error" => "id is missing"]);
            exit;
        }
        if (empty($Dinge)) {
            http_response_code(400);
            echo json_encode(["error" => "Dinge is missing"]);
            exit;
        }

        $filename1 = null;
        $filename2 = null;
        $filename3 = null;

        if (isset($image1))
            $filename1 = uploadImage($image1);
        if (isset($image2))
            $filename2 = uploadImage($image2);
        if (isset($image3))
            $filename3 = uploadImage($image3);


        $stmt = $pdo->prepare($queries['UPDATE']);
        $stmt->execute([
            ":id" => $id,
            ":Paypal" => $Paypal,
            ":Abonniert" => $Abonniert,
            ":Geld" => $Geld,
            ":Pfand" => $Pfand,
            ":Dinge" => $Dinge,
            ":Bild1" => $filename1,
            ":Bild2" => $filename2,
            ":Bild3" => $filename3,
        ]);
        echo json_encode(["message" => "OK"]);
        exit;
    case "PATCH":
        // Skip
        if (empty($id) || !isset($id)) {
            http_response_code(400);
            echo json_encode(["error" => "id is missing"]);
            exit;
        }
        $stmt = $pdo->prepare($queries['SKIP']);
        $stmt->execute([
            ":id" => $id,
        ]);
        echo json_encode(["message" => "OK"]);
        exit;

    case "PUT":
        // Create
        if (empty($mitarbeiterId) || !isset($mitarbeiterId)) {
            http_response_code(400);
            echo json_encode(["error" => "mitarbeiterId is missing"]);
            exit;
        }
        if (empty($Dinge)) {
            http_response_code(400);
            echo json_encode(["error" => "Dinge is missing"]);
            exit;
        }

        $filename1 = null;
        $filename2 = null;
        $filename3 = null;

        if (isset($image1))
            $filename1 = uploadImage($image1);
        if (isset($image2))
            $filename2 = uploadImage($image2);
        if (isset($image3))
            $filename3 = uploadImage($image3);

        $id = CUIDGenerator::gnerateCUID();
        $stmt = $pdo->prepare($queries['INSERT']);
        $stmt->execute([
            ":id" => $id,
            ":Paypal" => $Paypal,
            ":Abonniert" => $Abonniert,
            ":Geld" => $Geld,
            ":Pfand" => $Pfand,
            ":Dinge" => $Dinge,
            ":Bild1" => $filename1,
            ":Bild2" => $filename2,
            ":Bild3" => $filename3,
        ]);
        $stmt = $pdo->prepare($queries['UPDATE_MITARBEITER']);
        $stmt->execute([
            ":einkaufId" => $id,
            ":mitarbeiterId" => $mitarbeiterId,
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
        $stmt->execute([
            ":id" => $id,
        ]);
        echo json_encode(["message" => "OK"]);

        exit;

    default:
        http_response_code(405);
        echo json_encode(["error" => "method not allowed"]);
        exit;
}

