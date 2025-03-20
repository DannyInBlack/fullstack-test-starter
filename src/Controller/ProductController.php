<?php

namespace Vendor\Controllers;

use Vendor\Models\Product;
use Vendor\Models\DVD;
use Vendor\Models\Book;
use Vendor\Models\Furniture;
use Exception;

class ProductController
{
    public static function getProducts(): void
    {
        $response = [];
        $response['data'] = [];
        $data = 0;
        try {
            $data = Product::getAllProducts();
            $rows = count($data);
            $response['status'] = "Ok, found $rows rows";

            // Convert the objects to arrays and add them to the response
            foreach ($data as $product) {
                $response['data'][] = $product->toArray();
            }
        } catch (Exception $e) {
            $response['status'] = "Server Error";
        }

        // Encode the response as JSON and print it
        echo json_encode($response);
    }

    public static function saveProduct(array $data): void
    {
        $productType = 'Vendor\\Models\\' . $data['type'];

        $product = new $productType(
            $data['sku'],
            $data['name'],
            $data['price'],
            $data['type'],
            $data['attributes']
        );

        if ($product->saveProduct()) {
            $response['status'] = "Data saved successfully!";
        } else {
            $response['status'] = "Error: duplicate SKU";
        }

        echo json_encode($response);
    }

    public static function deleteProduct(array $data)
    {

        $productType = 'Vendor\\Models\\' . $data['type'];

        $product = new $productType(
            $data['sku'],
            $data['name'],
            $data['price'],
            $data['type'],
            $data['attributes']
        );

        if ($product->deleteProduct()) {
            $response['status'][] = "Deleted product #" . $data['sku'] . " successfully!";
        } else {
            $response['status'][] = "Error: could not delete product #" . $data['sku'];
        }

        echo json_encode($response);
    }
}
