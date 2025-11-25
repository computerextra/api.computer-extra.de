<?php

declare(strict_types=1);

function checkApiKey(?string $key): bool
{
    if (!$key || !isset($API_KEY)) {
        return false;
    }

    return hash_equals($API_KEY, $key);
}
