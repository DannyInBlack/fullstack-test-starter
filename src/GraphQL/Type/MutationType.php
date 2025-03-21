<?php declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\Type\ProductType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class MutationType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Mutation',
            'fields' => [
                'addProduct' => [
                    'type' => new ProductType(),
                    'args' => [
                        'id' => Type::nonNull(Type::string()),
                        'name' => Type::nonNull(Type::string()),
                        'inStock' => Type::nonNull(Type::boolean()),
                        'category' => Type::nonNull(Type::string()),
                        'brand' => Type::nonNull(Type::string()),
                    ],
                    'resolve' => function ($root, $args) {
                        $filePath = __DIR__ . '/../Migration/junior-web-dev.json';
                        $data = json_decode(file_get_contents($filePath), true);

                        $newProduct = [
                            'id' => $args['id'],
                            'name' => $args['name'],
                            'inStock' => $args['inStock'],
                            'category' => $args['category'],
                            'brand' => $args['brand'],
                            'gallery' => [],
                            'description' => '',
                            'attributes' => [],
                            'prices' => [],
                            '__typename' => 'Product',
                        ];

                        $data['data']['products'][] = $newProduct;
                        file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));

                        return $newProduct;
                    },
                ],
            ],
        ]);
    }
}