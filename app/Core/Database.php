<?php
namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $config = $this->loadEnv();
        $driver = strtolower($config['DB_CONNECTION'] ?? 'sqlite');

        try {
            if ($driver === 'mysql') {
                $host = $config['DB_HOST'] ?? '127.0.0.1';
                $port = $config['DB_PORT'] ?? '3306';
                $database = $config['DB_DATABASE'] ?? '';
                $username = $config['DB_USERNAME'] ?? 'root';
                $password = $config['DB_PASSWORD'] ?? '';

                $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
                $this->connection = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
                ]);
            } else {
                $dbPath = __DIR__ . '/../../database/database.sqlite';
                $this->connection = new PDO("sqlite:$dbPath");
                $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }

    private function loadEnv(): array {
        $path = __DIR__ . '/../../.env';
        if (!file_exists($path)) {
            return [];
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $config = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || $line[0] === '#' || $line[0] === ';') {
                continue;
            }
            if (strpos($line, '=') === false) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if (preg_match('/^"(.*)"$/', $value, $matches) || preg_match("/^'(.*)'$/", $value, $matches)) {
                $value = $matches[1];
            }

            $config[$key] = $value;
        }

        return $config;
    }
}
