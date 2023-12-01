<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services;

use Algolia\AlgoliaSearch\SearchClient;
use Algolia\AlgoliaSearch\SearchIndex;

class Algolia
{
    private const INDEX_PRODUCTS = 'products';
    private ?SearchClient $algoliaClient = null;

    public function reindex(): void
    {
        $this->reindexProducts();
        $results = $this->index(self::INDEX_PRODUCTS)->search("test_record");
        var_dump($results["hits"][0]);
    }

    public function reindexProducts(): void
    {
        $index = $this->index(self::INDEX_PRODUCTS);
        $record = ["objectID" => 1, "name" => "test_record"];
        $index->saveObject($record)->wait();
    }

    private function index(string $indexCode): SearchIndex
    {
        return $this->client()->initIndex(env('ALGOLIA_INDEX_PREFIX') . $indexCode);
    }

    private function client(): SearchClient
    {
        if (is_null($this->algoliaClient)) {
            $this->algoliaClient = SearchClient::create(env('ALGOLIA_APP_ID'), env('ALGOLIA_API_KEY'));
        }
        return $this->algoliaClient;
    }
}
