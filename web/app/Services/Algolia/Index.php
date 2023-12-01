<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia;

use Algolia\AlgoliaSearch\SearchIndex;

class Index
{
    public static function use(string $indexCode): SearchIndex
    {
        /** @var Client $client */
        $client = app()->make(Client::class);
        return $client->open()->initIndex(env('ALGOLIA_INDEX_PREFIX') . $indexCode);
    }
}
