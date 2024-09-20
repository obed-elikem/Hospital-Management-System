<?php
// Include the database connection
require_once "../../config/db.php";

// Start the session
// session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// After successful authentication, replace "doctor_id" with the actual column name in the doctors table for the ID
$doctor_id = $_SESSION["id"]; // Replace with the actual ID retrieved from the login process

// Check if the doctor's ID is available in the session
if (!isset($_SESSION["id"])) {
    // Redirect to the doctor's login page if not logged in
    // header("Location: ./doctor_login.php");
    exit();
}

// Get the doctor's ID from the session (make sure the doctor is logged in)
$doctor_id = $_SESSION["id"];

// Function to create a new prescription
function createPrescription($connection, $doctor_id, $patient_name, $medication, $dosage, $cost)
{
    // Check if the patient's name exists in the database
    $check_patient_sql = "SELECT id FROM patients WHERE fullname = ?";
    $check_patient_stmt = mysqli_prepare($connection, $check_patient_sql);

    if ($check_patient_stmt) {
        mysqli_stmt_bind_param($check_patient_stmt, "s", $patient_name);
        mysqli_stmt_execute($check_patient_stmt);
        mysqli_stmt_store_result($check_patient_stmt);

        if (mysqli_stmt_num_rows($check_patient_stmt) > 0) {
            // Get the patient ID from the database
            mysqli_stmt_bind_result($check_patient_stmt, $patient_id);
            mysqli_stmt_fetch($check_patient_stmt);

            // Close the statement
            mysqli_stmt_close($check_patient_stmt);

            // Insert the prescription details into the "prescriptions" table
            $insert_sql = "INSERT INTO prescriptions (doctor_id, patient_id, medication, dosage, cost)
                           VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($connection, $insert_sql);

            if ($insert_stmt) {
                mysqli_stmt_bind_param($insert_stmt, "iisss", $doctor_id, $patient_id, $medication, $dosage, $cost);

                if (mysqli_stmt_execute($insert_stmt)) {
                    // Success message or redirect to a success page
                    echo "Prescription created successfully!";
                } else {
                    // Error message or redirect to an error page
                    echo "Error: " . mysqli_error($connection);
                }

                // Close the statement
                mysqli_stmt_close($insert_stmt);
            } else {
                echo "Error: Unable to prepare the prescription insertion statement.";
            }
        } else {
            // Patient name not registered in the database
            echo "Error: Patient with name $patient_name is not registered.";
        }
    } else {
        echo "Error: Unable to prepare the patient checking statement.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["create_prescription"])) {
    $patient_name = $_POST["patient_name"];
    $medication = $_POST["medication"];
    $dosage = $_POST["dosage"];
    $cost = $_POST["cost"]; // Add the cost field

    createPrescription($connection, $doctor_id, $patient_name, $medication, $dosage, $cost); // Pass the cost to the function
}

// Pagination settings
$itemsPerPage = 5; // Number of items per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page

// Fetch prescriptions for the current doctor
$sql = "SELECT p.id, pat.fullname AS patient_name, p.medication, p.dosage, p.cost
        FROM prescriptions p
        INNER JOIN patients pat ON p.patient_id = pat.id
        WHERE p.doctor_id = ?
        LIMIT $itemsPerPage OFFSET " . (($page - 1) * $itemsPerPage);

$result = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($result, "i", $doctor_id);
mysqli_stmt_execute($result);
$result = mysqli_stmt_get_result($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-3">Create Prescription</h1>
        <form action="" method="post">
            
                <div class="mb-3">
                    <label for="patient_name" class="form-label">Patient Name:</label>
                    <input type="text" class="form-control" name="patient_name" required>
                </div>
                <div class="mb-3">
                    <label for="medication" class="form-label">Medication:</label>
                    <textarea class="form-control" name="medication" required></textarea>
                </div>
                <div class="row">
                <div class="mb-3 col-6">
                    <label for="dosage" class="form-label">Dosage:</label>
                    <textarea class="form-control" name="dosage" required></textarea>
                </div>
                <div class="mb-3 col-6">
                    <label for="cost" class="form-label">Cost:</label>
                    <input type="number" class="form-control" name="cost" required>
                </div>
                </div>
                <div class="d-flex justify-content-between">
                <button type="submit" name="create_prescription" class="btn btn-primary">Create Prescription</button>
                <a href="./view_prescription.php">View Prescription</a>
                </div>
            
        </form>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Add search functionality -->
    
</body>

</html>
