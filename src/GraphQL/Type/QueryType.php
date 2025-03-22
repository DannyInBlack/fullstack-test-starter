<?php declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use App\GraphQL\Data\DataSource;
use App\GraphQL\Type\CategoryType;
use App\GraphQL\Type\ProductType;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class QueryType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'fields' => [
                'categories' => [
                    'type' => new ListOfType(TypeRegistry::type(CategoryType::class)),
                    'description' => 'Returns a list of categories',
                    'resolve' => static fn ($rootValue, array $args): ?array => DataSource::getCategories(),
                ],
                'products' => [
                    'type' => new ListOfType(TypeRegistry::type(ProductType::class)),
                    'description' => 'Returns a list of products, optionally filtered by category',
                    'args' => [
                        'category' => [
                            'type' => Type::string(), // Optional argument for filtering by category
                            'description' => 'The category to filter products by',
                        ],
                    ],
                    'resolve' => function ($rootValue, array $args):?array {
                        $category = $args['category'] ?? null;
                        return DataSource::getProducts($category);
                    },
                ],
            ],
        ]);
    }
}