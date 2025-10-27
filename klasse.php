<?php
/**
 * Klassebehandling - CRUD operasjoner for klasse-tabellen
 * PRG120V - Oppgave
 * 
 * Funksjoner:
 * - Create: Registrer ny klasse
 * - Read: Vis alle klasser
 * - Delete: Slett klasse
 */

require_once 'db.php';

// Håndter POST requests
$melding = '';
$melding_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = getDbConnection();
    
    // OPPRETT NY KLASSE
    if (isset($_POST['opprett'])) {
        $klassekode = escapeString($conn, strtoupper(trim($_POST['klassekode'])));
        $klassenavn = escapeString($conn, trim($_POST['klassenavn']));
        $studiumkode = escapeString($conn, trim($_POST['studiumkode']));
        
        // Validering
        if (strlen($klassekode) != 5) {
            $melding = "Klassekode må være nøyaktig 5 tegn.";
            $melding_type = "error";
        } elseif (empty($klassenavn) || empty($studiumkode)) {
            $melding = "Alle felt må fylles ut.";
            $melding_type = "error";
        } else {
            $sql = "INSERT INTO klasse (klassekode, klassenavn, studiumkode) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $klassekode, $klassenavn, $studiumkode);
            
            if ($stmt->execute()) {
                $melding = "Klasse '$klassekode' ble registrert!";
                $melding_type = "success";
            } else {
                if ($conn->errno == 1062) {
                    $melding = "Klasse med kode '$klassekode' eksisterer allerede.";
                } else {
                    $melding = "Feil ved registrering: " . $conn->error;
                }
                $melding_type = "error";
            }
            $stmt->close();
        }
    }
    
    // SLETT KLASSE
    if (isset($_POST['slett'])) {
        $klassekode = escapeString($conn, $_POST['klassekode']);
        
        // Sjekk om klassen har studenter
        $check_sql = "SELECT COUNT(*) as count FROM student WHERE klassekode = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $klassekode);
        $stmt->execute();
        $result = $stmt->get_result();
        $count = $result->fetch_assoc()['count'];
        $stmt->close();
        
        if ($count > 0) {
            $melding = "Kan ikke slette klasse '$klassekode' - det finnes $count student(er) i denne klassen.";
            $melding_type = "error";
        } else {
            $sql = "DELETE FROM klasse WHERE klassekode = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $klassekode);
            
            if ($stmt->execute()) {
                $melding = "Klasse '$klassekode' ble slettet.";
                $melding_type = "success";
            } else {
                $melding = "Feil ved sletting: " . $conn->error;
                $melding_type = "error";
            }
            $stmt->close();
        }
    }
    
    closeDbConnection($conn);
}

// Hent alle klasser
$conn = getDbConnection();
$sql = "SELECT k.klassekode, k.klassenavn, k.studiumkode, COUNT(s.brukernavn) as antall_studenter 
        FROM klasse k 
        LEFT JOIN student s ON k.klassekode = s.klassekode 
        GROUP BY k.klassekode, k.klassenavn, k.studiumkode 
        ORDER BY k.klassekode";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klassebehandling - PRG120V</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>📖 Klassebehandling</h1>
            <p class="subtitle">Administrer klasser i systemet</p>
        </header>

        <nav class="main-nav">
            <a href="index.php" class="nav-link">🏠 Hjem</a>
            <a href="klasse.php" class="nav-link active">📖 Klasser</a>
            <a href="student.php" class="nav-link">👨‍🎓 Studenter</a>
        </nav>

        <main>
            <?php if ($melding): ?>
                <div class="message <?php echo $melding_type; ?>">
                    <?php echo htmlspecialchars($melding); ?>
                </div>
            <?php endif; ?>

            <!-- REGISTRER NY KLASSE -->
            <section class="form-section">
                <h2>➕ Registrer ny klasse</h2>
                <form method="POST" action="klasse.php" class="form">
                    <div class="form-group">
                        <label for="klassekode">Klassekode (5 tegn):</label>
                        <input type="text" id="klassekode" name="klassekode" maxlength="5" 
                               pattern="[A-Za-z0-9]{5}" required 
                               placeholder="F.eks: IT101">
                        <small>Må være nøyaktig 5 tegn (bokstaver og tall)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="klassenavn">Klassenavn:</label>
                        <input type="text" id="klassenavn" name="klassenavn" maxlength="50" 
                               required placeholder="F.eks: Informasjonsteknologi 1. klasse">
                    </div>
                    
                    <div class="form-group">
                        <label for="studiumkode">Studiumkode:</label>
                        <input type="text" id="studiumkode" name="studiumkode" maxlength="50" 
                               required placeholder="F.eks: ITE">
                    </div>
                    
                    <button type="submit" name="opprett" class="btn btn-primary">
                        ➕ Opprett klasse
                    </button>
                </form>
            </section>

            <!-- VIS ALLE KLASSER -->
            <section class="table-section">
                <h2>📋 Alle klasser</h2>
                
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Klassekode</th>
                                    <th>Klassenavn</th>
                                    <th>Studiumkode</th>
                                    <th>Antall studenter</th>
                                    <th>Handling</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($row['klassekode']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($row['klassenavn']); ?></td>
                                        <td><?php echo htmlspecialchars($row['studiumkode']); ?></td>
                                        <td><?php echo $row['antall_studenter']; ?></td>
                                        <td>
                                            <form method="POST" action="klasse.php" style="display: inline;" 
                                                  onsubmit="return confirm('Er du sikker på at du vil slette klassen \'<?php echo htmlspecialchars($row['klassekode']); ?>\'?');">
                                                <input type="hidden" name="klassekode" value="<?php echo htmlspecialchars($row['klassekode']); ?>">
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
                        <p>Ingen klasser registrert ennå. Bruk skjemaet over for å registrere en ny klasse.</p>
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
