<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Providers;

use App\Services\Algolia;
use App\Services\Algolia\App\Client as AlgoliaClient;
use App\Services\Cart;
use App\Services\Shopify\Config;
use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('cart', function () {
            return new Cart();
        });
    }
}
