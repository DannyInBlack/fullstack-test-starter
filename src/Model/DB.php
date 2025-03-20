<?php

declare(strict_types=1);

namespace Vendor\Models;

use mysqli;

abstract class DB
{
    private static ?mysqli $conn = null;
    private static string $DB_HOST = "localhost";
    private static string $DB_USERNAME = "root";
    private static string $DB_PASSWORD = "";
    private static string $DB_NAME = "test";

    protected static function getConn(): mysqli
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
}
