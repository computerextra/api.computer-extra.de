<?php

declare(strict_types=1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=UTF-8');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'OPTIONS') {
    http_response_code(204);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
    http_response_code(405);
    header('Allow: GET, OPTIONS');
    echo json_encode(['success' => false, 'error' => 'method_not_allowed']);
    exit;
}

require_once __DIR__ . '/bootstrap.php';

try {
    $preise = db_connect()
        ->query('SELECT id, hersteller, geraet, reparatur, preis FROM phonedocs')
        ->fetchAll();

    echo json_encode(
        ['success' => true, 'data' => $preise, 'count' => count($preise)],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR
    );
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'internal_server_error']);
}
