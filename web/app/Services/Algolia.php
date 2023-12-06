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
        $this->command('reindex', $indices);
    }

    private function command(string $method, array $indices = []): void
    {
        array_map(function (IndexInterface $index) use ($method, $indices) {
            if (empty($indices) || in_array($index->code(), $indices)) {
                $index->$method();
            }
        }, IndexRepository::all());
    }

    public function setup(array $indices = []): void
    {
        $this->command('setup', $indices);
    }
}
