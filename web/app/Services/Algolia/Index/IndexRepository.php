<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Index;

use App\Services\Algolia\Framework\Repository\RepositoryFactory;

class IndexRepository
{
    private const INDICES = [
        Products::class,
        Collections::class,
    ];

    public static function all(): array
    {
        $repository = RepositoryFactory::create(self::INDICES, IndexInterface::class);
        return $repository->all();
    }
}
