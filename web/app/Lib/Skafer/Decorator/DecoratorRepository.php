<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Skafer\Decorator;

use Skafer\Repository\RepositoryFactory;
use Skafer\Class\Loader;

class DecoratorRepository
{
    /**
     * @return DecoratorInterface[]
     */
    public static function all(string $namespace, string $interface): array
    {
        $repository = RepositoryFactory::create(Loader::classNames($namespace), $interface);
        return $repository->all();
    }
}
