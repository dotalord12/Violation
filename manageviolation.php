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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Violations</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { background-color: white; }
        .container { max-width: 800px; margin: 0 auto; padding-top: 50px; }
        .card { border: 1px solid #dee2e6; }
        .card-header { background-color: #007bff; color: #fff; }
        .table-responsive { margin-top: 20px; }
        .action-buttons { display: flex; align-items: center; }
        .action-buttons button { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Violations</h1>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Reported Violations</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Violation ID</th>
                                <th>Student Name</th>
                                <th>Course</th>
                                <th>Violation Type</th>
                                <th>Description</th>
                                <th>Date Reported</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="violationTableBody">
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

                            $sql = "SELECT id, student_name, course, violation_type, violation_description, date_reported FROM violations";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                          <td>{$row['id']}</td>
                                          <td>{$row['student_name']}</td>
                                          <td>{$row['course']}</td>
                                          <td>{$row['violation_type']}</td>
                                          <td>{$row['violation_description']}</td>
                                          <td>{$row['date_reported']}</td>
                                          <td class='action-buttons'>
                                              <button type='button' class='btn btn-success approveBtn'>Approve</button>
                                              <button type='button' class='btn btn-danger rejectBtn'>Reject</button>
                                          </td>
                                          </tr>";
                                }
                            
                        } else {
                            echo "<tr><td colspan='7'>No violations found</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <a href="Title UI.html" class="btn btn-primary">Report a New Violation</a>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Add Font Awesome library -->

<script>
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('approveBtn') || event.target.classList.contains('rejectBtn')) {
            var btn = event.target;
            // Disable the button to prevent multiple clicks
            btn.disabled = true;

            // Change button style to green for Approve and red for Reject
            if (btn.classList.contains('approveBtn')) {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-success');
            } else {
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-secondary');
            }

            // Change button text to edit icon
            btn.innerHTML = '<i class="fas fa-edit"></i>';

            var row = event.target.closest('tr');
            var violationId = row.cells[0].innerText;
            var status = btn.classList.contains('approveBtn') ? 'approved' : 'rejected';

            // AJAX request to update the violation status
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'updateviolationstatus.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        console.log("Violation " + status + ": " + violationId);
                        // Redirect to the student dashboard
                        window.location.href = "Recent_violation.html";
                    } else {
                        console.error('Error:', response.message);
                    }
                } else {
                    console.error('Error:', xhr.statusText);
                }
            };
            xhr.onerror = function() {
                console.error('Connection Error');
            };
            xhr.send('violationId=' + encodeURIComponent(violationId) + '&status=' + status);
        }
    });
</script>

</body>
</html>
