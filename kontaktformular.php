<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

// CORS Headers für alle Clients erlauben
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Nur POST-Methoden erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["message" => "Nur POST-Methoden sind erlaubt", "status" => 405]);
    exit();
}

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


//Prüfsumme
$ok2Send = false;

$name = "";
$email = "";
$number = "";
$Nachricht = "";

if (isset($_POST["Name"])) {
    $name = $_POST["Name"];
    $ok2Send = true;
} else {
    $ok2Send = false;
}

if (isset($_POST["Mail"])) {
    $email = $_POST["Mail"];
    $ok2Send = true;
} else {
    $ok2Send = false;
}

if (isset($_POST["Telefon"])) {
    $number = $_POST["Telefon"];
    $ok2Send = true;
}

if (isset($_POST["Nachricht"])) {
    $Nachricht = $_POST["Nachricht"];
    $ok2Send = true;
} else {
    $ok2Send = false;
}

if (!$ok2Send) {
    echo json_encode(["message" => "Fehlende oder ungültige Eingabewerte", "status" => 400]);
    exit();
}

$mail = new PHPMailer(true);

try {
    $mail->setLanguage("de", "/PHPMailer/language/");

    // Server Settings
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USER'];
    $mail->Password = $_ENV['SMTP_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $_ENV['SMTP_PORT'];


    $mail->setFrom($_ENV["SMTP_FROM"], "Kontaktformular");
    $mail->addAddress($_ENV["SMTP_TO"]);
    $mail->addBCC($_ENV["SMTP_BCC"]);
    $mail->isHTML(true);

    $mail->Subject = "Neue Kontaktanfrage von $name";
    $mail->CharSet = 'UTF-8';
    $mail->Body = "<hr>SIE HABEN EINE KONTAKTANFRAGE ERHALTEN<hr><br><br>Nachfolgende die Details der Anfrage:<br><br><b>Name:</b> $name<br><b>Email:</b> $email<br><br><b>Nachricht:</b><br>$Nachricht";
    $mail->send();

    echo json_encode(["message" => "E-Mail erfolgreich gesendet", "status" => 200]);
    exit();

} catch (Exception $e) {
    echo json_encode(["message" => $mail->ErrorInfo, "status" => 500]);
    exit();
}