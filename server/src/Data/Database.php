<?php 

namespace App\Data;

use PDO;
use PDOException;

class Database {
    private static $pdo = null;
    private static $maxPoolSize = 5;

    public static function getConnection() {
        $host = DB_HOST;
        $db   = DB_NAME;
        $user = DB_USER;
        $pass = DB_PASS;

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_PERSISTENT => true
        ];

        // Lazy fill: add one connection if pool not full
        if (count(self::$pool) < self::$maxPoolSize) {
            try {
                $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, $options);
                self::$pool[] = $pdo;
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        // Return a random connection from the pool
        $key = array_rand(self::$pool);
        return self::$pool[$key];
    }
}

?>