<?php
require_once __DIR__ . "/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// CORS Headers für alle Clients erlauben
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Nur POST-Methoden erlauben
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "message" => "Nur POST-Methoden sind erlaubt",
        "status" => 405,
    ]);
    exit();
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$data = $_POST;
$files = $_FILES;

$anschreiben = $files["Anschreiben"];
$lebenslauf = $files["Lebenslauf"];
$zeugnisse = $files["Zeugnisse"];

if (!isset($data)) {
    echo json_encode(["message" => "Keine Daten empfangen", "status" => 400]);
    exit();
}
if (
    !isset($data["Name"]) ||
    !isset($data["Mail"]) ||
    !isset($data["Phone"]) ||
    !isset($data["Job"])
) {
    echo json_encode(["message" => "Pflichtfelder fehlen", "status" => 400]);
    exit();
}

// Check IP
$ip = $_SERVER["REMOTE_ADDR"];
$strippedIp = trim($ip);
$location = get_geolocation($_ENV["IPGEOLOCATION_API"], $strippedIp);
$decodedLocation = json_decode($location, true);

$haveAnschreiben = false;
$haveLebenslauf = false;
$haveZeugnisse = false;

if (isset($anschreiben)) {
    $filename = "Anschreiben";
    $extension = pathinfo($anschreiben["name"], PATHINFO_EXTENSION);
    $basename = "$filename.$extension";

    $source = $anschreiben["tmp_name"];
    $destination = __DIR__ . "/uploads/" . $basename;

    if (!move_uploaded_file($source, $destination)) {
        echo json_encode([
            "message" => "Fehler beim Hochladen der Datei",
            "status" => 500,
        ]);
        exit();
    }
    $haveAnschreiben = true;
}

if (isset($lebenslauf)) {
    $filename = "Lebenslauf";
    $extension = pathinfo($lebenslauf["name"], PATHINFO_EXTENSION);
    $basename = "$filename.$extension";

    $source = $lebenslauf["tmp_name"];
    $destination = __DIR__ . "/uploads/" . $basename;

    if (!move_uploaded_file($source, $destination)) {
        echo json_encode([
            "message" => "Fehler beim Hochladen der Datei",
            "status" => 500,
        ]);
        exit();
    }
    $haveLebenslauf = true;
}

if (isset($zeugnisse)) {
    $filename = "Zeugnisse";
    $extension = pathinfo($zeugnisse["name"], PATHINFO_EXTENSION);
    $basename = "$filename.$extension";

    $source = $zeugnisse["tmp_name"];
    $destination = __DIR__ . "/uploads/" . $basename;

    if (!move_uploaded_file($source, $destination)) {
        echo json_encode([
            "message" => "Fehler beim Hochladen der Datei",
            "status" => 500,
        ]);
        exit();
    }
    $haveZeugnisse = true;
}

$mail = new PHPMailer(true);
try {
    $mail->setLanguage("de", "/PHPMailer/language/");

    $mail->isSMTP();
    $mail->Host = $_ENV["SMTP_HOST"];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV["SMTP_USER"];
    $mail->Password = $_ENV["SMTP_PASSWORD"];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $_ENV["SMTP_PORT"];

    $mail->setFrom($_ENV["SMTP_FROM"], "Kontaktformular");
    $mail->addAddress($_ENV["SMTP_BEWERBUNG"]);
    $mail->addBCC($_ENV["SMTP_BCC"]);
    $mail->isHTML(true);

    //Anhänge
    if ($anschreiben) {
        $mail->addAttachment("uploads/Anschreiben.pdf");
    }
    if ($lebenslauf) {
        $mail->addAttachment("uploads/Lebenslauf.pdf");
    }
    if ($zeugnisse) {
        $mail->addAttachment("uploads/Zeugnisse.pdf");
    }

    $body = "IP: " . $ip . "<br>";
    $body .=
        "<table><tr><th>Continent</th><th>Country</th><th>Organization</th><th>ISP</th><th>Is EU Member?</th></tr>";
    $body .= "<tr>";
    if ($decodedLocation["message"] != "") {
        $body .= "<td>" . $decodedLocation["message"] . "</td>";
    } else {
        $body .=
            "<td>" .
            $decodedLocation["continent_name"] .
            " (" .
            $decodedLocation["continent_code"] .
            ")</td>";
        $body .=
            "<td>" .
            $decodedLocation["country_name"] .
            " (" .
            $decodedLocation["country_code2"] .
            ")</td>";
        $body .= "<td>" . $decodedLocation["organization"] . "</td>";
        $body .= "<td>" . $decodedLocation["isp"] . "</td>";
        if ($decodedLocation["is_eu"] == true) {
            $body .= "<td>Yes</td>";
        } else {
            $body .= "<td>No</td>";
        }
    }
    $body .= "</tr>";
    $body .= "</table>";
    $body .=
        "<br><hr><br>Email von: " .
        $data["Name"] .
        "<br>Mail: " .
        $data["Mail"] .
        "<br>Telefon: " .
        $data["Phone"] .
        "<br>Bewerbung als: " .
        $data["Job"];

    $mail->Subject = "Neue Bewerbung";
    $mail->CharSet = "UTF-8";
    $mail->Body = $body;

    $mail->send();

    // Erfolgreiche Bewerbung
    echo json_encode([
        "message" => "Bewerbung erfolgreich gesendet",
        "status" => 200,
    ]);
    exit();
} catch (Exception $e) {
    echo json_encode([
        "message" => "Fehler beim Senden der E-Mail: " . $e->getMessage(),
        "status" => 500,
    ]);
    exit();
}

function get_geolocation(
    $apiKey,
    $ip,
    $lang = "de",
    $fields = "*",
    $excludes = "",
) {
    $url = "https://api.ipgeolocation.io/ipgeo?apiKey=$apiKey&ip=$ip&lang=$lang&fields=$fields&excludes=$excludes";
    $cURL = curl_init();
    curl_setopt($cURL, CURLOPT_URL, $url);
    curl_setopt($cURL, CURLOPT_HTTPGET, true);
    curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($cURL, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Accept: application/json",
        "User-Agent: " . $_SERVER["HTTP_USER_AGENT"],
    ]);
    return curl_exec($cURL);
}
