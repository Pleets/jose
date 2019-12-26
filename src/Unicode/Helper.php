<?php
namespace Pleets\Jose\Unicode;

class Helper
{
    public static function octetArrayToString(array $octets): string
    {
        $string = '';
        foreach ($octets as $octet)
        {
            $string .= chr($octet);
        }
        return $string;
    }

    public static function stringToOctetArray(string $string): array
    {
        $octets = [];
        $len = mb_strlen($string);
        for ($i = 0; $i <= $len - 1; $i++)
        {
            $octets[] = ord(substr($string, $i, 1));
        }
        return $octets;
    }

    public static function toBase64Url(string $base64): string
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], $base64);
    }

    public static function toBase64(string $urlString): string
    {
        return str_replace(['-', '_'], ['+', '/'], $urlString);
    }

    public static function addPadding(string $base64String): string
    {
        if (strlen($base64String) % 4 !== 0) {
            return self::addPadding($base64String . '=');
        }

        return $base64String;
    }
}
