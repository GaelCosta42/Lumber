<?php
namespace App\Core;

use PDO;

class DB
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../../config/database.php';

            $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']};charset=utf8mb4";

            self::$instance = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
        }

        return self::$instance;
    }
}
