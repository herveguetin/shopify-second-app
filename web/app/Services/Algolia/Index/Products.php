<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Index;

/**
 * @method code()
 * @method setup()
 * @method reindex()
 */
class Products implements IndexInterface
{
    public const INDEX_CODE = 'products';
    private IndexBuilder $index;

    public function __construct()
    {
        $this->index = new IndexBuilder(self::INDEX_CODE);
    }

    public function __call(string $name, array $arguments)
    {
        return $this->index->$name($arguments);
    }
}
