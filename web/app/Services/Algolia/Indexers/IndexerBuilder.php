<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use App\Services\Algolia\App\Cache;
use App\Services\Algolia\Index\IndexInterface;
use App\Services\Algolia\Index\IndexRepository;
use App\Services\Algolia\Indexers\Queue\Job;
use App\Services\Algolia\Indexers\Queue\JobConfig;
use App\Services\Shopify\Rest;
use Closure;
use Exception;
use Skafer\Decorator\DecoratorInterface;
use Skafer\Decorator\DecoratorRepository;

class IndexerBuilder implements IndexerInterface
{
    protected const INDEX_CODE = '';
    protected const API_PATH = '';
    protected const API_OBJECTS_RESPONSE_KEY = '';
    protected const DECORATOR_NAMESPACE = '';

    private ?array $objects = [];
    private ?Closure $objectsRequestClosure = null;

    protected function useRequestClosure(Closure $objectsRequestClosure)
    {
        $this->objectsRequestClosure = $objectsRequestClosure;
    }

    public function reindex(): void
    {
        $this->index()->algolia()->clearObjects();
        $this->requestObjects();
        $this->decorateObjects();
        $this->indexObjects();
    }

    public function index(): IndexInterface
    {
        return IndexRepository::get($this->code());
    }

    public function code(): string
    {
        if (static::INDEX_CODE === '') {
            throw new Exception('Please define an index code.');
        }
        return static::INDEX_CODE;
    }

    private function requestObjects(): void
    {
        if (!Cache::has($this->cacheKey())) {
            $this->objects = $this->objectsRequestClosure
                ? call_user_func($this->objectsRequestClosure)
                : (new Rest(static::API_PATH))->objects(static::API_OBJECTS_RESPONSE_KEY);
            Cache::put($this->cacheKey(), $this->objects);
        }
        $this->objects = Cache::get($this->cacheKey());
    }

    private function decorateObjects()
    {
        if (static::DECORATOR_NAMESPACE !== '') {
            array_map(function (DecoratorInterface $decorator) {
                $decorator->decorate($this->objects);
            }, DecoratorRepository::all(static::DECORATOR_NAMESPACE));
        }
    }

    private function indexObjects(): void
    {
        foreach ($this->objects as $object) {
            Job::dispatch(JobConfig::encode([
                'indexer_code' => $this->code(),
                'objects' => [$object],
            ]));
        }
    }

    private function cacheKey(): string
    {
        return $this->code() . '_objects';
    }
}
