<?php

/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \App\Services\Cart update(array $cart)
 *
 * @see \App\Services\Cart
 */
class Cart extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'cart';
    }

}
