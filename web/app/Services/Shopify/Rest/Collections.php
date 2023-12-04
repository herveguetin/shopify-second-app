<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Shopify\Rest;

use App\Services\Shopify\Rest;

class Collections
{
    public static function all(): array
    {
        return static::requestObjects();
    }

    protected static function requestObjects(): array
    {
        $staticCollections = static::getStaticCollections();
        $smartCollections = static::getObjects('smart_collections', 'smart_collections');
        $collections = array_merge($smartCollections, $staticCollections);
        static::enrichCollections($collections);
        return $collections;
    }

    private static function getStaticCollections(): array
    {
        $objects = static::getObjects('collects.json', 'collects');
        foreach ($objects as &$collection) {
            $collection['id'] = $collection['collection_id'];
        }
        return $objects;
    }

    private static function getObjects(string $apiPath, string $apiResponseKey = ''): array
    {
        return (new Rest($apiPath))->objects($apiResponseKey);
    }

    private static function enrichCollections(?array &$collections): void
    {
        foreach ($collections as &$collection) {
            $products = (new Rest(sprintf('collections/%s/products.json', $collection['id'])))
                ->objects('products');
            $collection['products'] = array_map(function ($product) {
                return $product['id'];
            }, $products);
        }
    }
}
