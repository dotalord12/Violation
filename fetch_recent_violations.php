<?php
// Establish database connection (replace dbname, username, password with your actual database credentials)
$pdo = new PDO('mysql:host=localhost;dbname=violation', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Prepare SQL statement to fetch recent violations data
$sql = "SELECT id, violation_type, violation_description, date_reported FROM violations ORDER BY date_reported DESC LIMIT 5"; // Assuming you want to fetch the 5 most recent violations
$stmt = $pdo->prepare($sql);
$stmt->execute();
$recentViolations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the database connection
$pdo = null;

// Send JSON response
header('Content-Type: application/json');
echo json_encode($recentViolations);
?>
