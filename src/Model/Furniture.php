<?php

namespace Vendor\Models;

use Vendor\Models\Product;
use Exception;

class Furniture extends Product
{
    private string $height;
    private string $width;
    private string $length;

    public function __construct(string $sku, string $name, string $price, string $type, array $attributes)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
        $this->height = $attributes['height'] ?? '';
        $this->width = $attributes['width'] ?? '';
        $this->length = $attributes['length'] ?? '';
    }

    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->price,
            'type' => $this->type,
            'height' => $this->height,
            'width' => $this->width,
            'length' => $this->length,
        ];
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
        $result = $stmt->execute();

        if ($result) {
            $query = "
            INSERT INTO item_attributes (sku, attribute_name, attribute_value)
            VALUES (?, 'height', ?), (?, 'width', ?), (?, 'length', ?)
            ";

            $stmt = $conn->prepare($query);
            $stmt->bind_param('ssssss', $this->sku, $this->height, $this->sku, $this->width, $this->sku, $this->length);
            $result = $stmt->execute();
        }

        return $result;
    }
}
