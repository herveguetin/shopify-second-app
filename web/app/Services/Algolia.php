<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services;

use App\Services\Algolia\Indexers\Collections;
use App\Services\Algolia\Indexers\IndexerInterface;
use App\Services\Algolia\Indexers\Products;

class Algolia
{
    private const INDEXERS = [
        Products::INDEXER_CODE => Products::class,
        Collections::INDEXER_CODE => Collections::class,
    ];


    public function reindex(array $indexers = []): void
    {
        array_map(function (IndexerInterface $indexer) {
            $indexer->reindex();
        }, $this->indexers($indexers));
    }

    /**
     * @return IndexerInterface[]
     */
    private function indexers(array $indexers = []): array
    {
        $usedIndexers = empty($indexers) ? self::INDEXERS : array_map(function ($indexerCode) {
            return self::INDEXERS[$indexerCode];
        }, $indexers);
        return array_map(function (string $indexerClassName) {
            return new $indexerClassName();
        }, $usedIndexers);
    }
}
