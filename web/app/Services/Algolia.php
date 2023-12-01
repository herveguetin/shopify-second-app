<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services;

use App\Services\Algolia\Indexers\IndexerInterface;
use App\Services\Algolia\Indexers\Products;

class Algolia
{
    private const INDEXERS = [
        Products::class
    ];


    public function reindex(): void
    {
        array_map(function (IndexerInterface $indexer) {
            $indexer->reindex();
            var_dump($indexer->sample());
        }, $this->indexers());
    }

    /**
     * @return IndexerInterface[]
     */
    private function indexers(): array
    {
        return array_map(function (string $indexerClassName) {
            return new $indexerClassName();
        }, self::INDEXERS);
    }
}
