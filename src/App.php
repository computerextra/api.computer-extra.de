<?php

namespace MyApi;

use PDO;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Visus\Cuid2\CuidFactory;

class App
{
    public PDO $pdo;
    public Logger $logger;
    public array $config;
    private CuidFactory $cuidFactory;

    public function __construct(array $config)
    {
        $this->config = $config;
        $db = $config["db"];
        $dsn = "mysql:host={$db["host"]};port={$db["port"]};dbname={$db["name"]};charset={$db["charset"]}";
        $this->pdo = new PDO($dsn, $db["user"], $db["pass"], [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $this->logger = new Logger("api");
        $this->logger->pushHandler(new StreamHandler($config["log"]["path"] ?? __DIR__ . "/../logs/api.log"));

        $this->cuidFactory = new CuidFactory();
    }

    public function checkApiKey(?string $key): bool
    {
        if (!$key) {
            return false;
        }
        return hash_equals($this->config["api_key"], $key);
    }

    public function generateCuid(): string
    {
        return $this->cuidFactory->create();
    }

    public function getModel(string $table)
    {
        $map = [
          "Mitarbeiter" => \MyApi\Model\Mitarbeiter::class,
          "Abteilung" => \MyApi\Model\Abteilung::class,
          "Angebot" => \MyApi\Model\Angebot::class,
          "Ansprechpartner" => \MyApi\Model\Ansprechpartner::class,
          "Aussteller" => \MyApi\Model\Aussteller::class,
          "Einkauf" => \MyApi\Model\Einkauf::class,
          "Jobs" => \MyApi\Model\Jobs::class,
          "Lieferant" => \MyApi\Model\Lieferant::class,
          "Partner" => \MyApi\Model\Partner::class,
          "Pdfs" => \MyApi\Model\Pdfs::class,
          "Refernzen" => \MyApi\Model\Refernzen::class,
          "Status" => \MyApi\Model\Status::class,
          "User" => \MyApi\Model\User::class,
          "Warenlieferung" => \MyApi\Model\Warenlieferung::class,
        ];

        if (!isset($map[$table])) {
            throw new \RuntimeException("Model for table $table not found");
        }
        $cls = $map[$table];
        return new $cls[$this]();
    }
}
