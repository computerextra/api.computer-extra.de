<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use DI\ContainerBuilder;
use MyApi\App;
use MyApi\Middleware\SecurityMiddleware;
use MyApi\Service\CacheService;
use MyApi\Service\ImageService;
use MyApi\Service\AuditService;

// ========================================
// 🔹 1. Input-Sanitizing (XSS / Injection)
// ========================================
SecurityMiddleware::sanitizeInput();

// ========================================
// 🔹 2. Dependency Injection Container
// ========================================
$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/di.php');
$container = $builder->build();

// App-Komponenten aus Container
$app = $container->get(App::class);
$cache = $container->get(CacheService::class);
$imageService = $container->get(ImageService::class);
$audit = $container->get(AuditService::class);

$config = $app->config;

// ========================================
// 🔹 3. HTTP & Sicherheits-Header
// ========================================
header('Access-Control-Allow-Origin: ' . $config['allowed_origin']);
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, X-API-Key');
header('Access-Control-Allow-Credentials: true');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
header("Content-Security-Policy: default-src 'self'");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

// ========================================
// 🔹 4. API-Key Validierung
// ========================================
$headers = getallheaders();
$apiKey = $headers['X-API-Key'] ?? $headers['x-api-key'] ?? null;
if (!$app->checkApiKey($apiKey)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// ========================================
// 🔹 5. Routing
// ========================================
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script = $_SERVER['SCRIPT_NAME'];
$base = rtrim(dirname($script), '/');
$path = preg_replace('#^' . preg_quote($base) . '#', '', $uri);
$path = trim($path, '/');
$parts = array_values(array_filter(explode('/', $path)));
$resource = $parts[0] ?? null;
$id = $parts[1] ?? null;

// ========================================
// 🔹 6. Fehlerbehandlung
// ========================================
set_exception_handler(function (Throwable $e) use ($app) {
    $app->logger->error('Unhandled exception', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
    exit;
});

// ========================================
// 🔹 7. Endpoints
// ========================================

// 🖼️  BILDER-ENDPUNKTE
if ($resource === 'bilder') {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET' && !$id) {
        $files = $imageService->listFiles();
        $data = array_map(fn ($f) => [
            'filename' => basename($f),
            'url' => $config['public_url'] . '/' . basename($f),
            'size_kb' => round(filesize($f) / 1024, 2),
            'modified_at' => date('Y-m-d H:i:s', filemtime($f)),
        ], $files);

        echo json_encode(['count' => count($data), 'data' => $data]);
        exit;
    }

    // Statistik-Endpoint
    if ($method === 'GET' && $id === 'stats') {
        $days = isset($_GET['days']) ? (int)$_GET['days'] : null;
        $cutoff = $days ? time() - ($days * 86400) : null;

        $files = $imageService->listFiles();
        $count = count($files);
        $totalSize = 0;
        $oldest = null;
        $newest = null;
        $byType = ['jpg' => 0,'jpeg' => 0,'png' => 0,'webp' => 0];
        $recentCount = 0;
        $recentSize = 0;

        foreach ($files as $filePath) {
            $size = filesize($filePath);
            $totalSize += $size;
            $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if (isset($byType[$ext])) {
                $byType[$ext]++;
            }
            $mtime = filemtime($filePath);
            if (!$oldest || $mtime < $oldest) {
                $oldest = $mtime;
            }
            if (!$newest || $mtime > $newest) {
                $newest = $mtime;
            }
            if ($cutoff && $mtime >= $cutoff) {
                $recentCount++;
                $recentSize += $size;
            }
        }

        $avg = $count ? round($totalSize / $count, 2) : 0;
        $response = [
            'total_files' => $count,
            'total_size_mb' => round($totalSize / 1048576, 2),
            'average_size_kb' => round($avg / 1024, 2),
            'by_type' => $byType,
            'oldest_file' => $oldest ? date('Y-m-d H:i:s', $oldest) : null,
            'newest_file' => $newest ? date('Y-m-d H:i:s', $newest) : null,
        ];
        if ($days) {
            $response['period_days'] = $days;
            $response['recent_files'] = $recentCount;
            $response['recent_size_mb'] = round($recentSize / 1048576, 2);
        }

        echo json_encode($response);
        exit;
    }

    http_response_code(404);
    echo json_encode(['error' => 'Unknown bilder endpoint']);
    exit;
}

// 🧱 GENERISCHE CRUD-ENDPUNKTE
if (!$resource || !in_array($resource, $config['allowed_tables'], true)) {
    http_response_code(404);
    echo json_encode(['error' => 'Unknown resource']);
    exit;
}

$model = $app->getModel($resource);
$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            if ($id) {
                $data = $model->find($id);
                if (!$data) {
                    http_response_code(404);
                    echo json_encode(['error' => 'Not found']);
                    exit;
                }
                echo json_encode($data);
                exit;
            }
            $limit = (int)($_GET['limit'] ?? 100);
            $offset = (int)($_GET['offset'] ?? 0);
            $cacheKey = "list:$resource:$limit:$offset";
            if ($cached = $cache->get($cacheKey)) {
                echo json_encode($cached);
                exit;
            }
            $data = $model->list($limit, $offset);
            $cache->set($cacheKey, $data);
            echo json_encode($data);
            exit;

        case 'POST':
            $payload = $_POST ?: (array)json_decode(file_get_contents('php://input'), true);
            $app->pdo->beginTransaction();
            $newId = $model->create($payload);
            $audit->write($resource, 'CREATE', $newId, $payload, null);
            $app->pdo->commit();
            http_response_code(201);
            echo json_encode(['id' => $newId, 'message' => 'Created']);
            exit;

        case 'PUT':
        case 'PATCH':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing id']);
                exit;
            }
            $payload = $_POST ?: (array)json_decode(file_get_contents('php://input'), true);
            $app->pdo->beginTransaction();
            $before = $model->find($id);
            $model->update($id, $payload);
            $after = $model->find($id);
            $audit->write($resource, 'UPDATE', $id, ['before' => $before,'after' => $after], null);
            $app->pdo->commit();
            echo json_encode(['message' => 'Updated']);
            exit;

        case 'DELETE':
            if (!$id) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing id']);
                exit;
            }
            $app->pdo->beginTransaction();
            $old = $model->find($id);
            $model->delete($id);
            $audit->write($resource, 'DELETE', $id, ['before' => $old], null);
            $app->pdo->commit();
            echo json_encode(['message' => 'Deleted']);
            exit;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
    }

} catch (Throwable $e) {
    $app->pdo->rollBack();
    $app->logger->error('Request failed', ['error' => $e->getMessage()]);
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
    exit;
}
