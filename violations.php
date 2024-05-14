<?php
// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract form data
    $studentName = $_POST['studentName'];
    $course = $_POST['course'];
    $violationType = $_POST['violationType'];
    $violationDescription = $_POST['violationDescription'];
    $dateReported = $_POST['dateReported'];

    // Insert form data into the database
    try {
        // Connect to the database (replace dbname, username, password with your actual database credentials)
        $pdo = new PDO('mysql:host=localhost;dbname=violation', 'root', '');
        
        // Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Prepare SQL statement to insert data
        $sql = "INSERT INTO violations (student_name, course, violation_type, violation_description, date_reported) 
                VALUES (:studentName, :course, :violationType, :violationDescription, :dateReported)";
        $stmt = $pdo->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':studentName', $studentName);
        $stmt->bindParam(':course', $course);
        $stmt->bindParam(':violationType', $violationType);
        $stmt->bindParam(':violationDescription', $violationDescription);
        $stmt->bindParam(':dateReported', $dateReported);
        
        // Execute the prepared statement
        $stmt->execute();

        // Close connection
        $pdo = null;

        // Return success message
        echo 'Violation record inserted successfully';
    } catch (PDOException $e) {
        // Handle database error
        echo 'Error: ' . $e->getMessage();
    }
}
?>
