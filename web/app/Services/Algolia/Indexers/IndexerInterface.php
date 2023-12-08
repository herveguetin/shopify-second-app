<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers;

interface IndexerInterface
{
    public function code(): string;
    public function reindex(): void;
}
