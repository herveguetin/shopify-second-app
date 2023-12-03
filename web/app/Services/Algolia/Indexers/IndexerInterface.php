<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

interface IndexerInterface
{
    public function reindex(): void;

    public function sample(): array;

    public function truncate(): void;
}
