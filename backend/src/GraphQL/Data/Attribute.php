<?php declare(strict_types=1);

namespace App\GraphQL\Data;

use GraphQL\Utils\Utils;

class Attribute
{
    public string $id;
    
    public string $displayValue;
    
    public string $value;

    /** @param array<string, mixed> $data */
    public function __construct(array $data)
    {
        Utils::assign($this, $data);
    }
}