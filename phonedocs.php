<?php
require_once __DIR__ . '/vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'ok' => false,
        'error' => 'method_not_allowed',
    ]);
    exit;
}

$name = trim((string) ($_POST['name'] ?? ''));
$telefonnummer = trim((string) ($_POST['telefonnummer'] ?? ''));
$email = trim((string) ($_POST['email'] ?? ''));
$geraet = trim((string) ($_POST['geraet'] ?? ''));
$fehler = trim((string) ($_POST['fehler'] ?? ''));
$fehlerbeschreibung = trim((string) ($_POST['fehlerbeschreibung'] ?? ''));
$datenschutz = (string) ($_POST['datenschutz'] ?? '0');

$missingFields = [];
if ($name === '') {
    $missingFields[] = 'name';
}
if ($email === '') {
    $missingFields[] = 'email';
}
if ($geraet === '') {
    $missingFields[] = 'geraet';
}
if ($fehler === '') {
    $missingFields[] = 'fehler';
}
if ($fehlerbeschreibung === '') {
    $missingFields[] = 'fehlerbeschreibung';
}
if ($datenschutz !== '1') {
    $missingFields[] = 'datenschutz';
}

if (!empty($missingFields)) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'validation_failed',
        'fields' => $missingFields,
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode([
        'ok' => false,
        'error' => 'invalid_email',
    ]);
    exit;
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $mail = new PHPMailer(true);
    $mail->setLanguage("de", "/PHPMailer/language/");

    $mail->isSMTP();
    $mail->Host = $_ENV["SMTP_HOST"];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV["SMTP_USER"];
    $mail->Password = $_ENV["SMTP_PASSWORD"];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $_ENV["SMTP_PORT"];

    $mail->addReplyTo($email, $name);
    $mail->setFrom($_ENV["SMTP_FROM"], "PhoneDocs");
    $mail->addAddress('sohrab.djahed@computer-extra.de');
    $mail->addBCC('johannes.kirchner@computer-extra.de');
    $mail->isHTML(true);
    $mail->Subject = "PhoneDocs: Neue Reparaturanfrage über die Webseite";
    $mail->CharSet = "UTF-8";

    $messageLines = [
        'Es wurde eine neue Reparaturanfrage ueber die Webseite gesendet.',
        '',
        'Name: ' . $name,
        'Telefonnummer: ' . ($telefonnummer !== '' ? $telefonnummer : '-'),
        'E-Mail: ' . $email,
        'Geraet: ' . $geraet,
        'Fehler: ' . $fehler,
        'Fehlerbeschreibung:',
        $fehlerbeschreibung,
        '',
        'Datenschutz bestaetigt: Ja',
        'Zeitpunkt: ' . date('d.m.Y H:i:s'),
        'IP-Adresse: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unbekannt'),
        'User-Agent: ' . ($_SERVER['HTTP_USER_AGENT'] ?? 'unbekannt'),
    ];
    $message = implode("<br>", $messageLines);
    $mail->Body = $message;
    $mail->send();

    http_response_code(200);
    echo json_encode([
        'ok' => true,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'mail_send_failed',
        'message' => $mail->ErrorInfo,
    ]);
    exit;
}
