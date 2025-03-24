<?php declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use App\GraphQL\Data\DataSource;
use App\GraphQL\Type\CategoryType;
use App\GraphQL\Type\ProductType;
use App\GraphQL\Data\Product;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
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
                        'category' => [ // Filter by category
                            'type' => Type::string(),
                            'description' => 'The category to filter products by',
                        ],
                    ],
                    'resolve' => static fn ($rootValue, array $args):?array => DataSource::getProductsByCategory($args['category'] ?? null),
                ],
                'product' => [
                    'type' => TypeRegistry::type(ProductType::class),
                    'description' => 'Returns a product by id',
                    'args' => [
                        'id' => [ // Get specific product by id
                            'type' =>  new NonNull(Type::id()), 
                            'description' => 'The ID of the product to retrieve',
                        ],
                    ],
                    'resolve' => static fn ($rootValue, array $args):?Product => DataSource::getProductById($args['id']),
                ],
            ],
        ]);
    }
}