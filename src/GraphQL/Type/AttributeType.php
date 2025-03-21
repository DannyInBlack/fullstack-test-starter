<?php declare(strict_types=1);

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'fields' => [
                'id' => Type::string(),
                'displayValue' => Type::string(),
                'value' => Type::string(),
                '__typename' => Type::string(),
            ],
        ]);
    }
}