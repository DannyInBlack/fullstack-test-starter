<?php declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use App\GraphQL\Data\AttributeSet;
use App\GraphQL\Data\DataSource;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ListOfType;

class AttributeSetType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'fields' => [
                'id' => Type::string(),
                'name' => Type::string(),
                'type' => Type::string(),
                'items' => [
                    'type' => new ListOfType(TypeRegistry::type(AttributeType::class)),
                    'resolve' => static fn (AttributeSet $attributeSet): ?array => DataSource::findAttributes($attributeSet->attribute_set_id),
                ],
                '__typename' => Type::string(),
            ],
        ]);
    }
}