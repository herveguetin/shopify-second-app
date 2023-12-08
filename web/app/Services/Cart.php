<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services;

use Skafer\Decorator\DecoratorInterface;
use Skafer\Decorator\DecoratorRepository;
use StdClass;

class Cart
{
    private StdClass $cart;

    public function update(array $cart): array
    {
        $this->cart = json_decode(json_encode($cart), false);
        $this->decorate();
        return (array)$this->cart;
    }

    private function decorate(): void
    {
        array_map(function (DecoratorInterface $decorator) {
            $objects = [$this->cart];
            $decorator->decorate($objects);
        }, DecoratorRepository::all('Cart\Decorators'));
    }
}
