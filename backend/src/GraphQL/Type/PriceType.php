<?php declare(strict_types=1);

namespace App\GraphQL\Type;

use App\GraphQL\TypeRegistry;
use App\GraphQL\Data\DataSource;
use App\GraphQL\Data\Price;
use App\GraphQL\Data\Currency;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class PriceType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'fields' => [
                'amount' => Type::float(),
                'currency' => [
                    'type' => TypeRegistry::type(CurrencyType::class),
                    'resolve' => static fn (Price $price): ?Currency => DataSource::findCurrency($price->id),
                ],
                '__typename' => Type::string(),
            ],
        ]);
    }
}