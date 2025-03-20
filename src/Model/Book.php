<?php

namespace Vendor\Models;

use Vendor\Models\Product;
use Exception;

class Book extends Product
{
    private string $weight;

    public function __construct(string $sku, string $name, string $price, string $type, array $attributes)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
        $this->weight = $attributes['weight'] ?? '';
    }

    public function setWeight(float $weight): void
    {
        $this->weight = $weight;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function saveProduct(): bool
    {
        $conn = self::getConn();

        $query = "
        INSERT INTO items (sku, name, price, type)
        VALUES (?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $this->sku, $this->name, $this->price, $this->type);
        try {
            $result = $stmt->execute();
        } catch (Exception) {
            return 0;
        }

        if ($result) {
            $query = "
            INSERT INTO item_attributes (sku, attribute_name, attribute_value)
            VALUES (?, 'weight', ?)
            ";

            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $this->sku, $this->weight);
            $result = $stmt->execute();
        }

        return $result;
    }

    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->price,
            'type' => $this->type,
            'weight' => $this->weight
        ];
    }
}
