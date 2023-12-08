<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\App;

use App\Lib\Skafer\Support\File;
use Illuminate\Config\Repository;

class Config
{
    private const DIR_URI = 'Algolia/config';
    private static ?Repository $config = null;

    public static function get(?string $path = null, $default = null): mixed
    {
        if (is_null(static::$config)) {
            static::$config = static::repository();
        }
        return static::$config->get($path, $default);
    }

    protected static function repository(): Repository
    {
        $repository = new Repository([]);
        foreach (File::files(self::DIR_URI) as $file) {
            $key = strtolower(basename($file->getRealPath(), '.php'));
            $path = $file->getRealPath();
            $repository->set($key, require $path);
        }
        return $repository;
    }
}
