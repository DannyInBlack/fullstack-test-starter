<?php declare(strict_types= 1);

namespace App\GraphQL\Data;

use GraphQL\Utils\Utils;

class Currency
{
    public int $price_id;

    public string $label;

    public string $symbol;

    /** @param array<string, mixed> $data */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}