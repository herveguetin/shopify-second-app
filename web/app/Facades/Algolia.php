<?php

/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\Algolia reindex
 * @method static \App\Services\Algolia reindexProducts
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