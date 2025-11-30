<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $host = "localhost";
        $db   = "u200283558_villa";   // your database
        $user = "u200283558_villa";   // your db username
        $pass = "Ansi@2023";          // your db password

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
}
