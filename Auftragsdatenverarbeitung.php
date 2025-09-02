<?php
require_once __DIR__ . "/vendor/autoload.php";

require __DIR__ . "/Auftragsdaten/UserInput.php";
require __DIR__ . "/Auftragsdaten/Sections.php";
require __DIR__ . "/Auftragsdaten/Anlagen.php";

use \Mpdf\Mpdf;
use \Mpdf\MpdfException;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

date_default_timezone_set('Europe/Berlin');

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

$_POST = json_decode(file_get_contents('php://input'), true);

$Kundennummer = $_POST["Kundennummer"];
$Firma = $_POST["Firma"];
$Adresse = $_POST["Adresse"];
$Postleitzahl = $_POST["Postleitzahl"];
$Ort = $_POST["Ort"];
$Vertretungsberechtigter = $_POST["Vertretungsberechtigter"];
$EMail = $_POST["EMail"];

$filename = createPdf();
sendMail($filename);

echo json_encode(["message" => "Erfolgreich erstellt und versendet", "filename" => $filename, "status" => 200]);
exit();

function createPdf()
{
  global $Kundennummer;
  global $Firma;
  global $Adresse;
  global $Postleitzahl;
  global $Ort;
  global $Vertretungsberechtigter;

  try {
    $mpdf = new Mpdf(["format" => "A4", 'mode' => 'utf-8']);

    $mpdf->PDFA = true;

    $mpdf->SetAuthor('Computer Extra GmbH'); // add the author name
    // $mpdf->setHeader('Datenschutzvereinbarung zur Auftragsverarbeitung gemäß Art. 28 DS-GVO');
    $mpdf->setFooter('Computer Extra GmbH|Stand: {DATE d.m.Y (H:i)}|Seite: {PAGENO}');

    $mpdf->WriteHTML(Einleitung(Kundennummer: $Kundennummer, Firma: $Firma, Adresse: $Adresse, Ort: "$Postleitzahl $Ort"));
    $mpdf->WriteHTML(Präambel());
    $mpdf->WriteHTML(Section_1());
    $mpdf->WriteHTML(Section_2());
    $mpdf->WriteHTML(Section_3());
    $mpdf->WriteHTML(Section_4());
    $mpdf->WriteHTML(Section_5());
    $mpdf->WriteHTML(Section_6());
    $mpdf->WriteHTML(Section_7());
    $mpdf->WriteHTML(Section_8());
    $mpdf->WriteHTML(Section_9());
    $mpdf->WriteHTML(Section_10());
    $mpdf->WriteHTML(Section_11());
    $mpdf->WriteHTML(Section_12());
    $mpdf->WriteHTML(Section_13());
    $mpdf->WriteHTML(Unterschrift(Ort: $Ort, Vertretungsberechtigter: $Vertretungsberechtigter));

    $mpdf->AddPage();
    $mpdf->Bookmark('Anlage A');
    $mpdf->WriteHTML(Anlage_A());
    $mpdf->AddPage();
    $mpdf->Bookmark('Anlage B');
    $mpdf->WriteHTML(Anlage_B());

    $date = date('d.m.Y', time());
    $filename = "AVV-$Kundennummer-$date.pdf";
    $mpdf->OutputFile(__DIR__ . "/AVV/$filename");
    return $filename;
  } catch (MpdfException $e) {
    $msg = $e->getMessage();
    echo json_encode(["message" => "Fehler: $msg", "filename" => null, "status" => 500]);
    exit();
  }
}

function sendMail(string $filename)
{
  global $EMail;
  global $Firma;
  global $Adresse;
  global $Postleitzahl;
  global $Ort;

  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();

  $mail = new PHPMailer(true);
  try {
    $mail->setLanguage("de", "/PHPMailer/language/");

    // Server Settings
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USER'];
    $mail->Password = $_ENV['SMTP_PASSWORD'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = $_ENV['SMTP_PORT'];


    $mail->setFrom("noreply@computer-extra.de", "Computer Extra GmbH");
    $mail->addAddress($EMail);
    $mail->addBCC($_ENV["SMTP_DATENSCHUTZ"]);
    // $mail->addBCC($_ENV["SMTP_BEWERBUNG"]);
    $mail->isHTML(true);

    $mail->Subject = "Neue Vereinbarung zur Auftragsverarbeitung";
    $mail->CharSet = 'UTF-8';
    $mail->Body = <<<HTML
    <p>Lieber Kunde,</p>
    <p>
      vielen Dank für den elektronischen Abschluss einer Vereinbarung zur
      Auftragsverarbeitung gem. Art. 28 DS-GVO.
    </p>
    <p>
      Wir haben Ihre Vertragsdaten erhalten und bereits verarbeitet: <br />
      $Firma<br />
      $Adresse<br />
      $Postleitzahl $Ort<br />
      $EMail
    </p>
    <p>Vielen Dank für Ihre Unterstützung</p>
    <hr />
    <p>
      Computer Extra GmbH <br />
      Harleshäuser Str. 8<br />
      34130 Kassel<br />
      Deutschland
    </p>
    <hr />
    <p>
      <em>
        Diese E-Mail wurde automatisch von der
        <a
          href="https://computer-extra.de"
          target="_blank"
          rel="noopener noreferrer"
          >Computer Extra GmbH</a
        >
        gesendet.
      </em>
    </p>
    HTML;
    $mail->addAttachment(__DIR__ . "/AVV/$filename", $filename);
    $mail->send();

  } catch (Exception $e) {
    echo json_encode(["message" => $mail->ErrorInfo, "status" => 500]);
    exit();
  }
}