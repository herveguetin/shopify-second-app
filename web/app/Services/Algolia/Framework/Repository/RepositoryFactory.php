<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Framework\Repository;

class RepositoryFactory
{
    public static function create(array $instances, $interface): RepositoryInterface
    {
        return new Repository($instances, $interface);
    }
}
