<?php
// Include the database connection
require_once "../../config/db.php";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate the input data
    $patient_fullname = $_POST["patient_fullname"];
    $temperature = $_POST["temperature"];
    $blood_pressure = $_POST["blood_pressure"];
    $heart_rate = $_POST["heart_rate"];
    $weight = $_POST["weight"];
    $height = $_POST["height"];
    $sugar_level = $_POST["sugar_level"];
    $record_date = $_POST["record_date"];

    // Check if the patient is registered in the database
    $check_patient_sql = "SELECT id FROM patients WHERE fullname = ?";
    $check_patient_stmt = mysqli_prepare($connection, $check_patient_sql);
    mysqli_stmt_bind_param($check_patient_stmt, "s", $patient_fullname);
    mysqli_stmt_execute($check_patient_stmt);
    mysqli_stmt_store_result($check_patient_stmt);

    if (mysqli_stmt_num_rows($check_patient_stmt) > 0) {
        // Get the patient ID from the database
        mysqli_stmt_bind_result($check_patient_stmt, $patient_id);
        mysqli_stmt_fetch($check_patient_stmt);

        // Insert the vital information into the database
        $insert_sql = "INSERT INTO vital_information (patient_id, temperature, blood_pressure, heart_rate, weight, height, sugar_level, record_date)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $insert_stmt = mysqli_prepare($connection, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "isssssss", $patient_id, $temperature, $blood_pressure, $heart_rate, $weight, $height, $sugar_level, $record_date);

        if (mysqli_stmt_execute($insert_stmt)) {
            // Success message or redirect to a success page
            $success_message = "Information saved successfully!";
            header("Location: nursedashboard.php?success_message=$success_message");
        } else {
            $error_message = "Error: " . mysqli_error($connection);
            header("Location: nursedashboard.php?error_message=$error_message");
            exit();
        }

        // Close the statement
        mysqli_stmt_close($insert_stmt);
    } else {
        // Patient not registered in the database
        $error_patient = "Error: Patient with fullname $patient_fullname is not registered.";
            header("Location: nursedashboard.php?error_message=$error_patient");
            exit();
    }

    // Close the statement
    mysqli_stmt_close($check_patient_stmt);
}

// Close the connection to the database
mysqli_close($connection);
?>
