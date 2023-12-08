<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use Algolia\AlgoliaSearch\SearchIndex;
use App\Services\Algolia\Framework\Decorator\DecoratorInterface;
use App\Services\Algolia\Framework\Decorator\DecoratorRepository;
use App\Services\Algolia\Framework\Index;
use App\Services\Algolia\Indexers\Queue\Job;
use App\Services\Algolia\Indexers\Queue\JobConfig;
use App\Services\Shopify\Rest;
use Closure;
use Exception;

class IndexerBuilder implements IndexerInterface
{
    private ?string $indexCode;
    private string $apiPath;
    private string $apiObjectsResponseKey;
    private SearchIndex $index;
    private ?array $objects = [];
    private bool $canClear = true;
    private string $decoratorNamespace;
    private ?Closure $objectsRequestClosure;

    public function __construct(
        string $indexCode,
        string $apiPath = '',
        string $apiObjectsResponseKey = '',
        string $decoratorNamespace = ''
    )
    {
        $this->indexCode = $indexCode;
        $this->apiPath = $apiPath;
        $this->apiObjectsResponseKey = $apiObjectsResponseKey;
        $this->decoratorNamespace = $decoratorNamespace;
    }

    public function useRequestClosure(Closure $objectsRequestClosure)
    {
        $this->objectsRequestClosure = $objectsRequestClosure;
    }

    public function reindex(): void
    {
        $this->truncate();
        $this->requestObjects();
        $this->decorateObjects();
        $this->indexObjects();
    }

    public function truncate(): void
    {
        if ($this->canClear()) {
            $this->index()->clearObjects();
        }
    }

    public function canClear(?bool $flag = null): bool
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

    public function code(): string
    {
        if (is_null($this->indexCode)) {
            throw new Exception('Please define an index code.');
        }
        return $this->indexCode;
    }

    protected function requestObjects(): void
    {
        $this->objects = $this->objectsRequestClosure
            ? call_user_func($this->objectsRequestClosure)
            : (new Rest($this->apiPath))->objects($this->apiObjectsResponseKey);
    }

    private function decorateObjects()
    {
        if ($this->decoratorNamespace !== '') {
            array_map(function (DecoratorInterface $decorator) {
                $decorator->decorate($this->objects);
            }, DecoratorRepository::all($this->decoratorNamespace, DecoratorInterface::class));
        }
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
