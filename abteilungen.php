<?php
header('Access-Control-Allow-Origin: *');
header("Content-Type:application/json");
require_once "./config.php";
require_once "./api.php";

$api = new API();

if (!empty($_POST)) {
    echo json_encode($api->get_abteilungen());
} else {
    echo json_encode($api->method_not_allowed());
}