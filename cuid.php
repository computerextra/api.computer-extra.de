<?php

declare(strict_types=1);

class CUIDGenerator
{
    private static $counter = 0;
    private static $lastTimestamp = 0;
    private static $base36Chars = '0123456789abcdefghijklmnopqrstuvwxyz';

    private static function encodeBase36($value)
    {
        $result = "";
        while ($value > 0) {
            $result = self::$base36Chars[$value % 36] . $result;
            $value = (int) ($value / 36);
        }
        return str_pad($result, 8, "=", STR_PAD_LEFT);
    }

    private static function getMachineFingerprint()
    {
        $hostname = gethostname();
        $hash = md5($hostname);
        return substr($hash, 0, 4);
    }

    private static function getRandomString($length)
    {
        $result = "";
        for ($i = 0; $i < $length; $i++) {
            $result .= self::$base36Chars[random_int(0, 35)];
        }
        return $result;
    }

    public static function gnerateCUID()
    {
        $timestamp = intval(microtime(true) * 1000);

        if ($timestamp == self::$lastTimestamp) {
            self::$counter++;
        } else {
            self::$lastTimestamp = $timestamp;
            self::$counter = 0;
        }

        $timestampBase36 = self::encodeBase36($timestamp);
        $counterBase36 = self::encodeBase36(self::$counter);
        $fingerprint = self::getMachineFingerprint();
        $randomString = self::getRandomString(4);

        return "c$timestampBase36$counterBase36$fingerprint$randomString";
    }
}

