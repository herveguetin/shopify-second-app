<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use Algolia\AlgoliaSearch\SearchIndex;
use App\Services\Algolia\Index;
use App\Services\Algolia\Indexers\Queue\Job;
use App\Services\Algolia\Indexers\Queue\JobConfig;
use App\Services\Shopify\Rest;
use Exception;
use Shopify\Auth\Session;

abstract class IndexerAbstract implements IndexerInterface
{
    public const INDEXER_CODE = '';
    protected const API_PATH = '';
    protected const API_OBJECTS_RESPONSE_KEY = '';
    protected const PAGE_SIZE = 5;

    protected SearchIndex $index;
    protected ?array $objects = [];
    private ?Session $session = null;
    private bool $canClear = true;

    public function reindex(): void
    {
        $this->truncate();
        $this->requestObjects();
        $this->indexObjects();
    }

    public function truncate(): void
    {
        if ($this->canClear()) {
            $this->index()->clearObjects();
        }
    }

    protected function canClear(?bool $flag = null): bool
    {
        if (!is_null($flag)) {
            $this->canClear = $flag;
        }
        return $this->canClear;
    }

    protected function index(): SearchIndex
    {
        return Index::use($this->code());
    }

    protected function code(): string
    {
        if (static::INDEXER_CODE === '') {
            throw new Exception('Please define an indexer code.');
        }
        return static::INDEXER_CODE;
    }

    protected function requestObjects(?string $pageUrl = null): void
    {
        $this->objects = (new Rest(static::API_PATH))->objects(static::API_OBJECTS_RESPONSE_KEY);
    }

    protected function indexObjects(): void
    {
        Job::dispatch(JobConfig::encode([
            'indexer_code' => $this->code(),
            'objects' => $this->objects,
        ]));
    }

    public function sample(): array
    {
        return $this->index()->browseObjects()->current();
    }
}
