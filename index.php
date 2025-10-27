<?php
require_once 'config.php';

// Initialize connection
$conn = getConnection();

// Handle form submissions
$message = '';
$error = '';

// Add new class
if (isset($_POST['add_klasse'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO klasse (klassekode, klassenavn, studiumkode) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['klassekode'], $_POST['klassenavn'], $_POST['studiumkode']]);
        $message = "Klasse lagt til!";
    } catch (PDOException $e) {
        $error = "Feil ved tillegg av klasse: " . $e->getMessage();
    }
}

// Delete class
if (isset($_POST['delete_klasse'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM klasse WHERE klassekode = ?");
        $stmt->execute([$_POST['klassekode_delete']]);
        $message = "Klasse slettet!";
    } catch (PDOException $e) {
        $error = "Feil ved sletting av klasse: " . $e->getMessage();
    }
}

// Add new student
if (isset($_POST['add_student'])) {
    try {
        $stmt = $conn->prepare("INSERT INTO student (brukernavn, fornavn, etternavn, klassekode) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_POST['brukernavn'], $_POST['fornavn'], $_POST['etternavn'], $_POST['student_klassekode']]);
        $message = "Student lagt til!";
    } catch (PDOException $e) {
        $error = "Feil ved tillegg av student: " . $e->getMessage();
    }
}

// Delete student
if (isset($_POST['delete_student'])) {
    try {
        $stmt = $conn->prepare("DELETE FROM student WHERE brukernavn = ?");
        $stmt->execute([$_POST['brukernavn_delete']]);
        $message = "Student slettet!";
    } catch (PDOException $e) {
        $error = "Feil ved sletting av student: " . $e->getMessage();
    }
}

// Fetch all classes
$klasser = $conn->query("SELECT * FROM klasse ORDER BY klassekode")->fetchAll();

// Fetch all students
$studenter = $conn->query("SELECT s.*, k.klassenavn FROM student s LEFT JOIN klasse k ON s.klassekode = k.klassekode ORDER BY s.etternavn, s.fornavn")->fetchAll();
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student- og Klasseadministrasjon</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        
        h2 {
            color: #555;
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .error {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .section {
            margin-bottom: 40px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            background-color: white;
        }
        
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        
        button:hover {
            background-color: #0056b3;
        }
        
        button.delete {
            background-color: #dc3545;
        }
        
        button.delete:hover {
            background-color: #c82333;
        }
        
        .list-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        
        .list-box {
            flex: 1;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            background-color: #f9f9f9;
            min-height: 200px;
        }
        
        .list-box h3 {
            margin-bottom: 10px;
            color: #555;
        }
        
        select[multiple] {
            height: 200px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #007bff;
            color: white;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student- og Klasseadministrasjon</h1>
        
        <?php if ($message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <!-- Class Management Section -->
        <div class="section">
            <h2>Klassehåndtering</h2>
            
            <h3>Registrer ny klasse</h3>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="klassekode">Klassekode:</label>
                        <input type="text" id="klassekode" name="klassekode" required>
                    </div>
                    <div class="form-group">
                        <label for="klassenavn">Klassenavn:</label>
                        <input type="text" id="klassenavn" name="klassenavn" required>
                    </div>
                    <div class="form-group">
                        <label for="studiumkode">Studiumkode:</label>
                        <input type="text" id="studiumkode" name="studiumkode" required>
                    </div>
                </div>
                <button type="submit" name="add_klasse">Legg til klasse</button>
            </form>
            
            <h3 style="margin-top: 30px;">Alle klasser</h3>
            <table>
                <thead>
                    <tr>
                        <th>Klassekode</th>
                        <th>Klassenavn</th>
                        <th>Studiumkode</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($klasser as $klasse): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($klasse['klassekode']); ?></td>
                            <td><?php echo htmlspecialchars($klasse['klassenavn']); ?></td>
                            <td><?php echo htmlspecialchars($klasse['studiumkode']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h3 style="margin-top: 30px;">Slett klasse</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="klassekode_delete">Velg klasse å slette:</label>
                    <select id="klassekode_delete" name="klassekode_delete" size="5" required>
                        <?php foreach ($klasser as $klasse): ?>
                            <option value="<?php echo htmlspecialchars($klasse['klassekode']); ?>">
                                <?php echo htmlspecialchars($klasse['klassekode'] . ' - ' . $klasse['klassenavn']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="delete_klasse" class="delete">Slett valgt klasse</button>
            </form>
        </div>
        
        <!-- Student Management Section -->
        <div class="section">
            <h2>Studenthåndtering</h2>
            
            <h3>Registrer ny student</h3>
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="brukernavn">Brukernavn:</label>
                        <input type="text" id="brukernavn" name="brukernavn" required>
                    </div>
                    <div class="form-group">
                        <label for="fornavn">Fornavn:</label>
                        <input type="text" id="fornavn" name="fornavn" required>
                    </div>
                    <div class="form-group">
                        <label for="etternavn">Etternavn:</label>
                        <input type="text" id="etternavn" name="etternavn" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="student_klassekode">Klasse:</label>
                    <select id="student_klassekode" name="student_klassekode">
                        <option value="">-- Ingen klasse --</option>
                        <?php foreach ($klasser as $klasse): ?>
                            <option value="<?php echo htmlspecialchars($klasse['klassekode']); ?>">
                                <?php echo htmlspecialchars($klasse['klassekode'] . ' - ' . $klasse['klassenavn']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="add_student">Legg til student</button>
            </form>
            
            <h3 style="margin-top: 30px;">Alle studenter</h3>
            <table>
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
                    <?php foreach ($studenter as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['brukernavn']); ?></td>
                            <td><?php echo htmlspecialchars($student['fornavn']); ?></td>
                            <td><?php echo htmlspecialchars($student['etternavn']); ?></td>
                            <td><?php echo htmlspecialchars($student['klassekode'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($student['klassenavn'] ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h3 style="margin-top: 30px;">Slett student</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="brukernavn_delete">Velg student å slette:</label>
                    <select id="brukernavn_delete" name="brukernavn_delete" size="5" required>
                        <?php foreach ($studenter as $student): ?>
                            <option value="<?php echo htmlspecialchars($student['brukernavn']); ?>">
                                <?php echo htmlspecialchars($student['brukernavn'] . ' - ' . $student['fornavn'] . ' ' . $student['etternavn']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="delete_student" class="delete">Slett valgt student</button>
            </form>
        </div>
    </div>
</body>
</html>
