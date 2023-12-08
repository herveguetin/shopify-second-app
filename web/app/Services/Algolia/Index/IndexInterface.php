<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Index;

use Algolia\AlgoliaSearch\SearchIndex;

interface IndexInterface
{
    public function code(): string;

    public function setup(): void;

    public function reindex(): void;

    public function sample(): array;

    public function truncate(): void;

    public function algolia(): SearchIndex;
}
