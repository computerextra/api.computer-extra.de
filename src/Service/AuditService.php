<?php

namespace MyApi\Service;

use MyApi\App;

class AuditService
{
    private App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function write(string $table, string $operation, ?string $recordId = null, ?array $payload = null, ?string $actor = null): void
    {
        $id = $this->app->generateCuid();
        $sql = "INSERT INTO `audit_log` (`id`,`table_name`,`operation`,`record_id`,`payload`,`actor`,`created_at`)
                VALUES (:id,:table,:op,:rid,:payload,:actor,NOW())";
        $stmt = $this->app->pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id,
            ':table' => $table,
            ':op' => $operation,
            ':rid' => $recordId,
            ':payload' => $payload ? json_encode($payload, JSON_UNESCAPED_UNICODE) : null,
            ':actor' => $actor
        ]);
    }
}
