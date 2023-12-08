<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Framework\Decorator;

use App\Services\Algolia\Framework\Class\Loader;
use App\Services\Algolia\Framework\Repository\RepositoryFactory;

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
