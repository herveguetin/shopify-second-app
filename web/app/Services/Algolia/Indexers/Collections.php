<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use App\Services\Shopify\Rest\Collections as ShopifyCollections;

class Collections extends IndexerBuilder
{
    public const INDEX_CODE = 'collections';

    public function __construct()
    {
        $this->useRequestClosure(function () {
            return ShopifyCollections::all();
        });
    }
}
