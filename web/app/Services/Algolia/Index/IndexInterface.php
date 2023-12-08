<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Index;

interface IndexInterface
{
    public function code(): string;
    public function setup(): void;
    public function reindex(): void;
}
