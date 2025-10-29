<?php

namespace MyApi\Utils;

class Sanitizer
{
    public static function escapeLike(string $value, string $char = '\\'): string
    {
        return str_replace(
            ['%', '_', $char],
            [$char . '%', $char . '_', $char . $char],
            $value
        );
    }

    public static function cleanString(string $value): string
    {
        return trim(strip_tags($value));
    }

    public static function arrayClean(array $data): array
    {
        foreach ($data as $k => $v) {
            if (is_string($v)) {
                $data[$k] = self::cleanString($v);
            }
        }
        return $data;
    }
}
