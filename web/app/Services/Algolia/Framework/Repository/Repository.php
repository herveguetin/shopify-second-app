<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Framework\Repository;

use App\Services\Algolia\Framework\Exceptions\WrongInterfaceException;

class Repository implements RepositoryInterface
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
        $this->instances = array_map(function ($className) {
            $instance = new $className();
            if (!is_subclass_of($instance, $this->interface)) {
                throw new WrongInterfaceException($instance, $this->interface);
            }
            return $instance;
        }, $this->instanceNames);
    }

    public function all(): array
    {
        return $this->instances;
    }
}
