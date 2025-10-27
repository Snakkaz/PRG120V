<?php
/**
 * Blank versjon - FULLSTENDIG FUNKSJONELL uten CSS
 * PRG120V - Alle CRUD operasjoner tilgjengelig
 */

require_once 'db.php';

$melding = '';
$melding_type = '';

// Håndter POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDbConnection();
    
    // OPPRETT NY KLASSE
    if (isset($_POST['opprett_klasse'])) {
        $klassekode = escapeString($conn, strtoupper(trim($_POST['klassekode'])));
        $klassenavn = escapeString($conn, trim($_POST['klassenavn']));
        $studiumkode = escapeString($conn, trim($_POST['studiumkode']));
        
        if (empty($klassekode) || strlen($klassekode) > 5) {
            $melding = "Klassekode må være mellom 1 og 5 tegn.";
            $melding_type = "error";
        } elseif (empty($klassenavn) || empty($studiumkode)) {
            $melding = "Alle felt må fylles ut.";
            $melding_type = "error";
        } else {
            try {
                $sql = "INSERT INTO klasse (klassekode, klassenavn, studiumkode) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $klassekode, $klassenavn, $studiumkode);
                $stmt->execute();
                $melding = "Klasse '$klassekode' ble registrert!";
                $melding_type = "success";
                $stmt->close();
            } catch (mysqli_sql_exception $e) {
                if ($conn->errno == 1062) {
                    $melding = "Klasse med kode '$klassekode' eksisterer allerede.";
                } else {
                    $melding = "Feil ved registrering: " . $e->getMessage();
                }
                $melding_type = "error";
            }
        }
        closeDbConnection($conn);
    }
    
    // SLETT KLASSE
    if (isset($_POST['slett_klasse'])) {
        $klassekode = escapeString($conn, $_POST['klassekode']);
        
        try {
            $sql = "DELETE FROM klasse WHERE klassekode = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $klassekode);
            $stmt->execute();
            $melding = "Klasse '$klassekode' ble slettet!";
            $melding_type = "success";
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            if ($conn->errno == 1451) {
                $melding = "Kan ikke slette klasse '$klassekode' - det finnes studenter i denne klassen.";
            } else {
                $melding = "Feil ved sletting: " . $e->getMessage();
            }
            $melding_type = "error";
        }
        closeDbConnection($conn);
    }
    
    // OPPRETT NY STUDENT
    if (isset($_POST['opprett_student'])) {
        $brukernavn = escapeString($conn, strtolower(trim($_POST['brukernavn'])));
        $fornavn = escapeString($conn, trim($_POST['fornavn']));
        $etternavn = escapeString($conn, trim($_POST['etternavn']));
        $klassekode = escapeString($conn, $_POST['klassekode']);
        
        if (empty($brukernavn) || strlen($brukernavn) > 7) {
            $melding = "Brukernavn må være mellom 1 og 7 tegn.";
            $melding_type = "error";
        } elseif (empty($fornavn) || empty($etternavn) || empty($klassekode)) {
            $melding = "Alle felt må fylles ut.";
            $melding_type = "error";
        } else {
            try {
                $sql = "INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $brukernavn, $fornavn, $etternavn, $klassekode);
                $stmt->execute();
                $melding = "Student '$brukernavn' ($fornavn $etternavn) ble registrert!";
                $melding_type = "success";
                $stmt->close();
            } catch (mysqli_sql_exception $e) {
                if ($conn->errno == 1062) {
                    $melding = "Student med brukernavn '$brukernavn' eksisterer allerede.";
                } elseif ($conn->errno == 1452) {
                    $melding = "Klassekode '$klassekode' finnes ikke. Registrer klassen først.";
                } else {
                    $melding = "Feil ved registrering: " . $e->getMessage();
                }
                $melding_type = "error";
            }
        }
        closeDbConnection($conn);
    }
    
    // SLETT STUDENT
    if (isset($_POST['slett_student'])) {
        $brukernavn = escapeString($conn, $_POST['brukernavn']);
        
        try {
            $sql = "DELETE FROM student WHERE brukernavn = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $brukernavn);
            $stmt->execute();
            $melding = "Student '$brukernavn' ble slettet!";
            $melding_type = "success";
            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $melding = "Feil ved sletting: " . $e->getMessage();
            $melding_type = "error";
        }
        closeDbConnection($conn);
    }
}

