<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Cart\Decorators;

use stdClass;

class TotalPriceDecorator implements DecoratorInterface
{

    public function decorate(stdClass $cart): stdClass
    {
        $cart->total_price = 3000;
        return $cart;
    }
}
