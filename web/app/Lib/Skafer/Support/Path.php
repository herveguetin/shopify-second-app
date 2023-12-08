<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Lib\Skafer\Support;

class Path
{
    private static string $basePath = '';

    public static function set(string $basePath): void
    {
        static::$basePath = $basePath;
    }

    public static function get(): string
    {
        return static::$basePath;
    }
}
