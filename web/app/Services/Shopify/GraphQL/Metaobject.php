<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Shopify\GraphQL;

class Metaobject
{
    public static function ofId(int $id): array
    {
        return ['meta' => 'test'];
    }
}
