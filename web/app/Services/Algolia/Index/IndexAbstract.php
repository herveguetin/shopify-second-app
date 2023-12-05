<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Index;

use App\Services\Algolia\Indexers\IndexerInterface;
use App\Services\Algolia\Indexers\IndexerRepository;
use Exception;

class IndexAbstract implements IndexInterface
{
    public const INDEX_CODE = '';
    public function reindex(): void
    {
        $this->getIndexers();
        array_map(function (IndexerInterface $indexer) {
            $indexer->reindex();
        }, $this->getIndexers());
    }

    private function getIndexers(): array
    {
        $allIndexers = IndexerRepository::all();
        return array_filter($allIndexers, function (IndexerInterface $indexer) {
            return $indexer::INDEX_CODE === $this->code();
        });
    }

    public function code(): string
    {
        if (static::INDEX_CODE === '') {
            throw new Exception('Please define an index code.');
        }
        return static::INDEX_CODE;
    }
}
