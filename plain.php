<?php
/**
 * Blank side uten CSS styling - PRG120V
 * Kopi av full funksjonalitet, kun ren HTML
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

// Hent alle klasser
$klasser = $conn->query("SELECT * FROM klasse ORDER BY klassekode");

// Hent alle studenter med klasseinformasjon
$studenter = $conn->query("
    SELECT s.*, k.klassenavn 
    FROM student s 
    LEFT JOIN klasse k ON s.klassekode = k.klassekode 
    ORDER BY s.etternavn, s.fornavn
");

closeDbConnection($conn);
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blank Side - PRG120V</title>
</head>
<body>
    <h1>Student- og Klassebehandling (Blank versjon)</h1>
    <p>PRG120V - Database Management System - Uten CSS styling</p>
    
    <hr>
    
    <nav>
        <a href="index.php">Hjem</a> |
        <a href="klasse.php">Klasser</a> |
        <a href="student.php">Studenter</a> |
        <a href="plain.php">Blank (aktiv)</a>
    </nav>
    
    <hr>
    
    <h2>Velkommen</h2>
    <p>Dette systemet lar deg administrere klasser og studenter ved hjelp av en MySQL-database.</p>
    <p>Dette er den blanke versjonen uten CSS-styling - kun ren HTML.</p>
    
    <hr>
    
    <h2>Statistikk</h2>
    <ul>
        <li>Antall klasser: <strong><?php echo $antall_klasser; ?></strong></li>
        <li>Antall studenter: <strong><?php echo $antall_studenter; ?></strong></li>
    </ul>
    
    <hr>
    
    <h2>Alle klasser</h2>
    <?php if ($klasser->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Klassekode</th>
                    <th>Klassenavn</th>
                    <th>Studiumkode</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($klasse = $klasser->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($klasse['klassekode']); ?></td>
                        <td><?php echo htmlspecialchars($klasse['klassenavn']); ?></td>
                        <td><?php echo htmlspecialchars($klasse['studiumkode']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Ingen klasser registrert.</p>
    <?php endif; ?>
    
    <hr>
    
    <h2>Alle studenter</h2>
    <?php if ($studenter->num_rows > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Brukernavn</th>
                    <th>Fornavn</th>
                    <th>Etternavn</th>
                    <th>Klassekode</th>
                    <th>Klassenavn</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($student = $studenter->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['brukernavn']); ?></td>
                        <td><?php echo htmlspecialchars($student['fornavn']); ?></td>
                        <td><?php echo htmlspecialchars($student['etternavn']); ?></td>
                        <td><?php echo htmlspecialchars($student['klassekode']); ?></td>
                        <td><?php echo htmlspecialchars($student['klassenavn'] ?? '-'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Ingen studenter registrert.</p>
    <?php endif; ?>
    
    <hr>
    
    <h2>Funksjoner</h2>
    
    <h3>Klassebehandling</h3>
    <ul>
        <li>Registrer nye klasser</li>
        <li>Vis alle klasser</li>
        <li>Slett klasser</li>
    </ul>
    <p><a href="klasse.php">Gå til Klasser (med styling)</a></p>
    
    <h3>Studentbehandling</h3>
    <ul>
        <li>Registrer nye studenter</li>
        <li>Vis alle studenter</li>
        <li>Slett studenter</li>
    </ul>
    <p><a href="student.php">Gå til Studenter (med styling)</a></p>
    
    <hr>
    
    <h2>Teknisk informasjon</h2>
    <dl>
        <dt>Database:</dt>
        <dd>stpet1155</dd>
        
        <dt>Server:</dt>
        <dd>b-studentsql-1.usn.no</dd>
        
        <dt>Deploy:</dt>
        <dd><a href="https://dokploy.usn.no/app/stpet1155-prg120v/" target="_blank">USN Dokploy</a></dd>
        
        <dt>Repository:</dt>
        <dd><a href="https://github.com/Snakkaz/PRG120V" target="_blank">GitHub - Snakkaz/PRG120V</a></dd>
    </dl>
    
    <hr>
    
    <footer>
        <p>&copy; 2025 PRG120V - Student- og Klassebehandling | USN</p>
        <p>Blank versjon uten CSS styling</p>
    </footer>
</body>
</html>
