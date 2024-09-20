<?php
// Include the database connection
require_once "../../config/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patient_name = $_POST["patient_name"];
    $bed_number = $_POST["bed_number"];
    $ward = $_POST["ward"];

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

        // Insert the allocated bed and ward information into the bed_ward_allocation table
        $insert_sql = "INSERT INTO bed_ward_allocation (patient_id, bed_number, ward, allocation_date) VALUES (?, ?, ?, NOW())";
        $insert_stmt = mysqli_prepare($connection, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "iss", $patient_id, $bed_number, $ward);

        if (mysqli_stmt_execute($insert_stmt)) {
            // Set success message
            $success_message = "Bed and ward allocated successfully!";
            header("Location: nursedashboard.php?success_message=$success_message");
            exit();
        } else {
            // Set error message
            $error_message = "Error: " . mysqli_error($connection);
            header("Location: nursedashboard.php?error_message=$error_message");
            exit();
        }

        // Close the statement
        mysqli_stmt_close($insert_stmt);
    } else {
        // Patient not registered in the database
        $error_message = "Error: Patient with name $patient_name is not registered.";
        header("Location: nursedashboard.php?error_message=$error_message");
        exit();
    }

    // Close the statement
    mysqli_stmt_close($check_patient_stmt);
}
?>
