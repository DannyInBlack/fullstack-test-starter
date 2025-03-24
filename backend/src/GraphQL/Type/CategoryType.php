<?php declare(strict_types=1);

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class CategoryType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'fields' => [
                'name' => Type::string(),
                '__typename' => Type::string(),
            ],
        ]);
    }
}