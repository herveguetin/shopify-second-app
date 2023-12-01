<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

class Products extends IndexerAbstract
{
    protected string $indexerCode = 'products';

    protected function run(): void
    {
        $record = ["objectID" => 1, "name" => "new rercord again"];
        $this->index()->saveObject($record)->wait();
    }
}
