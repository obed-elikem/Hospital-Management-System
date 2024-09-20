<?php

require_once "../../config/db.php";

// session_start(); // Start session

if (!isset($_SESSION["id"])) {
    exit();
}

$doctor_id = $_SESSION["id"];

function createAppointment($connection, $doctor_id, $patient_name, $appointment_date, $appointment_time)
{
    $check_patient_sql = "SELECT id FROM patients WHERE fullname = ?";
    $check_patient_stmt = mysqli_prepare($connection, $check_patient_sql);

    if ($check_patient_stmt) {
        mysqli_stmt_bind_param($check_patient_stmt, "s", $patient_name);
        mysqli_stmt_execute($check_patient_stmt);
        mysqli_stmt_store_result($check_patient_stmt);

        if (mysqli_stmt_num_rows($check_patient_stmt) > 0) {
            mysqli_stmt_bind_result($check_patient_stmt, $patient_id);
            mysqli_stmt_fetch($check_patient_stmt);
            mysqli_stmt_close($check_patient_stmt);

            $insert_sql = "INSERT INTO appointments (doctor_id, patient_id, appointment_date, appointment_time, status)
                           VALUES (?, ?, ?, ?, 'Scheduled')";
            $insert_stmt = mysqli_prepare($connection, $insert_sql);

            if ($insert_stmt) {
                mysqli_stmt_bind_param($insert_stmt, "iiss", $doctor_id, $patient_id, $appointment_date, $appointment_time);

                if (mysqli_stmt_execute($insert_stmt)) {
                    $appointment_created = true;
                } else {
                    $appointment_created = false;
                }

                mysqli_stmt_close($insert_stmt);
            } else {
                echo "Error: Unable to prepare the appointment insertion statement.";
            }
        } else {
            echo "Error: Patient with name $patient_name is not registered.";
        }
    } else {
        echo "Error: Unable to prepare the patient checking statement.";
    }

    return isset($appointment_created) ? "Appointment created successfully!" : "Error creating appointment.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["create_appointment"])) {
        $patient_name = $_POST["patient_name"];
        $appointment_date = $_POST["appointment_date"];
        $appointment_time = $_POST["appointment_time"];
        $create_appointment_message = createAppointment($connection, $doctor_id, $patient_name, $appointment_date, $appointment_time);

        // Store the message in session
        $_SESSION["create_appointment_message"] = $create_appointment_message;

        // Redirect after form submission to prevent resubmission on page refresh
        echo '<script>window.location.href = window.location.href;</script>';
        exit();
    }

    if (isset($_POST["update_status"])) {
        $appointment_id = $_POST["appointment_id"];
        $status = $_POST["status"];
        $update_status_message = updateAppointmentStatus($connection, $appointment_id, $status);

        // Store the message in session
        $_SESSION["update_status_message"] = $update_status_message;

        // Redirect after form submission to prevent resubmission on page refresh
        echo '<script>window.location.href = window.location.href;</script>';
        exit();
    }

    mysqli_close($connection);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <!-- Create New Appointment Form -->
        <div class="card">
            <div class="card-header">
                <h3>Create New Appointment</h3>
            </div>
            <div class="card-body">
                <form action="#" method="post">
                    <div class="mb-3">
                        <label for="patient_name" class="form-label">Patient Name:</label>
                        <input type="text" name="patient_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="appointment_date" class="form-label">Appointment Date:</label>
                            <input type="date" name="appointment_date" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="appointment_time" class="form-label">Appointment Time:</label>
                            <input type="time" name="appointment_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" name="create_appointment" class="btn btn-primary">Create Appointment</button>
                        <a href="./view_appointments.php">View Appointment List</a>
                    </div>
                </form>
                <?php if (isset($_SESSION["create_appointment_message"])) : ?>
                    <div class="mt-3">
                        <div class="alert <?php echo (strpos($_SESSION["create_appointment_message"], 'successfully') !== false) ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                            <?php echo $_SESSION["create_appointment_message"]; ?>
                        </div>
                    </div>
                    <?php unset($_SESSION["create_appointment_message"]); ?>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>