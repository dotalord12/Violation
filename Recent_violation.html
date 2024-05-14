<!-- student_dashboard.html -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div id="dashboardModule">
        <div class="container">
            <h1 class="text-center mt-4 mb-5">STUDENT DASHBOARD</h1>
            <div class="row">
                <div class="col-lg-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h2 class="card-title text-danger">Recent Violations</h2>
                            <ul id="recentViolationsList" class="list-group">
                                <!-- Recent violations will be dynamically populated here -->
                            </ul>
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">Last updated <span id="lastUpdatedTime"></span></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function fetchRecentViolations() {
            $.ajax({
                url: 'fetch_recent_violations.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    updateRecentViolations(data);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching recent violations:', error);
                }
            });
        }

        function updateRecentViolations(violations) {
            $('#recentViolationsList').empty();
            $('#lastUpdatedTime').text(new Date().toLocaleString());

            violations.forEach(function(violation) {
                var listItem = '<li class="list-group-item">' +
                    'Violation ID: ' + violation.id +
                    '<br>Violation Type: ' + violation.violation_type +
                    '<br>Description: ' + violation.violation_description +
                    '<br>Date Reported: ' + violation.date_reported +
                    '</li>';
                $('#recentViolationsList').append(listItem);
            });
        }

        // Initial fetch
        fetchRecentViolations();

        // Listen for postMessage events
        window.addEventListener('message', function(event) {
            if (event.data.type === 'updateRecentViolations') {
                updateRecentViolations(event.data.violations);
            }
        }, false);
    </script>
</body>
</html>
