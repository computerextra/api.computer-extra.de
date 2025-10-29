<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

// ===========================
// 🔹 ENV laden
// ===========================
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

// ===========================
// 🔹 Konfiguration
// ===========================
return [
    'env' => $_ENV['APP_ENV'] ?? 'development',

    'db' => [
        'dsn'  => sprintf(
            'mysql:host=%s;dbname=%s;charset=utf8mb4',
            $_ENV['DB_HOST'] ?? 'localhost',
            $_ENV['DB_NAME'] ?? 'computerextra'
        ),
        'user' => $_ENV['DB_USER'] ?? 'root',
        'pass' => $_ENV['DB_PASS'] ?? '',
    ],

    'api_key' => $_ENV['API_KEY'] ?? 'changeme123',

    'allowed_origin' => $_ENV['CORS_ORIGIN'] ?? '*',

    'upload_dir' => $_ENV['UPLOAD_DIR'] ?? '/var/www/bilder.computer-extra.de/data',
    'public_url' => $_ENV['PUBLIC_URL'] ?? 'https://bilder.computer-extra.de/data',

    'cache' => [
        'dir' => __DIR__ . '/../storage/cache',
        'ttl' => 3600,
    ],

    'log' => [
        'dir' => __DIR__ . '/../logs',
        'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
    ],

    // Tabellen für CRUD
    'allowed_tables' => [
        'Mitarbeiter', 'Abteilung', 'Einkauf', 'Lieferant',
        'Warenlieferung', 'Status', 'Jobs', 'Angebot',
        'Partner', 'Referenzen', 'Pdfs', 'Aussteller', 'User', 'Ansprechpartner'
    ],
];
