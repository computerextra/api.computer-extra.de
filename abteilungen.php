<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


header('Access-Control-Allow-Origin: *');
header("Content-Type:applicytion/json");
require_once "./config.php";
require_once "./api.php";

// Get Server Action
$action = $_GET["action"] ?? "";
$api = new API();

switch ($action) {
    case "GET": {
        echo json_encode($api->get_abteilungen());
    }
    default: {
        echo json_encode($api->method_not_allowed());
    }
}