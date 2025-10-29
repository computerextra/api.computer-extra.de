<?php

declare(strict_types=1);

namespace MyApi;

use PDO;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use MyApi\Middleware\QueryLoggerMiddleware;
use Throwable;

class App
{
    public PDO $pdo;
    public Logger $logger;
    public array $config;

    /**
     * Haupt-App-Klasse
     * Verantwortlich für:
     *  - PDO-Initialisierung
     *  - Logging
     *  - Zugriff auf Models & Services
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        // =========================
        // 💾 Datenbankverbindung
        // =========================
        try {
            $this->pdo = new PDO(
                $config['db']['dsn'],
                $config['db']['user'],
                $config['db']['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => true, // 🔹 Persistent Connection
                ]
            );

            // Slow Query Logging aktivieren
            $this->pdo->setAttribute(PDO::ATTR_STATEMENT_CLASS, [QueryLoggerMiddleware::class, [$this]]);

        } catch (Throwable $e) {
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
        }

        // =========================
        // 🧾 Logger einrichten
        // =========================
        $logDir = $config['log']['dir'] ?? (__DIR__ . '/../../logs');
        if (!is_dir($logDir)) {
            mkdir($logDir, 0775, true);
        }

        $logLevel = match (strtolower($config['log']['level'] ?? 'debug')) {
            'error' => Logger::ERROR,
            'warning' => Logger::WARNING,
            'info' => Logger::INFO,
            default => Logger::DEBUG,
        };

        $this->logger = new Logger('app');
        $this->logger->pushHandler(
            new StreamHandler($logDir . '/app.log', $logLevel)
        );

        // optional: JSON-Log aktivieren
        // use Monolog\Formatter\JsonFormatter;
        // $handler->setFormatter(new JsonFormatter());
    }

    // =====================================
    // 🔐 API-Key-Überprüfung
    // =====================================
    public function checkApiKey(?string $key): bool
    {
        if (!$key || !isset($this->config['api_key'])) {
            return false;
        }

        // konstantes Zeitvergleich, um Timing-Angriffe zu vermeiden
        return hash_equals($this->config['api_key'], $key);
    }

    // =====================================
    // 🧱 Dynamisches Model-Laden
    // =====================================
    public function getModel(string $resource)
    {
        $class = '\\MyApi\\Model\\' . ucfirst($resource);
        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Model not found for resource: $resource");
        }

        return new $class($this);
    }

    // =====================================
    // 🧰 Hilfsfunktion: Logging Helper
    // =====================================
    public function log(string $level, string $message, array $context = []): void
    {
        if (method_exists($this->logger, $level)) {
            $this->logger->$level($message, $context);
        } else {
            $this->logger->info($message, $context);
        }
    }

    // =====================================
    // 💡 Utility: Healthcheck (optional)
    // =====================================
    public function health(): array
    {
        try {
            $stmt = $this->pdo->query('SELECT 1');
            $dbOk = $stmt !== false;
        } catch (Throwable $e) {
            $dbOk = false;
        }

        return [
            'status' => $dbOk ? 'ok' : 'error',
            'database' => $dbOk,
            'timestamp' => date('Y-m-d H:i:s'),
        ];
    }
}
