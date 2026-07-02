<?php

declare(strict_types=1);

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

$to = 'johannes.kirchner@computer-extra.de';
$subject = 'Neue Reparaturanfrage ueber die Webseite';

$safeName = str_replace(["\r", "\n"], ' ', $name);
$safeEmail = str_replace(["\r", "\n"], ' ', $email);

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
    'Zeitpunkt: ' . date('Y-m-d H:i:s'),
    'IP-Adresse: ' . ($_SERVER['REMOTE_ADDR'] ?? 'unbekannt'),
    'User-Agent: ' . ($_SERVER['HTTP_USER_AGENT'] ?? 'unbekannt'),
];
$message = implode("\n", $messageLines);

$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=UTF-8',
    'From: Website Reparaturanfrage <noreply@api.computer-extra.de>',
    'Reply-To: ' . $safeName . ' <' . $safeEmail . '>',
    'X-Mailer: PHP/' . phpversion(),
];

$sent = mail($to, $subject, $message, implode("\r\n", $headers));

if (!$sent) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'mail_send_failed',
    ]);
    exit;
}

http_response_code(200);
echo json_encode([
    'ok' => true,
]);
