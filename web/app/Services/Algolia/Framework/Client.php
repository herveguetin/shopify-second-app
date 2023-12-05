<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Framework;

use Algolia\AlgoliaSearch\SearchClient;

class Client
{
    private ?SearchClient $algoliaClient = null;

    public function open(): SearchClient
    {
        if (is_null($this->algoliaClient)) {
            $this->algoliaClient = SearchClient::create(env('ALGOLIA_APP_ID'), env('ALGOLIA_API_KEY'));
        }
        return $this->algoliaClient;
    }
}
