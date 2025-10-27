<?php
/**
 * Database Configuration and Connection
 * 
 * Dette skriptet håndterer tilkobling til MySQL-databasen via phpMyAdmin.
 * Databasedetaljer:
 * - Host: mysql.usn.no
 * - Database: stpet1155
 * - Bruker: stpet1155
 * - Passord: d991stpet1155
 */

// Database konfigurasjon
define('DB_HOST', 'mysql.usn.no');
define('DB_USER', 'stpet1155');
define('DB_PASS', 'd991stpet1155');
define('DB_NAME', 'stpet1155');

/**
 * Oppretter og returnerer en database-tilkobling
 * 
 * @return mysqli Database connection object
 * @throws Exception hvis tilkobling feiler
 */
function getDbConnection() {
    // Opprett tilkobling
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Sjekk tilkobling
    if ($conn->connect_error) {
        die("Tilkobling feilet: " . $conn->connect_error);
    }
    
    // Sett tegnsett til UTF-8 for norske tegn
    $conn->set_charset("utf8mb4");
    
    return $conn;
}

/**
 * Lukk database-tilkobling
 * 
 * @param mysqli $conn Database connection object
 */
function closeDbConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}

/**
 * Escape string for SQL injection beskyttelse
 * 
 * @param mysqli $conn Database connection
 * @param string $string String to escape
 * @return string Escaped string
 */
function escapeString($conn, $string) {
    return $conn->real_escape_string($string);
}
?>
