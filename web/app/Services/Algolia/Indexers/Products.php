<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

/**
 * @method code()
 * @method reindex()
 * @method truncate()
 * @method sample()
 */
class Products implements IndexerInterface
{
    private const INDEX_CODE = 'products';
    private const API_PATH = 'products.json';
    private const API_OBJECTS_RESPONSE_KEY = 'products';
    private const DECORATOR_NAMESPACE = 'Algolia\Indexers\Products\Decorators';
    private IndexerBuilder $indexer;

    public function __construct()
    {
        $this->indexer = new IndexerBuilder(
            self::INDEX_CODE,
            self::API_PATH,
            self::API_OBJECTS_RESPONSE_KEY,
            self::DECORATOR_NAMESPACE
        );
    }

    public function __call(string $name, array $arguments)
    {
        return $this->indexer->$name($arguments);
    }
}

