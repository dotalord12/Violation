<?php
// Connect to the database
$pdo = new PDO('mysql:host=localhost;dbname=violation', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Prepare SQL statement to select approved violations
$sql = "SELECT * FROM violations WHERE status = 'Approved'";
$stmt = $pdo->query($sql);

// Fetch all approved violations
$approvedViolations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Close connection
$pdo = null;

// Return approved violations as JSON data
header('Content-Type: application/json');
echo json_encode($approvedViolations);
?>
