<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace Skafer\Repository;

class RepositoryFactory
{
    public static function create(array $instances, $interface): Repository
    {
        return new Repository($instances, $interface);
    }
}
