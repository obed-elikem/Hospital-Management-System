<?php
// Include the database connection
require_once "../../config/db.php";

// Check if the request_id parameter exists in the URL
if (isset($_GET["request_id"])) {
    $request_id = $_GET["request_id"];

    // Fetch the lab request information from the database
    $sql = "SELECT * FROM lab_requests WHERE id = '$request_id'";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) === 1) {
        // Fetch the lab request information
        $lab_request = mysqli_fetch_assoc($result);
        $test_name = $lab_request["test_name"];
    } else {
        // Redirect to the lab_requests page if the lab request is not found
        header("Location: lab_requests.php");
        exit();
    }
} else {
    // Redirect to the lab_requests page if the request_id parameter is not set
    header("Location: lab_requests.php");
    exit();
}

// Initialize variables for modals
$successModal = false;
$errorModal = false;
$modalMessage = "";

// Process the form submission if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $test_results = $_POST["test_results"];
    $status = $_POST["status"];

    // Update the lab_requests table with the test results and status
    $update_sql = "UPDATE lab_requests SET test_results = ?, status = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($connection, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "ssi", $test_results, $status, $request_id);

    if (mysqli_stmt_execute($update_stmt)) {
        // Set success modal variables
        $successModal = true;
        $modalMessage = "Test results and feedback submitted successfully!";
    } else {
        // Set error modal variables
        $errorModal = true;
        $modalMessage = "Error: " . mysqli_error($connection);
    }

    // Close the statement
    mysqli_stmt_close($update_stmt);
}

// Close the database connection
// mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Lab Results</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1>Input Lab Results</h1>
        <p><strong>Test Name:</strong> <?php echo $test_name; ?></p>

        <form action="" method="post">
            <div class="mb-3">
                <label for="test_results" class="form-label">Test Results:</label>
                <textarea name="test_results" id="test_results" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status:</label>
                <select name="status" class="form-select" required>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                    <option value="Canceled">Canceled</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit Results and Feedback</button>
        </form>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Success</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Test results and feedback submitted successfully!
                </div>
                <div class="d-flex justify-content-end m-2">
                    <a href="./laboratoristdashboard.php" class="btn btn-primary">Close</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Modal -->
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Error occurred. Please try again.
                </div>
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Show modals using JavaScript -->
    <?php
    if ($successModal) {
        echo '<script>var successModal = new bootstrap.Modal(document.getElementById("successModal")); successModal.show();</script>';
    }
    if ($errorModal) {
        echo '<script>var errorModal = new bootstrap.Modal(document.getElementById("errorModal")); errorModal.show();</script>';
    }
    ?>
</body>

</html>