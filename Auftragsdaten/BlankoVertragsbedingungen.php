<?php
require __DIR__ . "/UserInput.php";
require __DIR__ . "/Sections.php";

date_default_timezone_set('Europe/Berlin');

// CORS Headers für alle Clients erlauben
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$body = Einleitung("[XXXXX]", "[XXXXX]", "[XXXXX]", "[XXXXX] [XXXXX]");
$body .= Präambel();
$body .= Section_1();
$body .= Section_2();
$body .= Section_3();
$body .= Section_4();
$body .= Section_5();
$body .= Section_6();
$body .= Section_7();
$body .= Section_8();
$body .= Section_9();
$body .= Section_10();
$body .= Section_11();
$body .= Section_12();
$body .= Section_13();
$body .= Unterschrift("[XXXXX]", "[XXXXX]");

echo json_encode(["message" => $body, "status" => 200]);
exit();

