<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Index;

use App\Services\Algolia\Indexers\IndexerInterface;
use App\Services\Algolia\Indexers\IndexerRepository;
use App\Services\Algolia\Settings\Setup;
use Exception;

class IndexBuilder implements IndexInterface
{
    private ?string $indexCode;

    public function __construct(
        string $indexCode
    )
    {
        $this->indexCode = $indexCode;
    }

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
            return $indexer->code() === $this->code();
        });
    }

    public function code(): string
    {
        if (is_null($this->indexCode)) {
            throw new Exception('Please define an index code.');
        }
        return $this->indexCode;
    }

    public function setup(): void
    {
        Setup::push($this);
    }
}
