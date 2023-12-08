<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Skafer\Class;

use Symfony\Component\Finder\Finder;

class Loader
{
    private const BASE_PATH = '/app/Services/';

    public static function classNames(string $namespace): array
    {
        static::bindNamespace($namespace);
        return array_filter(get_declared_classes(), function ($className) use ($namespace) {
            return (str_contains($className, $namespace));
        });
    }

    public static function bindNamespace(string $namespace): void
    {
        $containerKey = strtolower(str_replace('\\', '_', $namespace));
        if (!app()->has($containerKey)) {
            $files = static::loadFiles($namespace);
            foreach ($files as $file) {
                include $file;
            }
            app()->bind($containerKey);
        }
    }

    private static function loadFiles(string $namespace): array
    {
        $files = [];
        $configPath = app()->basePath(self::BASE_PATH . str_replace('\\', '/', $namespace));
        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $files[strtolower(basename($file->getRealPath(), '.php'))] = $file->getRealPath();
        }
        ksort($files, SORT_NATURAL);
        return $files;
    }
}
