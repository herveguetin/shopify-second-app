<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

use Skafer\Repository\Repository;
use Skafer\Repository\RepositoryFactory;
use Skafer\Repository\RepositoryInterface;

class IndexerRepository implements RepositoryInterface
{
    private const INDEXERS = [
        Products::class,
        Collections::class,
    ];
    private static ?Repository $repository = null;

    public static function get($key, $default = null): IndexerInterface
    {
        return current(array_filter(static::all(), function (IndexerInterface $index) use ($key) {
            return $index->code() === $key;
        }));
    }

    public static function all(): array
    {
        return static::repository()->all();
    }

    private static function repository(): Repository
    {
        if (is_null(static::$repository)) {
            static::$repository = RepositoryFactory::create(self::INDEXERS, IndexerInterface::class);
        }
        return static::$repository;
    }
}
