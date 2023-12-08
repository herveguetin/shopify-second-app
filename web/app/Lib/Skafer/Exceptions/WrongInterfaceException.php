<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Skafer\Exceptions;

use JetBrains\PhpStorm\Pure;

class WrongInterfaceException extends \Exception
{
    #[Pure] public function __construct(
        \stdClass $class,
        string $interface
    )
    {
        $message = sprintf('%s must implement %.', $class::class, $interface);
        parent::__construct($message);
    }

}
