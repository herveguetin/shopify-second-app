<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Index;

use Algolia\AlgoliaSearch\SearchIndex;
use App\Services\Algolia\App\Client;
use App\Services\Algolia\Indexers\IndexerInterface;
use App\Services\Algolia\Indexers\IndexerRepository;
use App\Services\Algolia\Settings\Setup;
use Exception;

class IndexBuilder implements IndexInterface
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
            return $indexer->code() === $this->code();
        });
    }

    public function code(): string
    {
        if (static::INDEX_CODE === '') {
            throw new Exception('Please define an index code.');
        }
        return static::INDEX_CODE;
    }

    public function setup(): void
    {
        Setup::push($this);
    }

    public function sample(): array
    {
        return $this->algolia()->browseObjects()->current();
    }

    public function algolia(): SearchIndex
    {
        /** @var Client $client */
        $client = app()->make(Client::class);
        return $client->open()->initIndex(env('ALGOLIA_INDEX_PREFIX') . $this->code());
    }

    public function truncate(): void
    {
        $this->algolia()->clearObjects();
    }
}
