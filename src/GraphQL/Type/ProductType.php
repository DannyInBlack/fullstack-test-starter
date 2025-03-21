<?php declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use App\GraphQL\Data\DataSource;
use App\GraphQL\Data\Product;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'fields' => [
                'id' => Type::string(),
                'name' => Type::string(),
                'inStock' => Type::boolean(),
                'gallery' => [
                    'type' => new ListOfType(Type::string()),
                    'resolve' => static fn (Product $product): ?array => DataSource::findGallery($product->id)
                ],
                'description' => Type::string(),
                'category' => [
                    'type' => Type::string(),
                    'resolve' => static fn (Product $product): ?string => DataSource::findCategory($product->category)->name
                ],
                'attributes' => [
                    'type' => new ListOfType(TypeRegistry::type(AttributeSetType::class)),
                    'resolve' => static fn (Product $product): ?array => DataSource::findAttributeSet($product->id)
                ],
                'prices' => [
                    'type' => new ListOfType(TypeRegistry::type(PriceType::class)),
                    'resolve' => static fn (Product $product): ?array => DataSource::findPrices($product->id)
                ],
                'brand' => Type::string(),
                '__typename' => Type::string(),
            ]
        ]);
    }
}