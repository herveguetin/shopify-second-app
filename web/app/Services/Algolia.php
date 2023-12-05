<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services;

use App\Services\Algolia\Index\IndexInterface;
use App\Services\Algolia\Index\IndexRepository;

class Algolia
{
    public function reindex(array $indices = []): void
    {
        array_map(function (IndexInterface $index) use ($indices) {
            if (empty($indices) || in_array($index->code(), $indices)) {
                $index->reindex();
            }
        }, IndexRepository::all());
    }
}
