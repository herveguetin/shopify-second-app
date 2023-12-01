<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use Algolia\AlgoliaSearch\SearchIndex;
use App\Services\Algolia\Index;
use Exception;

abstract class IndexerAbstract implements IndexerInterface
{

    protected ?string $indexerCode = null;
    protected SearchIndex $index;

    public function reindex(): void
    {
        $this->run();
    }

    private function code(): string
    {
        if (is_null($this->indexerCode)) {
            throw new Exception('Please define an indexer code.');
        }
        return $this->indexerCode;
    }

    protected function index(): SearchIndex
    {
        return Index::use($this->code());
    }

    abstract protected function run(): void;

    public function sample(): array
    {
        return $this->index()->browseObjects()->current();
    }
}
