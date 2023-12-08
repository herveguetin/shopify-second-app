<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers\Products\Decorators;

use App\Services\Algolia\Framework\Decorator\DecoratorAbstract;
use App\Services\Shopify\Rest\Collections as ShopifyCollections;

class Collections extends DecoratorAbstract
{
    protected function decorateObject(mixed &$product)
    {
        $collections = array_filter(ShopifyCollections::all(), function ($collection) use ($product) {
            return in_array($product['id'], $collection['products']);
        });
        $product['collections'] = array_values(array_map(function ($collection) {
            return $collection['id'];
        }, $collections));
    }
}
