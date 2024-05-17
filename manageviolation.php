<?php
// Handle form submission to add a new violation//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['violationId'])) {
    // Extract form data
    $studentName = $_POST['studentName'];
    $course = $_POST['course'];
    $violationType = $_POST['violationType'];
    $violationDescription = $_POST['violationDescription'];
    $dateReported = $_POST['dateReported'];

    try {
        $pdo = new PDO('mysql:host=localhost;dbname=violation', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO violations (student_name, course, violation_type, violation_description, date_reported, status) 
                VALUES (:studentName, :course, :violationType, :violationDescription, :dateReported, 'Pending')";
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['violationId']) && isset($_POST['status'])) {
    $violationId = $_POST['violationId'];
    $status = $_POST['status'];

    try {
        // Connect to the database
        $pdo = new PDO('mysql:host=localhost;dbname=violation', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL statement to update status
        $sql = "UPDATE violations SET status = :status WHERE id = :violationId";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':violationId', $violationId);

        // Execute the prepared statement
        $stmt->execute();

        // Close connection
        $pdo = null;

        // Return success message
        echo json_encode(['status' => 'success', 'message' => 'Violation status updated successfully']);
    } catch (PDOException $e) {
        // Handle database error
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
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
        .container-fluid { max-width: 100%; padding-top: 50px; }
        .card { border: 1px solid #dee2e6; }
        .card-header { background-color: #007bff; color: #fff; }
        .table-responsive { margin-top: 20px; }
        .action-buttons { display: flex; align-items: center; }
        .action-buttons button { margin-right: 10px; }
    </style>
</head>
<body style="background-color:white;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand text-light" href="Admin_Dashboard.html">Home</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item">
                        <a class="nav-link text-light" href="TITLE UI.html">Report Violation</a>
                    </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="manageviolation.php">Manage Violations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-light" href="Settings.html">Settings</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <button class="btn btn-danger" onclick="logout()">Logout</button>
                    <script> 
                    function logout() {
                        // Perform logout action here
                        // For demonstration, let's redirect to login.html
                        window.location.href = "Login.html";
                    }
                    </script> 
                </li>
            </ul>
        </div>
    </nav>
</head>
<body>
    <div class="container">
        <h1></h1>
        <div class="row justify-content-center mt-5"> <!-- Added mt-5 for top margin -->
        <div class="col-lg-8"></div>
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
                                <th>Status</th>
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

                            $sql = "SELECT id, student_name, course, violation_type, violation_description, date_reported, status FROM violations";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $status = $row['status'] ? $row['status'] : 'Pending';
                                    echo "<tr>
                                          <td>{$row['id']}</td>
                                          <td>{$row['student_name']}</td>
                                          <td>{$row['course']}</td>
                                          <td>{$row['violation_type']}</td>
                                          <td>{$row['violation_description']}</td>
                                          <td>{$row['date_reported']}</td>
                                          <td>{$status}</td>
                                          <td class='action-buttons'>
                                              <button type='button' class='btn btn-success approveBtn' data-id='{$row['id']}'>Approve</button>
                                              <button type='button' class='btn btn-danger rejectBtn' data-id='{$row['id']}'>Reject</button>
                                          </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No violations found</td></tr>";
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
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Add Font Awesome library -->

    <script> 
document.addEventListener('DOMContentLoaded', function() {
    // Fetch violation status after the page loads
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_recent_violations.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var violations = JSON.parse(xhr.responseText);
            updateViolationStatus(violations);
        } else {
            console.error('Error:', xhr.statusText);
        }
    };
    xhr.onerror = function() {
        console.error('Connection Error');
    };
    xhr.send();

    function updateViolationStatus(violations) {
        var rows = document.querySelectorAll('#violationTableBody tr');
        violations.forEach(function(violation) {
            var row = Array.from(rows).find(row => row.querySelector('td:first-child').textContent.trim() === violation.id);
            if (row) {
                var statusCell = row.querySelector('td:nth-child(7)');
                statusCell.textContent = violation.status;
                var actionBtn = row.querySelector('.action-buttons');
                if (violation.status === 'Approved') {
                    actionBtn.innerHTML = '<i class="fas fa-check text-success"></i>';
                } else if (violation.status === 'Rejected') {
                    actionBtn.innerHTML = '<i class="fas fa-times text-danger"></i>';
                }
                // Check if an action has been taken for this violation
                var storedAction = localStorage.getItem('violation_' + violation.id);
                if (storedAction === 'Approved' || storedAction === 'Rejected') {
                    // Hide the buttons and display appropriate icon
                    row.querySelector('.approveBtn').style.display = 'none';
                    row.querySelector('.rejectBtn').style.display = 'none';
                    if (storedAction === 'Approved') {
                        actionBtn.innerHTML = '<i class="fas fa-check text-success"></i>';
                    } else {
                        actionBtn.innerHTML = '<i class="fas fa-times text-danger"></i>';
                    }
                }
            }
        });
    }
});

document.addEventListener('click', function(event) {
    if (event.target.classList.contains('approveBtn') || event.target.classList.contains('rejectBtn')) {
        var btn = event.target;
        var row = btn.closest('tr');
        var violationId = btn.getAttribute('data-id');
        var status = btn.classList.contains('approveBtn') ? 'Approved' : 'Rejected';

        // Check if an action has already been taken for this violation
        var storedAction = localStorage.getItem('violation_' + violationId);
        if (storedAction === 'Approved' || storedAction === 'Rejected') {
            // Action already taken, do nothing
            return;
        }

        // Store the action in localStorage
        localStorage.setItem('violation_' + violationId, status);

        // Disable the buttons and display appropriate icon
        btn.style.display = 'none';
        var actionBtn = row.querySelector('.action-buttons');
        if (status === 'Approved') {
            actionBtn.innerHTML = '<i class="fas fa-check text-success"></i>';
        } else {
            actionBtn.innerHTML = '<i class="fas fa-times text-danger"></i>';
        }

        // AJAX request to update the violation status
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'manageviolation.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    console.log(response.message);
                    // Update status
                    var statusCell = row.querySelector('td:nth-child(7)');
                    statusCell.textContent = status;
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
        xhr.send('violationId=' + encodeURIComponent(violationId) + '&status=' + encodeURIComponent(status));
    }
});


</script>  

</body>
</html>