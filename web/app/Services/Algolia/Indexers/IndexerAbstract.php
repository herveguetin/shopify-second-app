<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use Algolia\AlgoliaSearch\SearchIndex;
use App\Services\Algolia\Index;
use Exception;
use Shopify\Auth\Session;
use Shopify\Clients\Rest;
use Shopify\Clients\RestResponse;
use Shopify\Utils;

abstract class IndexerAbstract implements IndexerInterface
{
    public const INDEXER_CODE = '';
    protected const API_PATH = '';
    protected const API_OBJECTS_RESPONSE_KEY = '';
    protected const PAGE_SIZE = 5;

    protected SearchIndex $index;
    private ?Session $session = null;
    private bool $canClear = true;
    protected ?array $objects = [];

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
        $page = $this->requestPage(path: static::API_PATH, pageUrl: $pageUrl);
        $responseBody = $page->getDecodedBody();
        $newObjects = static::API_OBJECTS_RESPONSE_KEY !== '' ? $responseBody[static::API_OBJECTS_RESPONSE_KEY] : [$responseBody];
        $this->objects = array_merge($this->objects, $newObjects);
        if ($page->getPageInfo()->getNextPageUrl() !== null) {
            $this->requestObjects($page->getPageInfo()->getNextPageUrl());
        }
    }

    protected function indexObjects(): void
    {
        $this->index()->saveObjects($this->objects, ['objectIDKey' => 'id'])->wait();
    }

    public function sample(): array
    {
        return $this->index()->browseObjects()->current();
    }

    protected function requestPage(string $path, array $query = [], ?string $pageUrl = null): RestResponse
    {
        $pageInfo = null;
        if (!is_null($pageUrl)) {
            $parts = parse_url($pageUrl);
            parse_str($parts['query'], $query);
            $pageInfo = $query['page_info'];
        }
        /** @var RestResponse $response */
        $response = $this->shopify()->get(
            path: $path,
            query: array_merge($query, ['limit' => static::PAGE_SIZE, 'page_info' => $pageInfo])
        );
        return $response;
    }

    protected function shopify(): Rest
    {
        if (is_null($this->session)) {
            $this->session = Utils::loadOfflineSession(env('SHOPIFY_SHOP'));
        }
        return new Rest($this->session->getShop(), $this->session->getAccessToken());
    }
}
