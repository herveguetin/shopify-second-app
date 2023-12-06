<?php

/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\Algolia reindex(array $indexers = [])
 * @method static \App\Services\Algolia reindexProducts
 * @method static \App\Services\Algolia setup
 *
 * @see \App\Services\Algolia
 */
class Algolia extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'algolia';
    }
}
