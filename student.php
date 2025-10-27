<?php
/**
 * Studentbehandling - CRUD operasjoner for student-tabellen
 * PRG120V - Oppgave
 * 
 * Funksjoner:
 * - Create: Registrer ny student
 * - Read: Vis alle studenter
 * - Delete: Slett student
 */

require_once 'db.php';

// Håndter POST requests
$melding = '';
$melding_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDbConnection();
    
    // OPPRETT NY STUDENT
    if (isset($_POST['opprett'])) {
        $brukernavn = escapeString($conn, strtolower(trim($_POST['brukernavn'])));
        $fornavn = escapeString($conn, trim($_POST['fornavn']));
        $etternavn = escapeString($conn, trim($_POST['etternavn']));
        $klassekode = escapeString($conn, $_POST['klassekode']);
        
        // Validering
        if (strlen($brukernavn) != 7) {
            $melding = "Brukernavn må være nøyaktig 7 tegn.";
            $melding_type = "error";
        } elseif (empty($fornavn) || empty($etternavn) || empty($klassekode)) {
            $melding = "Alle felt må fylles ut.";
            $melding_type = "error";
        } else {
            $sql = "INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $brukernavn, $fornavn, $etternavn, $klassekode);
            
            if ($stmt->execute()) {
                $melding = "Student '$brukernavn' ($fornavn $etternavn) ble registrert!";
                $melding_type = "success";
            } else {
                if ($conn->errno == 1062) {
                    $melding = "Student med brukernavn '$brukernavn' eksisterer allerede.";
                } elseif ($conn->errno == 1452) {
                    $melding = "Klassekode '$klassekode' finnes ikke. Registrer klassen først.";
                } else {
                    $melding = "Feil ved registrering: " . $conn->error;
                }
                $melding_type = "error";
            }
            $stmt->close();
        }
    }
    
    // SLETT STUDENT
    if (isset($_POST['slett'])) {
        $brukernavn = escapeString($conn, $_POST['brukernavn']);
        
        $sql = "DELETE FROM student WHERE brukernavn = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $brukernavn);
        
        if ($stmt->execute()) {
            $melding = "Student '$brukernavn' ble slettet.";
            $melding_type = "success";
        } else {
            $melding = "Feil ved sletting: " . $conn->error;
            $melding_type = "error";
        }
        $stmt->close();
    }
    
    closeDbConnection($conn);
}

// Hent alle studenter med klasseinformasjon
$conn = getDbConnection();
$sql = "SELECT s.brukernavn, s.fornavn, s.etternavn, s.klassekode, k.klassenavn 
        FROM student s 
        LEFT JOIN klasse k ON s.klassekode = k.klassekode 
        ORDER BY s.etternavn, s.fornavn";
$studenter = $conn->query($sql);

// Hent alle klasser for dropdown
$klasser = $conn->query("SELECT klassekode, klassenavn FROM klasse ORDER BY klassekode");
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studentbehandling - PRG120V</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>👨‍🎓 Studentbehandling</h1>
            <p class="subtitle">Administrer studenter i systemet</p>
        </header>

        <nav class="main-nav">
            <a href="index.php" class="nav-link">🏠 Hjem</a>
            <a href="klasse.php" class="nav-link">📖 Klasser</a>
            <a href="student.php" class="nav-link active">👨‍🎓 Studenter</a>
        </nav>

        <main>
            <?php if ($melding): ?>
                <div class="message <?php echo $melding_type; ?>">
                    <?php echo htmlspecialchars($melding); ?>
                </div>
            <?php endif; ?>

            <!-- REGISTRER NY STUDENT -->
            <section class="form-section">
                <h2>➕ Registrer ny student</h2>
                
                <?php if ($klasser->num_rows > 0): ?>
                    <form method="POST" action="student.php" class="form">
                        <div class="form-group">
                            <label for="brukernavn">Brukernavn (7 tegn):</label>
                            <input type="text" id="brukernavn" name="brukernavn" maxlength="7" 
                                   pattern="[a-z0-9]{7}" required 
                                   placeholder="F.eks: stpet11">
                            <small>Må være nøyaktig 7 tegn (små bokstaver og tall)</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="fornavn">Fornavn:</label>
                            <input type="text" id="fornavn" name="fornavn" maxlength="50" 
                                   required placeholder="F.eks: Petter">
                        </div>
                        
                        <div class="form-group">
                            <label for="etternavn">Etternavn:</label>
                            <input type="text" id="etternavn" name="etternavn" maxlength="50" 
                                   required placeholder="F.eks: Petterson">
                        </div>
                        
                        <div class="form-group">
                            <label for="klassekode">Velg klasse:</label>
                            <select id="klassekode" name="klassekode" required class="form-select">
                                <option value="">-- Velg en klasse --</option>
                                <?php 
                                $klasser->data_seek(0); // Reset pointer
                                while ($klasse = $klasser->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo htmlspecialchars($klasse['klassekode']); ?>">
                                        <?php echo htmlspecialchars($klasse['klassekode'] . ' - ' . $klasse['klassenavn']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <button type="submit" name="opprett" class="btn btn-primary">
                            ➕ Opprett student
                        </button>
                    </form>
                <?php else: ?>
                    <div class="info-box warning">
                        <p>⚠️ Du må registrere minst én klasse før du kan legge til studenter.</p>
                        <a href="klasse.php" class="btn btn-primary">Gå til Klassebehandling</a>
                    </div>
                <?php endif; ?>
            </section>

            <!-- VIS ALLE STUDENTER -->
            <section class="table-section">
                <h2>📋 Alle studenter</h2>
                
                <?php if ($studenter->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="data-table">
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
                                <?php while ($row = $studenter->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($row['brukernavn']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['fornavn']); ?></td>
                                        <td><?php echo htmlspecialchars($row['etternavn']); ?></td>
                                        <td><?php echo htmlspecialchars($row['klassekode']); ?></td>
                                        <td><?php echo htmlspecialchars($row['klassenavn'] ?? 'N/A'); ?></td>
                                        <td>
                                            <form method="POST" action="student.php" style="display: inline;" 
                                                  onsubmit="return confirm('Er du sikker på at du vil slette studenten \'<?php echo htmlspecialchars($row['brukernavn']); ?>\'?');">
                                                <input type="hidden" name="brukernavn" value="<?php echo htmlspecialchars($row['brukernavn']); ?>">
                                                <button type="submit" name="slett" class="btn btn-danger btn-sm">
                                                    🗑️ Slett
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="info-box">
                        <p>Ingen studenter registrert ennå. Bruk skjemaet over for å registrere en ny student.</p>
                    </div>
                <?php endif; ?>
            </section>
        </main>

        <footer>
            <p>&copy; 2025 PRG120V - Student- og Klassebehandling | USN</p>
        </footer>
    </div>
</body>
</html>
<?php
closeDbConnection($conn);
?>
