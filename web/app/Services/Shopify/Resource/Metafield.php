<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Shopify\Resource;

use App\Services\Shopify\Rest\Session;
use Shopify\Rest\Admin2023_10\Metafield as ShopifyMetafield;

use function str_contains;

class Metafield
{
    public static function all(int $id, string $resource): array
    {
        $session = Session::offline();
        return ShopifyMetafield::all($session, [], [
            'metafield' => ['owner_id' => $id, 'owner_resource' => $resource]
        ]);
    }

    public static function metaobject(array $metafield): ?array
    {
        return (str_contains($metafield['value'], 'Metaobject')) ? Metaobject::find($metafield['value']) : null;
    }
}
