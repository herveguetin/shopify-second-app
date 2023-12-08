<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Framework\Decorator;

interface DecoratorInterface
{
    public function decorate(array &$objects): void;
}
