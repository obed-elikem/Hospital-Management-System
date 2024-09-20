<?php
// Include the database connection
require_once "../../config/db.php";

// Start the session
// session_start();
$doctor_id = $_SESSION["id"];

// Check if the form for adding a new patient operation is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_operation"])) {
    $patient_name = $_POST["patient_name"];
    $operation_name = $_POST["operation_name"];
    $operation_date = $_POST["operation_date"];
    $operation_time = $_POST["operation_time"];
    $operation_notes = $_POST["operation_notes"];
    $status = "pending"; // Default status value is "pending"

    // Check if the patient name exists in the database and get the patient ID
    $check_patient_sql = "SELECT id FROM patients WHERE fullname = ?";
    $check_patient_stmt = mysqli_prepare($connection, $check_patient_sql);

    if ($check_patient_stmt) {
        mysqli_stmt_bind_param($check_patient_stmt, "s", $patient_name);
        mysqli_stmt_execute($check_patient_stmt);
        mysqli_stmt_store_result($check_patient_stmt);

        if (mysqli_stmt_num_rows($check_patient_stmt) > 0) {
            // Patient exists in the database, get the patient ID
            mysqli_stmt_bind_result($check_patient_stmt, $patient_id);
            mysqli_stmt_fetch($check_patient_stmt);

            // Proceed to insert the operation record
            $insert_sql = "INSERT INTO operations (patient_id, doctor_id, operation_name, operation_date, operation_time, operation_notes, status)
                           VALUES (?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($connection, $insert_sql);

            if ($insert_stmt) {
                mysqli_stmt_bind_param($insert_stmt, "iisssss", $patient_id, $doctor_id, $operation_name, $operation_date, $operation_time, $operation_notes, $status);
                if (mysqli_stmt_execute($insert_stmt)) {
                    // Operation record added successfully
                } else {
                    echo "Error: " . mysqli_error($connection);
                }
                mysqli_stmt_close($insert_stmt);
            } else {
                echo "Error: Unable to prepare the insert statement.";
            }
        } else {
            echo "Error: Patient with the provided name not found in the database.";
        }

        mysqli_stmt_close($check_patient_stmt);
    } else {
        echo "Error: Unable to prepare the check patient statement.";
    }
    mysqli_close($connection);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Operations</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Add any additional CSS stylesheets here -->
</head>

<body>
    <div class="container mt-5">

        <!-- Form for adding a new patient operation record -->
        <h2>Add New Patient Operation</h2>
        <form action="" method="post">
            <div class="mb-3">
                <label for="patient_name" class="form-label">Patient Name:</label>
                <input type="text" class="form-control" name="patient_name" required>
            </div>
            <div class="mb-3">
                <label for="operation_name" class="form-label">Operation Name:</label>
                <input type="text" class="form-control" name="operation_name" required>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="operation_date" class="form-label">Date:</label>
                    <input type="date" class="form-control" name="operation_date" required>
                </div>
                <div class="col-md-6">
                    <label for="operation_time" class="form-label">Time:</label>
                    <input type="time" class="form-control" name="operation_time" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="operation_notes" class="form-label">Notes:</label>
                <textarea class="form-control" name="operation_notes" required></textarea>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" name="add_operation" class="btn btn-primary">Add Operation</button>
                <a href="./view_operations.php">View Made Operations</a>
            </div>
        </form>

    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>