<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

class Products extends IndexerAbstract
{
    public const INDEXER_CODE = 'products';
    protected const API_PATH = 'products.json';
    protected const API_OBJECTS_RESPONSE_KEY = 'products';
}
