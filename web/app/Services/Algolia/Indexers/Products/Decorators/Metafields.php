<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers\Products\Decorators;

use App\Services\Algolia\App\Cache;
use App\Services\Shopify\Resource\Metafield;
use Shopify\Rest\Base;
use Skafer\Decorator\DecoratorAbstract;

class Metafields extends DecoratorAbstract
{
    protected function decorateObject(mixed &$product)
    {
        $cacheKey = 'shopify_metafields_product_' . $product['id'];
        if (!Cache::has($cacheKey)) {
            $metafields = array_map(function (Base $metafield) {
                $metafieldArr = $metafield->toArray();
                if ($metaobject = Metafield::metaobject($metafieldArr)) {
                    $metafieldArr['value'] = $metaobject;
                }
                return $metafieldArr;
            }, Metafield::all($product['id'], 'product'));
            Cache::put($cacheKey, $metafields);
        }
        foreach (Cache::get($cacheKey) as $metafield) {
            $product[sprintf('metafield::%s_%s', $metafield['namespace'], $metafield['key'])] = $metafield;
        }
    }
}
