<?php

use App\Controllers\AngebotController;
use App\Controllers\AbteilungController;
use App\Controllers\JobController;
use App\Controllers\MitarbeiterController;
use App\Controllers\PartnerController;
use App\Controllers\ReferenzController;

// Abteilungen
Flight::route("GET /abteilungen", [new AbteilungController(), "index"]);

// Angebote
Flight::route("GET /angebote", [new AngebotController(), "online"]);

// Jobs
Flight::route("GET /jobs", [new JobController(), "index"]);

// Mitarbeiter
Flight::route("GET /mitarbeiter", [new MitarbeiterController(), "index"]);

// Partner
Flight::route("GET /partner", [new PartnerController(), "index"]);

// Referenzen
Flight::route("GET /referenzen", [new ReferenzController(), "index"]);