<?php
/**
 * @author Hervé Guétin <www.linkedin.com/in/herveguetin>
 */

use App\Services\Algolia\Index\Collections;
use App\Services\Algolia\Index\Products;

return [
    // Products
    Products::INDEX_CODE => [
        'settings' => [
            'parameters' => [
                'attributesForFaceting' => ['collections']
            ]
        ]
    ],
    // Collections
    Collections::INDEX_CODE => [],
];
