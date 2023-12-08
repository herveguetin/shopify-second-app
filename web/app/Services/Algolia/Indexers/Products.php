<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

class Products extends IndexerBuilder
{
    protected const INDEX_CODE = 'products';
    protected const API_PATH = 'products.json';
    protected const API_OBJECTS_RESPONSE_KEY = 'products';
    protected const DECORATOR_NAMESPACE = 'Algolia\Indexers\Products\Decorators';
}

