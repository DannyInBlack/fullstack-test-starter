<?php

namespace Vendor\View;

use Vendor\Controllers\ProductController;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        ProductController::getProducts();
        break;
    case 'POST':
        $testData = [
            [
                'sku' => 'BOOKTEST',
                'name' => 'HAAIRY POTTER',
                'price' => '49.99',
                'type' => 'Book',
                'attributes' => [
                    'weight' => '2'
                ],
            ],
        ];

        foreach ($testData as $data) {
            ProductController::saveProduct($data);
        }

        break;
    case 'DELETE':
        $testData = [
            [
                'sku' => 'BOOKTEST',
                'name' => 'HAAIRY POTTER',
                'price' => '49.99',
                'type' => 'Book',
                'attributes' => [
                    'weight' => '2'
                ],
            ],
        ];

        foreach ($testData as $data) {
            ProductController::deleteProduct($data);
        }
        
        break;
}
