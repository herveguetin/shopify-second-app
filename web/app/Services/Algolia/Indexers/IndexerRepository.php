<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use App\Services\Algolia\Framework\Repository\RepositoryFactory;

class IndexerRepository
{
    private const INDEXERS = [
        Products::class,
        Collections::class,
    ];

    public static function all(): array
    {
        $repository = RepositoryFactory::create(self::INDEXERS, IndexerInterface::class);
        return $repository->all();
    }
}
