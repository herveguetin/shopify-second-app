<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Framework\Repository;

interface RepositoryInterface
{
    public function all(): array;
}
