<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAuditLog extends AbstractMigration
{
    public function change(): void
    {
        if (!$this->hasTable('audit_log')) {
            $this->table('audit_log', ['id' => false, 'primary_key' => ['id']])
                ->addColumn('id', 'string', ['limit' => 128])
                ->addColumn('table_name', 'string', ['limit' => 128])
                ->addColumn('operation', 'string', ['limit' => 10])
                ->addColumn('record_id', 'string', ['limit' => 128, 'null' => true])
                ->addColumn('payload', 'text', ['null' => true])
                ->addColumn('actor', 'string', ['limit' => 128, 'null' => true])
                ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
                ->create();
        }
    }
}
