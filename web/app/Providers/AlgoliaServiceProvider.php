<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Providers;

use App\Services\Algolia;
use App\Services\Algolia\App\Client as AlgoliaClient;
use App\Services\Shopify\Config;
use Illuminate\Support\ServiceProvider;

class AlgoliaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('algolia', function () {
            return new Algolia();
        });
        $this->app->singleton(AlgoliaClient::class, function () {
            return new AlgoliaClient();
        });
    }

    public function boot()
    {
        Config::load();
    }
}
