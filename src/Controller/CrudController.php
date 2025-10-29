<?php

namespace MyApi\Controller;

use MyApi\App;
use MyApi\Service\AuditService;
use MyApi\Service\CacheService;
use MyApi\Service\ImageService;
use Throwable;

class CrudController
{
    public function __construct(
        protected App $app,
        protected CacheService $cache,
        protected ImageService $imageService,
        protected AuditService $audit
    ) {
    }

    /**
     * Handle generic CRUD for table $resource.
     * $id may be null for collection endpoints.
     */
    public function handle(string $resource, ?string $id, string $method): void
    {
        $model = $this->app->getModel($resource);

        switch ($method) {
            case 'GET':
                if ($id) {
                    $row = $model->find($id);
                    if (!$row) {
                        http_response_code(404);
                        echo json_encode(['error' => 'Not found']);
                        return;
                    }
                    echo json_encode($row);
                    return;
                }

                $limit = (int)($_GET['limit'] ?? 100);
                $offset = (int)($_GET['offset'] ?? 0);
                $cacheKey = "list:$resource:$limit:$offset";
                if ($cached = $this->cache->get($cacheKey)) {
                    echo json_encode($cached);
                    return;
                }
                $data = $model->list($limit, $offset);
                $this->cache->set($cacheKey, $data);
                echo json_encode($data);
                return;

            case 'POST':
                $isMultipart = strpos($_SERVER['CONTENT_TYPE'] ?? '', 'multipart/form-data') !== false;
                $input = $isMultipart ? $_POST : (array)json_decode(file_get_contents('php://input'), true);
                if ($isMultipart && !empty($_FILES)) {
                    foreach ($_FILES as $field => $file) {
                        if (preg_match('/bild|image|foto|picture/i', $field)) {
                            $input[$field] = $this->imageService->handleUpload($file);
                        }
                    }
                }
                $this->app->pdo->beginTransaction();
                try {
                    $newId = $model->create($input);
                    $this->audit->write($resource, 'CREATE', $newId, $input, null);
                    $this->app->pdo->commit();
                    $this->cache->delete("list:$resource:100:0");
                    http_response_code(201);
                    echo json_encode(['id' => $newId, 'message' => 'Created']);
                    return;
                } catch (Throwable $e) {
                    $this->app->pdo->rollBack();
                    throw $e;
                }

            case 'PUT':
            case 'PATCH':
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Missing id']);
                    return;
                }
                $isMultipart = strpos($_SERVER['CONTENT_TYPE'] ?? '', 'multipart/form-data') !== false;
                $input = $isMultipart ? $_POST : (array)json_decode(file_get_contents('php://input'), true);
                if ($isMultipart && !empty($_FILES)) {
                    foreach ($_FILES as $field => $file) {
                        if (preg_match('/bild|image|foto|picture/i', $field)) {
                            $input[$field] = $this->imageService->handleUpload($file);
                        }
                    }
                }
                $this->app->pdo->beginTransaction();
                try {
                    $old = $model->find($id);
                    $rows = $model->update($id, $input);
                    $payload = ['before' => $old, 'after' => $model->find($id)];
                    $this->audit->write($resource, 'UPDATE', $id, $payload, null);
                    $this->app->pdo->commit();
                    $this->cache->delete("list:$resource:100:0");
                    echo json_encode(['message' => 'Updated', 'rows' => $rows]);
                    return;
                } catch (Throwable $e) {
                    $this->app->pdo->rollBack();
                    throw $e;
                }

            case 'DELETE':
                if (!$id) {
                    http_response_code(400);
                    echo json_encode(['error' => 'Missing id']);
                    return;
                }
                $this->app->pdo->beginTransaction();
                try {
                    $old = $model->find($id);
                    // remove referenced images before deleting record
                    if ($old) {
                        foreach ($old as $field => $value) {
                            if (is_string($value) && preg_match('/bild|image|foto|picture/i', $field) && str_starts_with($value, $this->app->config['public_url'])) {
                                try {
                                    $this->imageService->deleteByUrl($value);
                                } catch (Throwable $e) {
                                    $this->app->logger->warning('Image delete failed', ['error' => $e->getMessage()]);
                                }
                            }
                        }
                    }
                    $rows = $model->delete($id);
                    $this->audit->write($resource, 'DELETE', $id, ['before' => $old], null);
                    $this->app->pdo->commit();
                    $this->cache->delete("list:$resource:100:0");
                    echo json_encode(['message' => 'Deleted', 'rows' => $rows]);
                    return;
                } catch (Throwable $e) {
                    $this->app->pdo->rollBack();
                    throw $e;
                }

            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
                return;
        }
    }
}
