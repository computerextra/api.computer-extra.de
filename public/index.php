<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use MyApi\App;
use MyApi\Service\CacheService;
use MyApi\Service\ImageService;
use MyApi\Service\AuditService;

// =======================
// Bootstrap & Konfiguration
// =======================
$config = require __DIR__ . '/../config/bootstrap.php';

// Sicherheitsheader & CORS
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

// App & Services
$app = new App($config);
$cache = new CacheService($config['cache']['dir'], $config['cache']['ttl']);
$imageService = new ImageService($config['upload_dir'], $config['public_url']);
$audit = new AuditService($app);

// Globale Fehlerbehandlung
set_exception_handler(function (Throwable $e) use ($app) {
    $app->logger->error('Unhandled exception', [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
    exit;
});

// =======================
// API-Key Überprüfung
// =======================
$headers = getallheaders();
$apiKey = $headers['X-API-Key'] ?? $headers['x-api-key'] ?? null;
if (!$app->checkApiKey($apiKey)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// =======================
// Routing-Analyse
// =======================
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script = $_SERVER['SCRIPT_NAME'];
$base = rtrim(dirname($script), '/');
$path = preg_replace('#^' . preg_quote($base) . '#', '', $uri);
$path = trim($path, '/');
$parts = array_values(array_filter(explode('/', $path)));
$resource = $parts[0] ?? null;
$id = $parts[1] ?? null;

header('Content-Type: application/json; charset=utf-8');

// ===========================
//   /bilder - Endpunkte
// ===========================
if ($resource === 'bilder') {
    $uploadDir = $config['upload_dir'];
    $publicBase = $config['public_url'];
    $method = $_SERVER['REQUEST_METHOD'];

    // --- GET /bilder ---
    if ($method === 'GET' && !$id) {
        $files = $imageService->listFiles();
        $data = array_map(function ($f) use ($publicBase) {
            return [
                'filename'     => basename($f),
                'url'          => $publicBase . '/' . basename($f),
                'size_kb'      => round(filesize($f) / 1024, 2),
                'modified_at'  => date('Y-m-d H:i:s', filemtime($f)),
            ];
        }, $files);
        echo json_encode(['count' => count($data), 'data' => $data]);
        exit;
    }

    // --- GET /bilder/orphans ---
    if ($method === 'GET' && $id === 'orphans') {
        $knownUrls = [];
        $pattern = $publicBase . '/%';

        foreach ($config['allowed_tables'] as $tbl) {
            $cols = $app->pdo->query("SHOW COLUMNS FROM `$tbl`")->fetchAll(PDO::FETCH_COLUMN);
            $imageCols = array_filter($cols, fn ($c) => preg_match('/bild|image|foto|picture/i', $c));
            foreach ($imageCols as $col) {
                $stmt = $app->pdo->prepare("SELECT `$col` FROM `$tbl` WHERE `$col` LIKE :pat");
                $stmt->execute([':pat' => $pattern]);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $knownUrls[] = $row[$col];
                }
            }
        }

        $knownFiles = array_unique(array_map(fn ($u) => basename(parse_url($u, PHP_URL_PATH)), $knownUrls));
        $orphans = [];
        foreach ($imageService->listFiles() as $filePath) {
            $name = basename($filePath);
            if (!in_array($name, $knownFiles)) {
                $orphans[] = [
                    'filename'     => $name,
                    'url'          => $publicBase . '/' . $name,
                    'size_kb'      => round(filesize($filePath) / 1024, 2),
                    'modified_at'  => date('Y-m-d H:i:s', filemtime($filePath)),
                ];
            }
        }

        echo json_encode([
            'total_files'   => count($imageService->listFiles()),
            'orphans_count' => count($orphans),
            'orphans'       => $orphans
        ]);
        exit;
    }

    // --- DELETE /bilder/orphans?confirm=true ---
    if ($method === 'DELETE' && $id === 'orphans') {
        $confirm = isset($_GET['confirm']) && $_GET['confirm'] === 'true';
        if (!$confirm) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Bestätigung erforderlich',
                'hint'  => 'Führe DELETE /bilder/orphans?confirm=true aus, um verwaiste Dateien zu löschen.'
            ]);
            exit;
        }

        $knownUrls = [];
        $pattern = $publicBase . '/%';

        foreach ($config['allowed_tables'] as $tbl) {
            $cols = $app->pdo->query("SHOW COLUMNS FROM `$tbl`")->fetchAll(PDO::FETCH_COLUMN);
            $imageCols = array_filter($cols, fn ($c) => preg_match('/bild|image|foto|picture/i', $c));
            foreach ($imageCols as $col) {
                $stmt = $app->pdo->prepare("SELECT `$col` FROM `$tbl` WHERE `$col` LIKE :pat");
                $stmt->execute([':pat' => $pattern]);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $knownUrls[] = $row[$col];
                }
            }
        }

        $knownFiles = array_unique(array_map(fn ($u) => basename(parse_url($u, PHP_URL_PATH)), $knownUrls));
        $orphans = [];
        foreach ($imageService->listFiles() as $filePath) {
            if (!in_array(basename($filePath), $knownFiles)) {
                $orphans[] = $filePath;
            }
        }

        $deleted = 0;
        $errors = [];
        foreach ($orphans as $filePath) {
            try {
                unlink($filePath);
                $deleted++;
                $app->logger->info("Orphan-Bild gelöscht: $filePath");
            } catch (Throwable $e) {
                $errors[] = ['file' => $filePath, 'error' => $e->getMessage()];
                $app->logger->warning('Fehler beim Löschen eines Orphan-Bildes', [
                    'file'  => $filePath,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $app->pdo->beginTransaction();
        try {
            $audit->write('bilder', 'DELETE_ORPHANS', null, [
                'deleted_count' => $deleted,
                'errors'        => $errors
            ], null);
            $app->pdo->commit();
        } catch (Throwable $e) {
            $app->pdo->rollBack();
            $app->logger->error('Audit-Fehler beim Orphan-Delete', ['error' => $e->getMessage()]);
        }

        echo json_encode([
            'deleted' => $deleted,
            'errors'  => $errors,
            'message' => 'Verwaiste Bilder gelöscht.'
        ]);
        exit;
    }

    // --- GET /bilder/stats (+ ?days=30 optional) ---
    if ($method === 'GET' && $id === 'stats') {
        $days = isset($_GET['days']) ? (int)$_GET['days'] : null;
        $cutoff = $days ? time() - ($days * 86400) : null;

        $files = $imageService->listFiles();
        $count = count($files);
        $totalSize = 0;
        $oldest = null;
        $newest = null;
        $byType = ['jpg' => 0, 'jpeg' => 0, 'png' => 0, 'webp' => 0];
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

        $average = $count > 0 ? round($totalSize / $count, 2) : 0;
        $response = [
            'total_files'      => $count,
            'total_size_kb'    => round($totalSize / 1024, 2),
            'total_size_mb'    => round($totalSize / 1048576, 2),
            'average_size_kb'  => round($average / 1024, 2),
            'oldest_file'      => $oldest ? date('Y-m-d H:i:s', $oldest) : null,
            'newest_file'      => $newest ? date('Y-m-d H:i:s', $newest) : null,
            'by_type'          => $byType
        ];
        if ($days) {
            $response['period_days']     = $days;
            $response['recent_files']    = $recentCount;
            $response['recent_size_kb']  = round($recentSize / 1024, 2);
            $response['recent_size_mb']  = round($recentSize / 1048576, 2);
        }

        echo json_encode($response);
        exit;
    }

    http_response_code(404);
    echo json_encode(['error' => 'Unknown bilder endpoint']);
    exit;
}

// ===========================
//   CRUD - generisch
// ===========================
if (!$resource || !in_array($resource, $config['allowed_tables'], true)) {
    http_response_code(404);
    echo json_encode(['error' => 'Unknown resource']);
    exit;
}

$model = $app->getModel($resource);
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        if ($id) {
            $res = $model->find($id);
            if (!$res) {
                http_response_code(404);
                echo json_encode(['error' => 'Not found']);
                exit;
            }
            echo json_encode($res);
        } else {
            $limit = (int)($_GET['limit'] ?? 100);
            $offset = (int)($_GET['offset'] ?? 0);
            $cacheKey = "list:$resource:$limit:$offset";
            if ($cached = $cache->get($cacheKey)) {
                echo json_encode($cached);
                exit;
            }
            $res = $model->list($limit, $offset);
            $cache->set($cacheKey, $res);
            echo json_encode($res);
        }
        exit;
    }

    if ($method === 'POST') {
        $isMultipart = strpos($_SERVER['CONTENT_TYPE'] ?? '', 'multipart/form-data') !== false;
        $data = $isMultipart ? $_POST : (array)json_decode(file_get_contents('php://input'), true);

        if ($isMultipart && !empty($_FILES)) {
            foreach ($_FILES as $field => $file) {
                if (preg_match('/bild|image|foto|picture/i', $field)) {
                    $data[$field] = $imageService->handleUpload($file);
                }
            }
        }

        $app->pdo->beginTransaction();
        try {
            $newId = $model->create($data);
            $audit->write($resource, 'CREATE', $newId, $data, null);
            $app->pdo->commit();
            $cache->delete("list:$resource:100:0");
            http_response_code(201);
            echo json_encode(['id' => $newId, 'message' => 'Created']);
            exit;
        } catch (Throwable $e) {
            $app->pdo->rollBack();
            throw $e;
        }
    }

    if (in_array($method, ['PUT', 'PATCH'])) {
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing id']);
            exit;
        }
        $isMultipart = strpos($_SERVER['CONTENT_TYPE'] ?? '', 'multipart/form-data') !== false;
        $data = $isMultipart ? $_POST : (array)json_decode(file_get_contents('php://input'), true);

        if ($isMultipart && !empty($_FILES)) {
            foreach ($_FILES as $field => $file) {
                if (preg_match('/bild|image|foto|picture/i', $field)) {
                    $data[$field] = $imageService->handleUpload($file);
                }
            }
        }

        $app->pdo->beginTransaction();
        try {
            $old = $model->find($id);
            $rows = $model->update($id, $data);
            $payload = ['before' => $old, 'after' => $model->find($id)];
            $audit->write($resource, 'UPDATE', $id, $payload, null);
            $app->pdo->commit();
            $cache->delete("list:$resource:100:0");
            echo json_encode(['message' => 'Updated','rows' => $rows]);
            exit;
        } catch (Throwable $e) {
            $app->pdo->rollBack();
            throw $e;
        }
    }

    if ($method === 'DELETE') {
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing id']);
            exit;
        }
        $app->pdo->beginTransaction();
        try {
            $old = $model->find($id);
            if ($old) {
                foreach ($old as $field => $value) {
                    if (is_string($value) && preg_match('/bild|image|foto|picture/i', $field) && str_starts_with($value, $config['public_url'])) {
                        try {
                            $imageService->deleteByUrl($value);
                        } catch (Throwable $e) {
                            $app->logger->warning('Image delete failed', ['error' => $e->getMessage()]);
                        }
                    }
                }
            }
            $rows = $model->delete($id);
            $audit->write($resource, 'DELETE', $id, ['before' => $old], null);
            $app->pdo->commit();
            $cache->delete("list:$resource:100:0");
            echo json_encode(['message' => 'Deleted','rows' => $rows]);
            exit;
        } catch (Throwable $e) {
            $app->pdo->rollBack();
            throw $e;
        }
    }

    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;

} catch (Throwable $e) {
    $app->logger->error('Request error', ['message' => $e->getMessage()]);
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
    exit;
}
