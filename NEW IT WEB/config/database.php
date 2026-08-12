<?php
require_once __DIR__ . '/config.php';

function getPDO(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $host = env('DB_HOST', '127.0.0.1');
    $db   = env('DB_NAME', 'ivantech');
    $user = env('DB_USER', 'root');
    $pass = env('DB_PASS', '');
    $dsn  = "mysql:host={$host};dbname={$db};charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        // Friendly error for development; in production log and show generic message
        http_response_code(500);
        echo "Database connection failed. Check your configuration.";
        exit;
    }
    return $pdo;
}
