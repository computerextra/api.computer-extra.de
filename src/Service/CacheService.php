<?php

namespace MyApi\Service;

class CacheService
{
    private string $dir;
    private int $ttl;

    public function __construct(string $dir, int $ttl = 60)
    {
        $this->dir = rtrim($dir, '/');
        $this->ttl = $ttl;
        if (!is_dir($this->dir)) {
            mkdir($this->dir, 0777, true);
        }
    }

    private function file(string $key): string
    {
        return $this->dir . '/' . md5($key) . '.json';
    }

    public function get(string $key): mixed
    {
        $file = $this->file($key);
        if (!is_file($file)) {
            return null;
        }
        if (filemtime($file) < time() - $this->ttl) {
            return null;
        }
        return json_decode(file_get_contents($file), true);
    }

    public function set(string $key, mixed $value): void
    {
        file_put_contents($this->file($key), json_encode($value));
    }

    public function delete(string $key): void
    {
        @unlink($this->file($key));
    }
}
