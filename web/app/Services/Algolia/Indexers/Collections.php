<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use App\Services\Shopify\Rest;

class Collections extends IndexerAbstract
{
    public const INDEXER_CODE = 'collections';
    protected const API_PATH = 'collects.json';
    protected const API_OBJECTS_RESPONSE_KEY = 'collects';

    protected function requestObjects(): void
    {
        $staticCollections = $this->getStaticCollections();
        $smartCollections = (new Rest('smart_collections'))->objects('smart_collections');
        $collections = array_merge($smartCollections, $staticCollections);
        $this->enrichCollections($collections);
        $this->objects = $collections;
    }

    private function getStaticCollections(): array
    {
        parent::requestObjects();
        foreach ($this->objects as &$collection) {
            $collection['id'] = $collection['collection_id'];
        }
        return $this->objects;
    }

    private function enrichCollections(?array &$collections)
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
