<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use Algolia\AlgoliaSearch\SearchIndex;
use App\Services\Algolia\Framework\Index;
use App\Services\Algolia\Indexers\Queue\Job;
use App\Services\Algolia\Indexers\Queue\JobConfig;
use App\Services\Shopify\Rest;
use Exception;

abstract class IndexerAbstract implements IndexerInterface
{
    public const INDEX_CODE = '';
    protected const API_PATH = '';
    protected const API_OBJECTS_RESPONSE_KEY = '';

    protected SearchIndex $index;
    protected ?array $objects = [];
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
        if (static::INDEX_CODE === '') {
            throw new Exception('Please define an index code.');
        }
        return static::INDEX_CODE;
    }

    protected function requestObjects(): void
    {
        $this->objects = (new Rest(static::API_PATH))->objects(static::API_OBJECTS_RESPONSE_KEY);
    }

    protected function indexObjects(): void
    {
        foreach ($this->objects as $object) {
            Job::dispatch(JobConfig::encode([
                'indexer_code' => $this->code(),
                'objects' => [$object],
            ]));
        }
    }

    public function sample(): array
    {
        return $this->index()->browseObjects()->current();
    }
}
