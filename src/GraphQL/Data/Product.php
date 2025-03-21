<?php declare(strict_types= 1);

namespace App\GraphQL\Data;

use GraphQL\Utils\Utils;

class Product
{
    public string $id;

    public string $name;
    
    public int $inStock;

    public string $description;

    public string $category;

    public string $brand;

    /** @param array<string, mixed> $data */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}