<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\App;

use Illuminate\Config\Repository;
use Symfony\Component\Finder\Finder;

class Config
{
    public static function get(?string $path = null, $default = null): mixed
    {
        $config = new static();
        return $config->repository()->get($path, $default);
    }

    protected function repository(): Repository
    {
        $files = $this->getConfigurationFiles();
        $repository = new Repository([]);
        foreach ($files as $key => $path) {
            $repository->set($key, require $path);
        }
        return $repository;
    }

    protected function getConfigurationFiles(): array
    {
        $files = [];
        $configPath = app()->basePath('/app/Services/Algolia/config');
        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $files[basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }
        ksort($files, SORT_NATURAL);
        return $files;
    }
}
