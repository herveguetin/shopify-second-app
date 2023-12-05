<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Index;

interface IndexInterface
{
    public function reindex(): void;

    public function code(): string;
}
