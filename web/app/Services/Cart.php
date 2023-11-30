<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services;

use App\Services\Cart\Decorators\DecoratorInterface;
use App\Services\Cart\Decorators\TotalPriceDecorator;
use Exception;
use StdClass;

class Cart
{
    private StdClass $cart;

    private array $decorators = [
        TotalPriceDecorator::class
    ];

    public function update(array $cart): array
    {
        $this->cart = json_decode(json_encode($cart), false);
        $this->decorate();
        return (array)$this->cart;
    }

    private function decorate(): void
    {
        array_map(function (string $decoratorClassName) {
            /** @var DecoratorInterface $decoratorInstance */
            $decoratorInstance = new $decoratorClassName();
            $this->assertDecoratorInstance($decoratorInstance);
            $this->cart = $decoratorInstance->decorate($this->cart);
        }, $this->decorators);
    }

    private function assertDecoratorInstance($decoratorInstance): void
    {
        if (!$decoratorInstance instanceof DecoratorInterface) {
            throw new Exception(sprintf(
                '%s must implement \App\Services\Cart\Decorators\DecoratorInterface',
                get_class($decoratorInstance)
            ));
        }
    }
}
