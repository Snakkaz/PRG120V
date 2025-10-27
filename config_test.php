<?php
// Test database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'skole_db');
define('DB_USER', 'debian-sys-maint');
define('DB_PASS', 'IXQHtvZHLjyf54Mk');

// Create database connection
function getConnection() {
    try {
        $conn = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $conn;
    } catch (PDOException $e) {
        die("Databaseforbindelse feilet: " . $e->getMessage());
    }
}
?>
