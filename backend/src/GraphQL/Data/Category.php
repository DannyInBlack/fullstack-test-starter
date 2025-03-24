<?php declare(strict_types= 1);

namespace App\GraphQL\Data;

use GraphQL\Utils\Utils;

class Category
{
    public string $name;

    /** @param array<string, mixed> $data */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}