<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Skafer\Class;

use App\Lib\Skafer\Support\File;

class Loader
{

    private static array $loadedClasses = [];

    public static function classNames(string $namespace): array
    {
        if (!in_array($namespace, array_keys(static::$loadedClasses))) {
            static::include($namespace);
            static::$loadedClasses[$namespace] = static::namespaceClasses($namespace);
        }
        return static::$loadedClasses[$namespace];
    }

    private static function include(string $namespace): void
    {
        foreach (File::files(str_replace('\\', '/', $namespace)) as $file) {
            include_once $file->getRealPath();
        }
    }

    public static function namespaceClasses($namespace): array
    {
        return array_filter(
            get_declared_classes(),
            function ($className) use ($namespace) {
                return (str_contains($className, $namespace));
            }
        );
    }
}
