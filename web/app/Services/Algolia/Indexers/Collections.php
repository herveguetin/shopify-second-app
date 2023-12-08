<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use App\Services\Shopify\Rest;

/**
 * @method code()
 * @method reindex()
 * @method truncate()
 * @method sample()
 */
class Collections implements IndexerInterface
{
    public const INDEX_CODE = 'collections';

    private IndexerBuilder $indexer;

    public function __construct()
    {
        $this->indexer = new IndexerBuilder(self::INDEX_CODE);
        $this->indexer->useRequestClosure(function () {
            Rest\Collections::all();
        });
    }

    public function __call(string $name, array $arguments)
    {
        return $this->indexer->$name($arguments);
    }
}