// Hent data
$conn = getDbConnection();
$klasser = $conn->query("SELECT * FROM klasse ORDER BY klassekode");
$studenter = $conn->query("
    SELECT s.*, k.klassenavn
    FROM student s
    LEFT JOIN klasse k ON s.klassekode = k.klassekode
    ORDER BY s.etternavn, s.fornavn
");
$klasser_for_dropdown = $conn->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode");
closeDbConnection($conn);
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blank versjon - PRG120V</title>
</head>
<body>
    <h1>Student- og Klassebehandling (Blank versjon - Fullstendig funksjonell)</h1>
    <p>PRG120V - Uten CSS styling - Hvit bakgrunn, sort tekst</p>
    
    <hr>
    
    <nav>
        <a href="index.php">Hjem (med styling)</a> |
        <a href="klasse.php">Klasser (med styling)</a> |
        <a href="student.php">Studenter (med styling)</a> |
        <a href="plain.php">Enkel visning</a> |
        <strong>Blank (aktiv)</strong>
    </nav>
    
    <hr>
    
    <?php if ($melding): ?>
        <p><strong>[<?php echo strtoupper($melding_type); ?>]</strong> <?php echo htmlspecialchars($melding); ?></p>
        <hr>
    <?php endif; ?>
    
    <h2>KLASSEBEHANDLING</h2>
    
    <h3>Registrer ny klasse</h3>
    <form method="POST" action="blank.php">
        <p>
            <label for="klassekode">Klassekode:</label><br>
            <input type="text" id="klassekode" name="klassekode" maxlength="5" required>
            <small>(Maks 5 tegn, f.eks: IT1)</small>
        </p>
        
        <p>
            <label for="klassenavn">Klassenavn:</label><br>
            <input type="text" id="klassenavn" name="klassenavn" maxlength="50" required>
        </p>
        
        <p>
            <label for="studiumkode">Studiumkode:</label><br>
            <input type="text" id="studiumkode" name="studiumkode" maxlength="50" required>
        </p>
        
        <p>
            <button type="submit" name="opprett_klasse">Opprett klasse</button>
        </p>
    </form>
    
    <hr>
    
    <h3>Alle klasser</h3>
    <?php if ($klasser->num_rows > 0): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Klassekode</th>
                    <th>Klassenavn</th>
                    <th>Studiumkode</th>
                    <th>Handling</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($klasse = $klasser->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($klasse['klassekode']); ?></td>
                        <td><?php echo htmlspecialchars($klasse['klassenavn']); ?></td>
                        <td><?php echo htmlspecialchars($klasse['studiumkode']); ?></td>
                        <td>
                            <form method="POST" action="blank.php" style="display: inline;">
                                <input type="hidden" name="klassekode" value="<?php echo htmlspecialchars($klasse['klassekode']); ?>">
                                <button type="submit" name="slett_klasse" onclick="return confirm('Er du sikker på at du vil slette denne klassen?');">Slett</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Ingen klasser registrert.</p>
    <?php endif; ?>
    
    <hr>
    <hr>
    
    <h2>STUDENTBEHANDLING</h2>
    
    <h3>Registrer ny student</h3>
    <?php if ($klasser_for_dropdown->num_rows > 0): ?>
        <form method="POST" action="blank.php">
            <p>
                <label for="brukernavn">Brukernavn:</label><br>
                <input type="text" id="brukernavn" name="brukernavn" maxlength="7" required>
                <small>(Maks 7 tegn, små bokstaver, f.eks: gb)</small>
            </p>
            
            <p>
                <label for="fornavn">Fornavn:</label><br>
                <input type="text" id="fornavn" name="fornavn" maxlength="50" required>
            </p>
            
            <p>
                <label for="etternavn">Etternavn:</label><br>
                <input type="text" id="etternavn" name="etternavn" maxlength="50" required>
            </p>
            
            <p>
                <label for="klassekode_student">Klasse:</label><br>
                <select id="klassekode_student" name="klassekode" required>
                    <option value="">Velg klasse...</option>
                    <?php while ($k = $klasser_for_dropdown->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($k['klassekode']); ?>">
                            <?php echo htmlspecialchars($k['klassekode'] . ' - ' . $k['klassenavn']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </p>
            
            <p>
                <button type="submit" name="opprett_student">Opprett student</button>
            </p>
        </form>
    <?php else: ?>
        <p><strong>OBS:</strong> Du må opprette minst én klasse før du kan registrere studenter.</p>
    <?php endif; ?>
    
    <hr>
    
    <h3>Alle studenter</h3>
    <?php if ($studenter->num_rows > 0): ?>
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Brukernavn</th>
                    <th>Fornavn</th>
                    <th>Etternavn</th>
                    <th>Klassekode</th>
                    <th>Klassenavn</th>
                    <th>Handling</th>
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
                        <td>
                            <form method="POST" action="blank.php" style="display: inline;">
                                <input type="hidden" name="brukernavn" value="<?php echo htmlspecialchars($student['brukernavn']); ?>">
                                <button type="submit" name="slett_student" onclick="return confirm('Er du sikker på at du vil slette denne studenten?');">Slett</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Ingen studenter registrert.</p>
    <?php endif; ?>
    
    <hr>
    
    <footer>
        <p>&copy; 2025 PRG120V - Student- og Klassebehandling | USN</p>
        <p>Blank versjon - Fullstendig funksjonell uten CSS styling</p>
    </footer>
</body>
</html>
