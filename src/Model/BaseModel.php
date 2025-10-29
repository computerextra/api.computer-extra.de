<?php

namespace MyApi\Model;

use MyApi\App;
use PDO;

abstract class BaseModel
{
    protected App $app;
    protected PDO $pdo;
    protected string $table;
    protected string $primaryKey = 'id';
    protected bool $autoIncrement = false;
    protected array $fillable = [];

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->pdo = $app->pdo;
    }

    public function filterInput(array $data): array
    {
        $out = [];
        foreach ($this->fillable as $col) {
            if (array_key_exists($col, $data)) {
                $out[$col] = $data[$col];
            }
        }
        return $out;
    }

    public function find(string $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function list(int $limit = 100, int $offset = 0): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM `{$this->table}` LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create(array $data): string
    {
        $data = $this->filterInput($data);
        if (!$this->autoIncrement && empty($data[$this->primaryKey])) {
            $data[$this->primaryKey] = $this->app->generateCuid();
        } else {
            unset($data[$this->primaryKey]);
        }

        $cols = array_keys($data);
        if (empty($cols)) {
            throw new \InvalidArgumentException('No fields provided');
        }
        $placeholders = array_map(fn ($c) => ':' . $c, $cols);
        $sql = 'INSERT INTO `' . $this->table . '` (`' . implode('`,`', $cols) . '`) VALUES (' . implode(',', $placeholders) . ')';
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->execute();

        return $this->autoIncrement ? (string)$this->pdo->lastInsertId() : (string)$data[$this->primaryKey];
    }

    public function update(string $id, array $data): int
    {
        $data = $this->filterInput($data);
        unset($data[$this->primaryKey]);
        if (empty($data)) {
            return 0;
        }
        $sets = implode(',', array_map(fn ($c) => "`$c` = :$c", array_keys($data)));
        $sql = "UPDATE `{$this->table}` SET $sets WHERE `{$this->primaryKey}` = :__id";
        $stmt = $this->pdo->prepare($sql);
        foreach ($data as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':__id', $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function delete(string $id): int
    {
        $stmt = $this->pdo->prepare("DELETE FROM `{$this->table}` WHERE `{$this->primaryKey}` = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }
}
