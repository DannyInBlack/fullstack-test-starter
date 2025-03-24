<?php declare(strict_types= 1);

namespace App\GraphQL\Data;

use GraphQL\Utils\Utils;

class Price
{
    public int $id;
    
    public string $product_id;

    public string $amount;

    /** @param array<string, mixed> $data */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}