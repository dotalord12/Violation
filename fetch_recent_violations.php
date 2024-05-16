<?php
// Establish database connection (replace dbname, username, password with your actual database credentials)
$pdo = new PDO('mysql:host=localhost;dbname=violation', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Prepare SQL statement to fetch recent violations data excluding rejected and pending ones
$sql = "SELECT id, violation_type, violation_description, date_reported FROM violations WHERE status NOT IN ('rejected', 'pending') ORDER BY date_reported DESC LIMIT 5"; // Fetch the 5 most recent non-rejected and non-pending violations
$stmt = $pdo->prepare($sql);
$stmt->execute();
$recentViolations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close the database connection
$pdo = null;

// Send JSON response
header('Content-Type: application/json');
echo json_encode($recentViolations);
?>
