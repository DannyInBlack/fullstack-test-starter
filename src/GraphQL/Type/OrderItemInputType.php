<?php

declare(strict_types=1);

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class OrderItemInputType extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderItemInput',
            'fields' => [
                'productID' => Type::nonNull(Type::string()), // Product ID
                'quantity' => Type::nonNull(Type::int()),    // Quantity
                'attributeIDs' => Type::listOf(Type::string()), // List of attribute IDs
            ],
        ]);
    }
}