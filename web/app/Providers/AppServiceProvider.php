<?php

namespace App\Providers;

use App\Lib\DbSessionStorage;
use App\Lib\Handlers\AppUninstalled;
use App\Lib\Handlers\Privacy\CustomersDataRequest;
use App\Lib\Handlers\Privacy\CustomersRedact;
use App\Lib\Handlers\Privacy\ShopRedact;
use App\Services\Algolia;
use App\Services\Algolia\Framework\Client as AlgoliaClient;
use App\Services\Cart;
use App\Services\Shopify\Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Shopify\ApiVersion;
use Shopify\Context;
use Shopify\Exception\MissingArgumentException;
use Shopify\Webhooks\Registry;
use Shopify\Webhooks\Topics;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('cart', function () {
            return new Cart();
        });
        $this->app->bind('algolia', function () {
            return new Algolia();
        });
        $this->app->singleton(AlgoliaClient::class, function () {
            return new AlgoliaClient();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws MissingArgumentException
     */
    public function boot()
    {
        Config::load();
        $this->bootShopify();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     * @throws MissingArgumentException
     */
    public function bootShopify()
    {
        $host = str_replace('https://', '', env('HOST', 'not_defined'));

        $customDomain = env('SHOP_CUSTOM_DOMAIN', null);
        Context::initialize(
            env('SHOPIFY_API_KEY', 'not_defined'),
            env('SHOPIFY_API_SECRET', 'not_defined'),
            env('SCOPES', 'not_defined'),
            $host,
            new DbSessionStorage(),
            ApiVersion::LATEST,
            true,
            false,
            null,
            '',
            null,
            (array)$customDomain,
        );

        URL::forceRootUrl("https://$host");
        URL::forceScheme('https');

        Registry::addHandler(Topics::APP_UNINSTALLED, new AppUninstalled());

        /*
         * This sets up the mandatory privacy webhooks. You’ll need to fill in the endpoint to be used by your app in
         * the “Privacy webhooks” section in the “App setup” tab, and customize the code when you store customer data
         * in the handlers being registered below.
         *
         * More details can be found on shopify.dev:
         * https://shopify.dev/docs/apps/webhooks/configuration/mandatory-webhooks
         *
         * Note that you'll only receive these webhooks if your app has the relevant scopes as detailed in the docs.
         */
        Registry::addHandler('CUSTOMERS_DATA_REQUEST', new CustomersDataRequest());
        Registry::addHandler('CUSTOMERS_REDACT', new CustomersRedact());
        Registry::addHandler('SHOP_REDACT', new ShopRedact());
    }
}
