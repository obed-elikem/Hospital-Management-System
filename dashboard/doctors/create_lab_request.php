<?php
// Include the database connection
require_once "../../config/db.php";

// Start the session
// session_start();

// Check if the doctor is logged in
if (!isset($_SESSION["id"])) {
    // Redirect to doctor login page or show an error message
    // header("Location: ./doctor_login.php");
    exit();
}

// Initialize variables to hold success and error messages
$success_message = '';
$error_message = '';

// Process the form submission if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["patient_name"]) && isset($_POST["test_name"])) {

    $patient_name = $_POST["patient_name"];
    $test_name = $_POST["test_name"];

    // Check if the patient is registered in the database
    $check_patient_sql = "SELECT id FROM patients WHERE fullname = ?";
    $check_patient_stmt = mysqli_prepare($connection, $check_patient_sql);
    mysqli_stmt_bind_param($check_patient_stmt, "s", $patient_name);
    mysqli_stmt_execute($check_patient_stmt);
    mysqli_stmt_store_result($check_patient_stmt);

    if (mysqli_stmt_num_rows($check_patient_stmt) > 0) {
        // Get the patient ID from the database
        mysqli_stmt_bind_result($check_patient_stmt, $patient_id);
        mysqli_stmt_fetch($check_patient_stmt);

        // Get the doctor ID from the session
        $doctor_id = $_SESSION["id"];

        // Insert the lab request into the lab_requests table
        $insert_sql = "INSERT INTO lab_requests (patient_id, doctor_id, test_name, request_date) VALUES (?, ?, ?, NOW())";
        $insert_stmt = mysqli_prepare($connection, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "iis", $patient_id, $doctor_id, $test_name);

        if (mysqli_stmt_execute($insert_stmt)) {
            // Set a success message
            $success_message = "Lab request submitted successfully!";
        } else {
            // Set an error message
            $error_message = "Error: " . mysqli_error($connection);
        }

        // Close the statement
        mysqli_stmt_close($insert_stmt);

        // Clear the form data
        $_POST = array();

        // Use JavaScript to redirect after form submission to prevent resubmission
        echo '<script>window.location.href = "' . $_SERVER['PHP_SELF'] . '";</script>';
        exit();
    } else {
        // Patient not registered in the database
        $error_message = "Error: Patient with name $patient_name is not registered.";
    }

    // Close the statement
    mysqli_stmt_close($check_patient_stmt);
}

// Close the database connection
// mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Lab Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2>Create Lab Request</h2>

        <?php
        // Display success message if available
        if (!empty($success_message)) {
            echo "<div class='alert alert-success'>$success_message</div>";
        }

        // Display error message if available
        if (!empty($error_message)) {
            echo "<div class='alert alert-danger'>$error_message</div>";
        }
        ?>

        <form action="" method="post">
            <div class="mb-3">
                <label for="patient_name" class="form-label">Patient Name:</label>
                <input type="text" class="form-control" name="patient_name" required>
            </div>
            <div class="mb-3">
                <label for="test_name" class="form-label">Test Name:</label>
                <textarea class="form-control" name="test_name" rows="5"></textarea>
            </div>
            <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Create Request</button>
            <a href="./view_lab_request.php">View Request</a>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
