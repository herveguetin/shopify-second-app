<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Index;

use Skafer\Repository\Repository;
use Skafer\Repository\RepositoryFactory;
use Skafer\Repository\RepositoryInterface;

class IndexRepository implements RepositoryInterface
{
    private const INDICES = [
        Products::class,
        Collections::class,
    ];
    private static ?Repository $repository = null;

    private static function repository(): Repository
    {
        if (is_null(static::$repository)) {
            static::$repository = RepositoryFactory::create(self::INDICES, IndexInterface::class);
        }
        return static::$repository;
    }

    public static function get($key): IndexInterface
    {
        return current(array_filter(static::all(), function (IndexInterface $index) use ($key) {
            return $index->code() === $key;
        }));
    }

    public static function all(): array
    {
        return static::repository()->all();
    }
}
