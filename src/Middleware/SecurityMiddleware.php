<?php

namespace MyApi\Middleware;

use MyApi\Utils\Sanitizer;

class SecurityMiddleware
{
    public static function sanitizeInput(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH') {
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            if (str_contains($contentType, 'application/json')) {
                $raw = file_get_contents('php://input');
                $data = json_decode($raw, true);
                $_POST = Sanitizer::arrayClean($data ?? []);
            }
        }
    }
}
