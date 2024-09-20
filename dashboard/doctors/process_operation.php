<?php
require_once "../../config/db.php";



if (!isset($_SESSION["id"])) {
    echo "You are not logged in.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patient_email = $_POST["patient_email"];
    $doctor_id = $_SESSION["id"];
    $operation_name = $_POST["operation_name"];
    $operation_date = $_POST["operation_date"];
    $operation_time = $_POST["operation_time"];
    $operation_notes = $_POST["operation_notes"];
    $status = "pending";

    // Check if the patient email exists in the database and get the patient ID
    $check_patient_sql = "SELECT id FROM patients WHERE email = ?";
    $check_patient_stmt = mysqli_prepare($connection, $check_patient_sql);

    if ($check_patient_stmt) {
        mysqli_stmt_bind_param($check_patient_stmt, "s", $patient_email);
        mysqli_stmt_execute($check_patient_stmt);
        mysqli_stmt_store_result($check_patient_stmt);

        if (mysqli_stmt_num_rows($check_patient_stmt) > 0) {
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
                    header("Location: doctorsdashboard.php"); // Redirect after successful submission
                    exit();
                } else {
                    echo "Error: " . mysqli_error($connection);
                }
                mysqli_stmt_close($insert_stmt);
            } else {
                echo "Error: Unable to prepare the insert statement.";
            }
        } else {
            echo "Error: Patient with the provided email not found in the database.";
        }

        mysqli_stmt_close($check_patient_stmt);
    } else {
        echo "Error: Unable to prepare the check patient statement.";
    }
}

?>
