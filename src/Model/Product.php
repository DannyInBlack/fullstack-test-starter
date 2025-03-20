<?php

declare(strict_types=1);

namespace Vendor\Models;

use Exception;
use mysqli;
use mysqli_result;
use Vendor\Models\DB;
use Vendor\Models\Book;
use Vendor\Models\DVD;

abstract class Product extends DB
{
    protected string $sku;
    protected string $name;
    protected string $price;
    protected string $type;

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public static function getAllProducts(): array| bool
    {
        $conn = self::getConn();

        $query = "
        SELECT 
            items.sku,
            items.name,
            items.price,
            items.type,
            item_attributes.attribute_name,
            item_attributes.attribute_value
        FROM items
        LEFT JOIN item_attributes
        ON items.sku = item_attributes.sku
        ";

        $result = $conn->execute_query($query)->fetch_all(MYSQLI_ASSOC);

        $products = [];
        $attributes = [];
        foreach ($result as $row) {
            $sku = $row['sku'];
            if (!isset($attributes[$sku])) {
                $attributes[$sku] = [];
            }
            $attributes[$sku][$row['attribute_name']] = $row['attribute_value'];
        }

        $used = [];

        foreach ($result as $row) {
            if (isset($used[$row['sku']])) {
                continue;
            }
            $productType = 'Vendor\\Models\\' . $row['type'];
            $product = new $productType($row['sku'], $row['name'], $row['price'], $row['type'], $attributes[$row['sku']]);
            $products[] = $product;
            $used[$row['sku']] = 1;
        }


        return $products;
    }

    public function deleteProduct(): bool
    {
        $conn = DB::getConn();

        $query = "
        DELETE FROM item_attributes
        WHERE sku=?
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $this->sku);

        try{
            $result = $stmt->execute();
        } catch (Exception) {
            return false;
        }

        $query = "
        DELETE FROM items 
        WHERE sku=?
        ";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $this->sku);
    
        try{
            $result = $stmt->execute();
        } catch (Exception) {
            return false;
        }

        return true;
    }

    abstract public function saveProduct(): bool;

    abstract public function toArray(): array;
}
