<?php
/**
 * Database Configuration and Connection
 * 
 * Dette skriptet håndterer tilkobling til MySQL-databasen via phpMyAdmin.
 * 
 * VIKTIG: Database hostname varierer avhengig av server-oppsett.
 * Hvis du får "Name does not resolve" feil, prøv en av disse:
 * 
 * 1. 'mysql-ait.usn.no'  - Standard for USN AIT-studenter
 * 2. 'localhost'         - Hvis MySQL kjører på samme server som PHP
 * 3. 'mysql'             - Docker container navn (hvis i Docker)
 * 4. '127.0.0.1'         - IP loopback adresse
 * 
 * Databasedetaljer:
 * - Database: stpet1155
 * - Bruker: stpet1155
 * - Passord: d991stpet1155
 */

// Database konfigurasjon
// Prøv disse hostname-alternativene hvis tilkobling feiler:
// Option 1: Standard USN hostname for AIT-studenter
define('DB_HOST', 'mysql-ait.usn.no');

// Option 2: Hvis MySQL er på samme server (uncomment for å bruke)
// define('DB_HOST', 'localhost');

// Option 3: Docker container navn (uncomment for å bruke)
// define('DB_HOST', 'mysql');

// Option 4: IP adresse (uncomment for å bruke)
// define('DB_HOST', '127.0.0.1');

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
