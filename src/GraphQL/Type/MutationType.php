<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use App\GraphQL\Data\DataSource;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;

class MutationType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => [
                'addOrder' => [
                    'type' => Type::boolean(), // Returns true if the order is successfully inserted
                    'args' => [
                        'items' => new NonNull(new ListOfType(new NonNull(TypeRegistry::type(OrderItemInputType::class)))), // Reference the standalone input type
                    ],
                    'resolve' => function ($root, $args): bool {
                        $items = $args['items'];

                        // Calculate the total price
                        $total = 0.0;
                        foreach ($items as $item) {
                            $productId = $item['productID'];
                            $quantity = $item['quantity'];
                            $price = DataSource::getProductPrice($productId);
                            if ($price === null) {
                                throw new \InvalidArgumentException("Invalid product ID: $productId");
                            }
                            $total += $price * $quantity;
                        }

                        // Insert the order into the database
                        $orderId = DataSource::insertOrder($total);

                        // Insert products and attributes into the order_product and order_product_attribute tables
                        foreach ($items as $item) {
                            $productId = $item['productID'];
                            $quantity = $item['quantity'];
                            $attributeIDs = $item['attributeIDs'] ?? [];

                            DataSource::insertOrderProduct($orderId, $productId, $quantity);

                            foreach ($attributeIDs as $attributeId) {
                                DataSource::insertOrderProductAttribute($orderId, $productId, $attributeId);
                            }
                        }

                        return true; // Success
                    },
                ],
            ],
        ]);
    }
}