<?php

namespace MyApi\Controller;

use MyApi\App;
use MyApi\Service\AuditService;
use Throwable;

class CrudController
{
    public function __construct(
        private App $app,
        private AuditService $audit
    ) {
    }

    public function handle(string $table, ?string $id, string $method, ?array $input): void
    {
        $model = $this->app->getModel($table);

        switch ($method) {
            case 'GET':
                echo json_encode($id ? $model->find($id) : $model->list());
                break;

            case 'POST':
                $this->app->pdo->beginTransaction();
                try {
                    $newId = $model->create($input);
                    $this->audit->write($table, 'CREATE', $newId, $input);
                    $this->app->pdo->commit();
                    echo json_encode(['id' => $newId]);
                } catch (Throwable $e) {
                    $this->app->pdo->rollBack();
                    throw $e;
                }
                break;

            default:
                http_response_code(405);
        }
    }
}
