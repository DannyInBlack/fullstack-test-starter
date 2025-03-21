<?php

declare(strict_types=1);

namespace App\GraphQL\Data;

use mysqli;

abstract class DataSource
{

    private static ?mysqli $conn = null;

    private static string $DB_HOST = "localhost";

    private static string $DB_USERNAME = "root";

    private static string $DB_PASSWORD = "";

    private static string $DB_NAME = "test";

    private static function getConn(): mysqli
    {
        if (self::$conn === null) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            self::$conn = new mysqli(
                self::$DB_HOST,
                self::$DB_USERNAME,
                self::$DB_PASSWORD,
                self::$DB_NAME
            );
        }

        return self::$conn;
    }


    public static function findCategory(string $name): ?Category
    {
        $sql = "SELECT * from test.categories WHERE categories.name=?";
        $conn = self::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $name);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new Category($row);
        }

        return null;
    }

    /** @return array<Category> */
    public static function getCategories(): ?array
    {
        $sql = "SELECT * from test.categories";
        $conn = self::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $categories = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $categories[] = new Category($row);
            }
        }

        return $categories;
    }

    /** @return array<Product> */
    public static function getProducts(): ?array
    {
        $sql = "SELECT * from test.products";
        $conn = self::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $products = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                // var_dump($row);
                $products[] = new Product($row);
            }
        }


        return $products;
    }

    /** @return array<string> */
    public static function findGallery(string $productId): ?array
    {
        $sql = "SELECT image_url FROM test.galleries WHERE product_id = ?";
        $conn = self::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        $gallery = [];

        while ($row = $result->fetch_assoc()) {
            $gallery[] = $row['image_url'];
        }

        return $gallery;
    }

    /** @return array<Price> */
    public static function findPrices(string $productId): ?array
    {
        $sql = "SELECT * FROM test.prices WHERE product_id = ?";
        $conn = self::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        $prices = [];

        while ($row = $result->fetch_assoc()) {
            $prices[] = new Price($row);
        }

        return $prices;
    }

    public static function findCurrency(int $priceId): ?Currency
    {
        $sql = "SELECT * FROM test.currencies WHERE price_id = ?";
        $conn = self::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $priceId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new Currency($row);
        }

        return null;
    }

    /** @return array<AttributeSet> */
    public static function findAttributeSet(string $productId): ?array
    {
        $sql = "SELECT * FROM test.attribute_sets WHERE product_id = ?";
        $conn = self::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        $attributeSet = [];

        while ($row = $result->fetch_assoc()) {
            $attributeSet[] = new AttributeSet($row);
        }

        return $attributeSet;
    }

    /** @return array<Attribute> */
    public static function findAttributes(int $attributeSetId): ?array
    {
        $sql = "SELECT a.* 
            FROM test.attributes_attribute_sets aas 
            INNER JOIN test.attributes a ON aas.attribute_id = a.id 
            WHERE aas.attribute_set_id = ?";
        $conn = self::getConn();
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $attributeSetId);
        $stmt->execute();
        $result = $stmt->get_result();

        $attributes = [];

        while ($row = $result->fetch_assoc()) {
            $attributes[] = new Attribute($row);
        }

        return $attributes;
    }
}
