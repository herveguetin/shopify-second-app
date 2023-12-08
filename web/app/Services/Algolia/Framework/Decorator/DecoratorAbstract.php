<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Framework\Decorator;

abstract class DecoratorAbstract implements DecoratorInterface
{
    public function decorate(array &$objects): void
    {
        foreach ($objects as &$object) {
            $this->decorateObject($object);
        }
    }

    abstract protected function decorateObject(mixed &$object);
}
