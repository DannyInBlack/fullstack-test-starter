<?php declare(strict_types= 1);

namespace App\GraphQL\Data;

use GraphQL\Utils\Utils;

class AttributeSet
{
    public int $attribute_set_id;

    public string $id;
    
    public string $product_id;

    public string $name;

    public string $type;

    /** @param array<string, mixed> $data */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}