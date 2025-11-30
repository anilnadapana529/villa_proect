<?php

namespace App\Core;

use PDO;
use PDOException;
use mysqli;

class Database {
    private static $instance = null;
    private static $mysqliInstance = null;
    private $pdo;

    private function __construct() {
        $host = "localhost";
        $db   = "u200283558_villa";
        $user = "u200283558_villa";
        $pass = "Ansi@2023";

        try {
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$db;charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

        } catch (PDOException $e) {
            die("DB Connection Failed: " . $e->getMessage());
        }
    }

    public static function instance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }

    public static function connect() {
        if (self::$mysqliInstance === null) {
            $host = "localhost";
            $db   = "u200283558_villa";
            $user = "u200283558_villa";
            $pass = "Ansi@2023";

            self::$mysqliInstance = new mysqli($host, $user, $pass, $db);

            if (self::$mysqliInstance->connect_error) {
                die("MySQLi Connection Failed: " . self::$mysqliInstance->connect_error);
            }

            self::$mysqliInstance->set_charset("utf8mb4");
        }
        return self::$mysqliInstance;
    }
}
