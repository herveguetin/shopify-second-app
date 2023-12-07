<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Shopify\Rest;

use Shopify\Auth\Session as ShopifySession;
use Shopify\Utils;

class Session
{
    public static function offline(): ShopifySession
    {
        return Utils::loadOfflineSession(env('SHOPIFY_SHOP'));
    }
}
