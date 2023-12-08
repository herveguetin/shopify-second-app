<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Skafer\Repository;

use Skafer\Exceptions\WrongInterfaceException;

class Repository
{
    private array $instanceNames = [];
    private mixed $interface = null;
    private array $instances = [];

    public function __construct(
        array $instanceNames,
        mixed $interface,
    )
    {
        $this->instanceNames = $instanceNames;
        $this->interface = $interface;
        $this->makeInstances();
    }

    private function makeInstances()
    {
        array_map(function ($className) {
            $instance = new $className();
            if (!is_subclass_of($instance, $this->interface)) {
                throw new WrongInterfaceException($instance, $this->interface);
            }
            $this->instances[$className] = $instance;
        }, $this->instanceNames);
    }

    public function all(): array
    {
        return $this->instances;
    }

    public function get($key): mixed
    {
        return $this->instances[$key];
    }
}
