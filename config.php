<?php

declare(strict_types=1);

class Config
{
    private $DB_HOST;
    private $DB_NAME;
    private $DB_USER;
    private $DB_PASS;
    public $UPLOAD_DIR;
    public $pdo;

    public function __construct()
    {
        $this->setConfig();
        $this->getPdo();
    }

    private function checkEnv(string $key, bool $isRequired)
    {
        $test = getenv($key);
        if ((!isset($test) || empty($test)) && $isRequired) {
            throw new ErrorException("$key is missing in .env file!");
        }
        if (isset($test) && !empty($test)) {
            return (string) $test;
        } else {
            return null;
        }
    }

    private function setConfig()
    {
        $this->readEnv();
        $this->DB_HOST = $this->checkEnv("DB_HOST", true);
        $this->DB_NAME = $this->checkEnv("DB_NAME", true);
        $this->DB_USER = $this->checkEnv("DB_USER", true);
        $this->DB_PASS = $this->checkEnv("DB_PASS", false);
        $this->UPLOAD_DIR = $this->checkEnv("UPLOAD_DIR", true);
    }

    private function readEnv()
    {
        $env = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . ".env");
        $lines = explode("\n", $env);

        foreach ($lines as $line) {
            preg_match("/([^#]+)\=(.*)/", $line, $matches);
            if (isset($matches[2])) {
                putenv(trim($line));
            }
        }
    }

    private function getPdo()
    {
        try {
            $pdo = new PDO("mysql:host=$this->DB_HOST;dbname=$this->DB_NAME", $this->DB_USER, $this->DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => true,
            ]);
            $this->pdo = $pdo;
        } catch (PDOException $e) {
            throw new Exception("Database Connection failed: " . $e->getMessage());
        }
    }
}