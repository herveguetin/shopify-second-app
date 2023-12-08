<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Providers;

use App\Lib\Skafer\Support\Path;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Finder\Finder;

class SkaferServiceProvider extends ServiceProvider
{
    private const LIB_PATH = 'app/Lib/Skafer';

    public function register()
    {
        foreach (Finder::create()->files()->name('*.php')->in(app()->basePath(self::LIB_PATH)) as $file) {
            include_once $file->getRealPath();
        }
    }

    public function boot()
    {
        Path::set(app()->basePath('app/Services/'));
    }
}
