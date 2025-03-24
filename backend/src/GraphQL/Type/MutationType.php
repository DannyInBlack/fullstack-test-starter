<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\Data\DataSource;
use App\GraphQL\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

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
                        'items' => Type::nonNull(Type::listOf(Type::nonNull(TypeRegistry::type(OrderItemInputType::class)))),
                    ],
                    'resolve' => function ($root, $args): ?bool {
                        $items = $args['items'];

                        // Log the received items into a file
                        $logFile = '/opt/lampp/htdocs/file.log';
                        $logData = sprintf("[%s] Received items: %s\n", date('Y-m-d H:i:s'), json_encode($items, JSON_PRETTY_PRINT));
                        file_put_contents($logFile, $logData, FILE_APPEND);
                        
                        $total = 0.0;
                        foreach ($items as $item) {
                            $productId = $item['productId'];
                            $quantity = $item['quantity'];

                            $product = DataSource::getProductById($productId);
                            if ($product === null) {
                                throw new \InvalidArgumentException("Invalid product ID: $productId");
                            }
                            
                            if (!$product->inStock) {
                                throw new \InvalidArgumentException("Product is not in stock: $productId");
                            }
                            
                            $price = DataSource::getProductPrice($productId);
                            if ($price === null) {
                                throw new \InvalidArgumentException("No price found for product: $productId");
                            }
                            
                            $total += $price * $quantity;
                        }
                        // Insert the order into the database
                        $orderId = DataSource::insertOrder($total);

                        // Insert order items and their attributes
                        foreach ($items as $item) {
                            $productId = $item['productId'];
                            $quantity = $item['quantity'];
                            $attributeIDs = $item['attributeIds'] ?? [];

                            $orderItemId = DataSource::insertOrderItem($orderId, $productId, $quantity);

                            foreach ($attributeIDs as $attributeId) {
                                // echo $productId .' '. $attributeId[0];
                                $attribute_set_id = DataSource::getAttributeSetById($productId, $attributeId[0]);
                                if (!$attribute_set_id) {
                                    throw new \InvalidArgumentException('Invalid attribute ID');
                                }
                                DataSource::insertItemAttribute($orderItemId, $attribute_set_id, $attributeId[1]);
                            }
                        }

                        return true;  
                    },
                ],
            ],
        ]);
    }
}
