<?php

require "../vendor/autoload.php";

Flight::response()->header("Access-Control-Allow-Origin", "*");
Flight::response()->header("Access-Control-Allow-Methods", "GET");
Flight::response()->header("Access-Control-Allow-Methods", "X-Requested-With");

require "../routes/api.php";

Flight::start();