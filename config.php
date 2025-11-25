<?php

declare(strict_types=1);

const DB_HOST = "127.0.0.1";
const DB_NAME = "db";
const DB_USER = "root";
const DB_PASS = "";
const API_KEY = "SecureKey";
const UPLOAD_DIR = "";

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => true,
    ]);
} catch (PDOException $e) {
    throw new Exception("Database Connection failed: " . $e->getMessage());
}
