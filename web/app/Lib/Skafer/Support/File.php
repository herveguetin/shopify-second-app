<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Lib\Skafer\Support;

use Symfony\Component\Finder\Finder;

class File
{
    private const PATTERN_PHP_FILES = '*.php';

    public static function files(string $dirUri, ?string $pattern = null): array
    {
        $pattern = $pattern ?? self::PATTERN_PHP_FILES;
        $files = [];
        foreach (Finder::create()->files()->name($pattern)->in(Path::get() . $dirUri) as $file) {
            $files[] = $file;
        }
        ksort($files, SORT_NATURAL);
        return $files;
    }
}
