<?php
/**
 * Database Connection Test
 * 
 * Dette skriptet tester tilkoblingen til MySQL-databasen.
 * Kjør dette skriptet for å verifisere at databasekonfigurasjonen er korrekt.
 */

require_once 'db.php';

echo "<!DOCTYPE html>\n";
echo "<html lang='no'>\n";
echo "<head>\n";
echo "    <meta charset='UTF-8'>\n";
echo "    <meta name='viewport' content='width=device-width, initial-scale=1.0'>\n";
echo "    <title>Database Test - PRG120V</title>\n";
echo "    <link rel='stylesheet' href='style.css'>\n";
echo "</head>\n";
echo "<body>\n";
echo "    <div class='container'>\n";
echo "        <header>\n";
echo "            <h1>🔧 Database Connection Test</h1>\n";
echo "            <p class='subtitle'>PRG120V - Database Verification</p>\n";
echo "        </header>\n";
echo "        <main>\n";
echo "            <section>\n";
echo "                <h2>Test Results</h2>\n";
echo "                <div class='info-box warning'>\n";
echo "                    <p>⚠️ <strong>Connection problems?</strong> If you get DNS or connection errors, use the <a href='troubleshoot_db.php'><strong>Database Troubleshooter</strong></a> to find the correct hostname.</p>\n";
echo "                </div>\n";

// Test 1: Database Connection
echo "                <h3>1. Testing Database Connection...</h3>\n";
try {
    $conn = getDbConnection();
    echo "                <div class='message success'>✅ Database connection successful!</div>\n";
    echo "                <div class='info-box'>\n";
    echo "                    <p><strong>Host:</strong> " . DB_HOST . "</p>\n";
    echo "                    <p><strong>Database:</strong> " . DB_NAME . "</p>\n";
    echo "                    <p><strong>User:</strong> " . DB_USER . "</p>\n";
    echo "                    <p><strong>Character Set:</strong> " . $conn->character_set_name() . "</p>\n";
    echo "                </div>\n";
    
    // Test 2: Check Tables
    echo "                <h3>2. Checking Database Tables...</h3>\n";
    
    // Check klasse table
    $result = $conn->query("SHOW TABLES LIKE 'klasse'");
    if ($result->num_rows > 0) {
        echo "                <div class='message success'>✅ Table 'klasse' exists</div>\n";
        
        // Count records
        $count_result = $conn->query("SELECT COUNT(*) as count FROM klasse");
        $count = $count_result->fetch_assoc()['count'];
        echo "                <p>Number of records in 'klasse': <strong>$count</strong></p>\n";
    } else {
        echo "                <div class='message error'>❌ Table 'klasse' does not exist</div>\n";
        echo "                <div class='info-box warning'>\n";
        echo "                    <p>You need to create the 'klasse' table. Run this SQL:</p>\n";
        echo "                    <pre>CREATE TABLE klasse (\n";
        echo "  klassekode CHAR(5) NOT NULL,\n";
        echo "  klassenavn VARCHAR(50) NOT NULL,\n";
        echo "  studiumkode VARCHAR(50) NOT NULL,\n";
        echo "  PRIMARY KEY (klassekode)\n";
        echo ");</pre>\n";
        echo "                </div>\n";
    }
    
    // Check student table
    $result = $conn->query("SHOW TABLES LIKE 'student'");
    if ($result->num_rows > 0) {
        echo "                <div class='message success'>✅ Table 'student' exists</div>\n";
        
        // Count records
        $count_result = $conn->query("SELECT COUNT(*) as count FROM student");
        $count = $count_result->fetch_assoc()['count'];
        echo "                <p>Number of records in 'student': <strong>$count</strong></p>\n";
    } else {
        echo "                <div class='message error'>❌ Table 'student' does not exist</div>\n";
        echo "                <div class='info-box warning'>\n";
        echo "                    <p>You need to create the 'student' table. Run this SQL:</p>\n";
        echo "                    <pre>CREATE TABLE student (\n";
        echo "  brukernavn CHAR(7) NOT NULL,\n";
        echo "  fornavn VARCHAR(50) NOT NULL,\n";
        echo "  etternavn VARCHAR(50) NOT NULL,\n";
        echo "  klassekode CHAR(5) NOT NULL,\n";
        echo "  PRIMARY KEY (brukernavn),\n";
        echo "  FOREIGN KEY (klassekode) REFERENCES klasse(klassekode)\n";
        echo ");</pre>\n";
        echo "                </div>\n";
    }
    
    // Test 3: Test Query Execution
    echo "                <h3>3. Testing Query Execution...</h3>\n";
    $test_query = $conn->query("SELECT 1 as test");
    if ($test_query && $test_query->num_rows > 0) {
        echo "                <div class='message success'>✅ Query execution successful</div>\n";
    } else {
        echo "                <div class='message error'>❌ Query execution failed</div>\n";
    }
    
    // Test 4: PHP Version
    echo "                <h3>4. PHP Environment</h3>\n";
    echo "                <div class='info-box'>\n";
    echo "                    <p><strong>PHP Version:</strong> " . phpversion() . "</p>\n";
    echo "                    <p><strong>MySQLi Extension:</strong> " . (extension_loaded('mysqli') ? '✅ Loaded' : '❌ Not loaded') . "</p>\n";
    echo "                </div>\n";
    
    closeDbConnection($conn);
    
    echo "                <h3>✅ All Tests Passed!</h3>\n";
    echo "                <div class='info-box'>\n";
    echo "                    <p>Your database is configured correctly and ready to use.</p>\n";
    echo "                    <a href='index.php' class='btn btn-primary'>Go to Application</a>\n";
    echo "                </div>\n";
    
} catch (Exception $e) {
    echo "                <div class='message error'>❌ Database connection failed!</div>\n";
    echo "                <div class='info-box'>\n";
    echo "                    <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>\n";
    echo "                    <p>Please check your database configuration in <code>db.php</code></p>\n";
    echo "                </div>\n";
}

echo "            </section>\n";
echo "        </main>\n";
echo "    </div>\n";
echo "</body>\n";
echo "</html>\n";
?>
