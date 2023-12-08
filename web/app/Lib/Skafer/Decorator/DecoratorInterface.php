<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Skafer\Decorator;

interface DecoratorInterface
{
    public function decorate(array &$objects): void;
}
