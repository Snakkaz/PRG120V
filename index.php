<?php
/**
 * Hovedside for Student- og Klassebehandling
 * PRG120V - Oppgave
 * 
 * Dette er startsiden for applikasjonen som viser meny og oversikt.
 */

require_once 'db.php';

// Hent statistikk fra database
$conn = getDbConnection();

// Tell antall klasser
$result = $conn->query("SELECT COUNT(*) as count FROM klasse");
$antall_klasser = $result->fetch_assoc()['count'];

// Tell antall studenter
$result = $conn->query("SELECT COUNT(*) as count FROM student");
$antall_studenter = $result->fetch_assoc()['count'];

closeDbConnection($conn);
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student- og Klassebehandling - PRG120V</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📚 Student- og Klassebehandling</h1>
            <p class="subtitle">PRG120V - Database Management System</p>
        </header>

        <nav class="main-nav">
            <a href="index.php" class="nav-link active">🏠 Hjem</a>
            <a href="klasse.php" class="nav-link">📖 Klasser</a>
            <a href="student.php" class="nav-link">👨‍🎓 Studenter</a>
            <a href="blank.php" class="nav-link">📄 Blank</a>
        </nav>

        <main>
            <section class="welcome-section">
                <h2>Velkommen til Student- og Klassebehandlingssystemet</h2>
                <p>Dette systemet lar deg administrere klasser og studenter ved hjelp av en MySQL-database.</p>
            </section>

            <section class="stats-section">
                <h3>Statistikk</h3>
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">📖</div>
                        <div class="stat-number"><?php echo $antall_klasser; ?></div>
                        <div class="stat-label">Klasser</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">👨‍🎓</div>
                        <div class="stat-number"><?php echo $antall_studenter; ?></div>
                        <div class="stat-label">Studenter</div>
                    </div>
                </div>
            </section>

            <section class="features-section">
                <h3>Funksjoner</h3>
                <div class="features-grid">
                    <div class="feature-card">
                        <h4>📖 Klassebehandling</h4>
                        <ul>
                            <li>✅ Registrer nye klasser</li>
                            <li>✅ Vis alle klasser</li>
                            <li>✅ Slett klasser</li>
                        </ul>
                        <a href="klasse.php" class="btn btn-primary">Gå til Klasser</a>
                    </div>
                    <div class="feature-card">
                        <h4>👨‍🎓 Studentbehandling</h4>
                        <ul>
                            <li>✅ Registrer nye studenter</li>
                            <li>✅ Vis alle studenter</li>
                            <li>✅ Slett studenter</li>
                        </ul>
                        <a href="student.php" class="btn btn-primary">Gå til Studenter</a>
                    </div>
                </div>
            </section>

            <section class="info-section">
                <h3>Teknisk informasjon</h3>
                <div class="info-box">
                    <p><strong>Database:</strong> stpet1155</p>
                    <p><strong>Server:</strong> mysql.usn.no</p>
                    <p><strong>Deploy:</strong> <a href="https://dokploy.usn.no/app/stpet1155-prg120v/" target="_blank">USN Dokploy</a></p>
                    <p><strong>Repository:</strong> <a href="https://github.com/Snakkaz/PRG120V" target="_blank">GitHub - Snakkaz/PRG120V</a></p>
                </div>
            </section>
        </main>

        <footer>
            <p>&copy; 2025 PRG120V - Student- og Klassebehandling | USN</p>
        </footer>
    </div>
</body>
</html>
