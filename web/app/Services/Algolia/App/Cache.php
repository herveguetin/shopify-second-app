<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\App;

class Cache extends \Illuminate\Support\Facades\Cache
{
    public static function __callStatic($method, $args)
    {
        $cache = \Illuminate\Support\Facades\Cache::store('array');
        return $cache->$method(...$args);
    }
}
