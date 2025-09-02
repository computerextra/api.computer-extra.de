<?php
require __DIR__ . "/Anlagen.php";


date_default_timezone_set('Europe/Berlin');

// CORS Headers für alle Clients erlauben
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

echo json_encode(["message" => Anlage_A(), "status" => 200]);
exit();

