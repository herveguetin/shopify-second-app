<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Shopify;

use Shopify\Auth\Session;
use Shopify\Clients\Rest as ShopifyRest;
use Shopify\Clients\RestResponse;
use Shopify\Utils;

class Rest
{
    private const PAGE_SIZE = 5;
    private string $apiPath;
    private string $apiObjectsResponseKey = '';
    private ?Session $session = null;
    private array $objects = [];

    public function __construct(
        string $apiPath = '',
    )
    {
        $this->apiPath = $apiPath;
    }


    public function objects(string $apiObjectsResponseKey = ''): array
    {
        $this->apiObjectsResponseKey = $apiObjectsResponseKey;
        $this->requestObjects();
        return $this->objects;
    }

    protected function requestObjects(?string $pageUrl = null): void
    {
        $page = $this->requestPage(path: $this->apiPath, pageUrl: $pageUrl);
        $responseBody = $page->getDecodedBody();
        $newObjects = $this->apiObjectsResponseKey !== '' ? $responseBody[$this->apiObjectsResponseKey] : [$responseBody];
        $this->objects = array_merge($this->objects, $newObjects);
        if ($page->getPageInfo()->getNextPageUrl() !== null) {
            $this->requestObjects($page->getPageInfo()->getNextPageUrl());
        }
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

    protected function shopify(): ShopifyRest
    {
        if (is_null($this->session)) {
            $this->session = Utils::loadOfflineSession(env('SHOPIFY_SHOP'));
        }
        return new ShopifyRest($this->session->getShop(), $this->session->getAccessToken());
    }
}
