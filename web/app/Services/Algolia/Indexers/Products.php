<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use App\Services\Shopify\Resource\Metafield;
use App\Services\Shopify\Rest\Collections as ShopifyCollections;
use Shopify\Rest\Base;

class Products extends IndexerAbstract
{
    public const INDEX_CODE = 'products';
    protected const API_PATH = 'products.json';
    protected const API_OBJECTS_RESPONSE_KEY = 'products';
    private array $collections = [];

    protected function requestObjects(): void
    {
        parent::requestObjects();
        $this->collections = ShopifyCollections::all();
        $this->addCollections();
        $this->addMetafields();
    }

    private function addCollections(): void
    {
        $newObjects = array_map(function ($product) {
            return $this->addCollectionsOfProduct($product);
        }, $this->objects);
        $this->objects = $newObjects;
    }

    private function addCollectionsOfProduct(mixed $product): array
    {
        $collections = array_filter($this->collections, function ($collection) use ($product) {
            return in_array($product['id'], $collection['products']);
        });
        $product['collections'] = array_values(array_map(function ($collection) {
            return $collection['id'];
        }, $collections));
        return $product;
    }

    private function addMetafields(): void
    {
        $newObjects = array_map(function ($product) {
            return $this->addMetafieldsOfProduct($product);
        }, $this->objects);
        $this->objects = $newObjects;
    }

    private function addMetafieldsOfProduct(mixed $product): array
    {
        $product['metafields'] = array_map(function (Base $metafield) {
            $metafieldArr = $metafield->toArray();
            if ($metaobject = Metafield::metaobject($metafieldArr)) {
                $metafieldArr['value'] = $metaobject;
            }
            return $metafieldArr;
        }, Metafield::all($product['id'], 'product'));
        return $product;
    }
}

