<?php
/**
 * Database Connection Troubleshooter
 * 
 * Dette skriptet tester forskjellige MySQL hostname-alternativer
 * for å finne den som fungerer på din server.
 */

// Mulige hostnames å teste
$hosts_to_test = [
    'b-studentsql-1.usn.no' => 'Korrekt USN database server (fra phpMyAdmin)',
    'localhost' => 'Lokal MySQL server',
    'mysql' => 'Docker container navn',
    '127.0.0.1' => 'Loopback IP adresse',
    'mysql-ait.usn.no' => 'Alternativ USN hostname',
    'mysql.usn.no' => 'Gammel hostname (deprecated)',
];

$db_user = 'stpet1155';
$db_pass = 'd991stpet1155';
$db_name = 'stpet1155';

?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Troubleshooter</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .test-result { margin: 15px 0; padding: 15px; border-radius: 8px; }
        .test-success { background: #d1fae5; border-left: 4px solid #10b981; }
        .test-fail { background: #fee2e2; border-left: 4px solid #ef4444; }
        .code-block { background: #f1f5f9; padding: 10px; border-radius: 5px; font-family: monospace; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🔧 Database Connection Troubleshooter</h1>
            <p class="subtitle">Testing different MySQL hostnames...</p>
        </header>

        <nav class="main-nav">
            <a href="index.php" class="nav-link">🏠 Hjem</a>
            <a href="test_db.php" class="nav-link">🔍 Database Test</a>
            <a href="troubleshoot_db.php" class="nav-link active">🔧 Troubleshoot</a>
        </nav>

        <main>
            <section>
                <h2>Testing Database Connections</h2>
                <p>Testing different hostname alternatives to find the correct one for your server...</p>

                <?php
                $successful_host = null;
                
                foreach ($hosts_to_test as $host => $description) {
                    echo "<div class='test-result'>";
                    echo "<h3>Testing: <code>$host</code></h3>";
                    echo "<p><em>$description</em></p>";
                    
                    // Disable error reporting temporarily
                    $old_error_reporting = error_reporting(0);
                    
                    try {
                        $conn = @new mysqli($host, $db_user, $db_pass, $db_name);
                        
                        if ($conn->connect_error) {
                            echo "<div class='test-fail'>";
                            echo "❌ <strong>Failed:</strong> " . htmlspecialchars($conn->connect_error);
                            echo "</div>";
                        } else {
                            echo "<div class='test-success'>";
                            echo "✅ <strong>Success!</strong> Connection established.";
                            echo "<br><strong>Server Version:</strong> " . $conn->server_info;
                            echo "<br><strong>Character Set:</strong> " . $conn->character_set_name();
                            echo "</div>";
                            
                            if (!$successful_host) {
                                $successful_host = $host;
                            }
                            
                            $conn->close();
                        }
                    } catch (Exception $e) {
                        echo "<div class='test-fail'>";
                        echo "❌ <strong>Exception:</strong> " . htmlspecialchars($e->getMessage());
                        echo "</div>";
                    }
                    
                    // Restore error reporting
                    error_reporting($old_error_reporting);
                    
                    echo "</div>";
                }
                ?>

                <?php if ($successful_host): ?>
                    <div class="message success">
                        <h3>✅ Solution Found!</h3>
                        <p>The following hostname works: <strong><?php echo htmlspecialchars($successful_host); ?></strong></p>
                        
                        <h4>How to fix:</h4>
                        <p>Update <code>db.php</code> and change the DB_HOST line to:</p>
                        <div class="code-block">
                            define('DB_HOST', '<?php echo htmlspecialchars($successful_host); ?>');
                        </div>
                        
                        <h4>Step-by-step:</h4>
                        <ol>
                            <li>Open the file <code>db.php</code></li>
                            <li>Find the line: <code>define('DB_HOST', '...');</code></li>
                            <li>Change it to: <code>define('DB_HOST', '<?php echo htmlspecialchars($successful_host); ?>');</code></li>
                            <li>Save the file</li>
                            <li>Test your application at <a href="index.php">index.php</a></li>
                        </ol>
                        
                        <a href="test_db.php" class="btn btn-primary">Test Database Connection</a>
                    </div>
                <?php else: ?>
                    <div class="message error">
                        <h3>❌ No Working Connection Found</h3>
                        <p>None of the tested hostnames could connect to the database.</p>
                        
                        <h4>Possible causes:</h4>
                        <ul>
                            <li>Database credentials are incorrect</li>
                            <li>MySQL server is not running</li>
                            <li>Firewall is blocking the connection</li>
                            <li>Database user does not have remote access permissions</li>
                            <li>MySQL server hostname is different from the tested ones</li>
                        </ul>
                        
                        <h4>What to do:</h4>
                        <ol>
                            <li>Contact USN IT support to get the correct MySQL hostname</li>
                            <li>Verify your database credentials in phpMyAdmin</li>
                            <li>Check if your database user has remote access permissions</li>
                            <li>Ask about the correct hostname for Dokploy-hosted applications</li>
                        </ol>
                    </div>
                <?php endif; ?>

                <div class="info-box">
                    <h3>Database Configuration Details</h3>
                    <p><strong>User:</strong> <?php echo htmlspecialchars($db_user); ?></p>
                    <p><strong>Database:</strong> <?php echo htmlspecialchars($db_name); ?></p>
                    <p><strong>Password:</strong> [Hidden for security]</p>
                </div>

                <div class="info-box warning">
                    <h3>⚠️ Security Note</h3>
                    <p><strong>Important:</strong> Delete this file (<code>troubleshoot_db.php</code>) after you've fixed the connection issue. It contains sensitive information about your database connection attempts.</p>
                </div>
            </section>
        </main>

        <footer>
            <p>&copy; 2025 PRG120V - Database Troubleshooter</p>
        </footer>
    </div>
</body>
</html>
