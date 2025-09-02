<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/vendor/autoload.php";

require __DIR__ . "/Auftragsdaten/Einleitung.php";
require __DIR__ . "/Auftragsdaten/Sections.php";

use \Mpdf\Mpdf;
use \Mpdf\MpdfException;

date_default_timezone_set('Europe/Berlin');

// CORS Headers für alle Clients erlauben
header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Nur POST-Methoden erlauben
// if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//   echo json_encode(["message" => "Nur POST-Methoden sind erlaubt", "status" => 405]);
//   exit();
// }

// $_POST = json_decode(file_get_contents('php://input'), true);

// $Kundennummer = $_POST["Kundennummer"];
// $Firma = $_POST["Firma"];
// $Adresse = $_POST["Adresse"];
// $Postleitzahl = $_POST["Postleitzahl"];
// $Ort = $_POST["Ort"];
// $Land = $_POST["Land"];
// $Vertretungsberechtigter = $_POST["Vertretungsberechtigter"];

try {
  $mpdf = new Mpdf(["format" => "A4", 'mode' => 'utf-8']);

  $mpdf->PDFA = true;

  $mpdf->SetAuthor('Computer Extra GmbH'); // add the author name
  // $mpdf->setHeader('Datenschutzvereinbarung zur Auftragsverarbeitung gemäß Art. 28 DS-GVO');
  $mpdf->setFooter('Computer Extra GmbH|Stand: {DATE d.m.Y (H:i)}|Seite: {PAGENO}');

  $mpdf->WriteHTML(Einleitung("D12345", "TestFirma", "Harleshäuser Str. 8", "34130 Kassel"));
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

  //      <section id="Unterschriften">
// `;

  //   // TODO:   <p>Kaufungen, 25.08.2025</p>
// //       <p>gez. Markus Barella (Vertretungsberechtigter des Auftraggebers)</p>

  //   $body .= `
//       <p>Kassel, 25.08.2025</p>
//       <p>gez. Christian Krauss (Computer Extra GmbH)</p>
//     </section>`;




  // $mpdf->WriteHTML($body);
  // $date = date('d.m.Y', time());
  // $filename = "AVV-$Kundennummer-$date.pdf";
  // $mpdf->OutputFile(__DIR__ . "/$filename");
  $mpdf->Output();

  // echo json_encode(["message" => "PDF Erfolgreich erstellt.", "filename" => $filename, "status" => 200]);
  exit();
} catch (MpdfException $e) {
  $msg = $e->getMessage();
  echo json_encode(["message" => "Fehler: $msg", "filename" => null, "status" => 500]);
  // exit();
}
