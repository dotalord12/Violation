<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "violation";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the latest violation record
$sql = "SELECT * FROM violations ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the latest violation record as an associative array
    $latestViolation = $result->fetch_assoc();
    // Close connection
    $conn->close();
    // Return the latest violation record as JSON
    echo json_encode($latestViolation);
} else {
    // If no violation records found, return an empty JSON object
    echo json_encode([]);
}
?>
