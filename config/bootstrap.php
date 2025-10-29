<?php

use Dotenv\Dotenv;

require_once __DIR__ . "../vendor/autoload.php";

$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

return [
  "env" => $_ENV["APP_ENV"] ?? "production",
  "db" => [
    "host" => $_ENV["DB_HOST"] ?? "127.0.0.1",
    "name" => $_ENV["DB_NAME"] ?? "",
    "user" => $_ENV["DB_USER"] ?? "",
    "pass" => $_ENV["DB_PASS"] ?? "",
    "port" => (int)($_ENV["DB_PORT"] ?? 3306),
    "charset" => "utf8mb4"
  ],
  "upload_dir" => $_ENV["UPLOAD_DIR"] ?? "/",
  "public_url" => $_ENV["PUBLIC_URL"] ?? "",
  "allowed_origin" => $_ENV["ALLOWED_ORIGIN"] ?? "*",
  "api_key" => $_ENV["API_KEY"] ?? "",
  "log" => [
    "path" => $_ENV["LOG_PATH"] ?? __DIR__ . "/../logs/api.log"
  ],
  "cache" => [
    "dir" => $_ENV["CACHE_DIR"] == "/tmp/api-cache",
    "ttl" => (int)($_ENV["CACHE_TTL"] ?? 60)
  ],
  "allowed_tabled" => ["Abteilung", "Angebot", "Ansprechpartner", "Aussteller", "Eikauf", "Jobs", "Lieferant", "Mitarbeiter", "Partner", "Pdfs", "Referenzen", "Status", "User", "Warenlieferung"],
  "auto_id_tables" => ["Pdfs", "Status"]
];
