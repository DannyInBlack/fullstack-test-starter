<?php

namespace Vendor\Models;

use Vendor\Models\Product;
use Exception;

class DVD extends Product
{
    private string $size;

    public function __construct(string $sku, string $name, string $price, string $type, array $attributes)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
        $this->size = $attributes['size'] ?? '';
    }

    public function setSize(float $size): void
    {
        $this->size = $size;
    }

    public function getSize(): float
    {
        return $this->size;
    }

    public function saveProduct(): bool {
        $conn = self::getConn();

        $query = "
        INSERT INTO items (sku, name, price, type)
        VALUES (?, ?, ?, ?)
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $this->sku, $this->name, $this->price, $this->type);
        try{
            $result = $stmt->execute();
        } catch (Exception){
            return 0;
        }

        if ($result) {
            $query = "
            INSERT INTO item_attributes (sku, attribute_name, attribute_value)
            VALUES (?, 'size', ?)
            ";

            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $this->sku, $this->size);
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
            'size' => $this->size,
        ];
    }
}
