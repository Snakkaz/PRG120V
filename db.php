<?php
/**
 * Database Configuration and Connection
 * 
 * Dette skriptet håndterer tilkobling til MySQL-databasen via phpMyAdmin.
 * 
 * SERVER INFORMASJON (fra phpMyAdmin):
 * - Server: b-studentsql-1.usn.no via TCP/IP
 * - Server type: MariaDB 10.11.14-deb12
 * - Protocol version: 10
 * - Connection: TCP/IP (SSL optional)
 * - Character set: UTF-8 Unicode (utf8mb4)
 * 
 * TILKOBLINGSDETALJER:
 * - Database: stpet1155
 * - Bruker: stpet1155
 * - Passord: d991stpet1155
 * 
 * VIKTIG: Hvis du får "Name does not resolve" feil:
 * 1. Kjør troubleshoot_db.php for å teste alle hostname-alternativer
 * 2. Verifiser at serveren kan nå b-studentsql-1.usn.no
 * 3. Prøv 'localhost' hvis MySQL er på samme server
 */

// Database konfigurasjon
// Riktig hostname fra phpMyAdmin: b-studentsql-1.usn.no
define('DB_HOST', 'b-studentsql-1.usn.no');

// Alternative hostnames hvis den over ikke fungerer:
// define('DB_HOST', 'localhost');      // Hvis MySQL er lokal
// define('DB_HOST', 'mysql');          // Docker container
// define('DB_HOST', '127.0.0.1');     // IP loopback

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
