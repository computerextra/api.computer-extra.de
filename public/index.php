<?php

require "../vendor/autoload.php";

use App\Helpers\CorsUtil;

$CorsUil = new CorsUtil();

Flight::before("start", [$CorsUil, "setupCors"]);

require "../routes/api.php";

Flight::start();