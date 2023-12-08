<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

namespace App\Services\Algolia\Indexers\Products\Decorators;

use App\Services\Algolia\Framework\Decorator\DecoratorAbstract;
use App\Services\Shopify\Resource\Metafield;
use Shopify\Rest\Base;

class Metafields extends DecoratorAbstract
{
    protected function decorateObject(mixed &$product)
    {
        $metafields = array_map(function (Base $metafield) {
            $metafieldArr = $metafield->toArray();
            if ($metaobject = Metafield::metaobject($metafieldArr)) {
                $metafieldArr['value'] = $metaobject;
            }
            return $metafieldArr;
        }, Metafield::all($product['id'], 'product'));
        foreach ($metafields as $metafield) {
            $product[sprintf('metafield::%s_%s', $metafield['namespace'], $metafield['key'])] = $metafield;
        }
    }
}
