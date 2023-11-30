<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Cart\Decorators;

use stdClass;

interface DecoratorInterface
{
    public function decorate(stdClass $cart): stdClass;
}
