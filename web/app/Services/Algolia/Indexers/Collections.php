<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use App\Services\Shopify\Rest;

class Collections extends IndexerAbstract
{
    public const INDEX_CODE = 'collections';

    protected function requestObjects(): void
    {
        $this->objects = Rest\Collections::all();
    }
}
