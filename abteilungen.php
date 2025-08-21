<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type:application/json");
require_once "./config.php";
require_once "./api.php";

// Get Server Action
$action = $_GET["action"] ?? "";
$api = new API();

echo $action;

switch ($action) {
    case "GET": {
        echo json_encode($api->get_abteilungen());
        break;
    }
    default: {
        echo json_encode($api->method_not_allowed());
        break;
    }
}