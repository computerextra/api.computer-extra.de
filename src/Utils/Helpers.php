<?php

namespace MyApi\Utils;

class Helpers
{
    public static function isImageFieldName(string $name): bool
    {
        return (bool)preg_match('/bild|image|foto|picture/i', $name);
    }

    public static function basenameFromUrl(string $url): ?string
    {
        $p = parse_url($url, PHP_URL_PATH);
        if (!$p) {
            return null;
        }
        return basename($p);
    }
}
