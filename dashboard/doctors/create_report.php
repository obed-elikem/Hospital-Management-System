<?php
require_once "../../config/db.php";
session_start();

$doctor_id = $_SESSION["id"];

$itemsPerPage = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patient_name = $_POST["patient_name"];
    $report_notes = $_POST["report_notes"];
    $status = $_POST["status"];
    $report_date = $_POST["report_date"];
    $report_time = $_POST["report_time"];

    $check_patient_sql = "SELECT email FROM patients WHERE fullname = ?";
    $check_patient_stmt = mysqli_prepare($connection, $check_patient_sql);

    if ($check_patient_stmt) {
        mysqli_stmt_bind_param($check_patient_stmt, "s", $patient_name);
        mysqli_stmt_execute($check_patient_stmt);
        mysqli_stmt_store_result($check_patient_stmt);
        mysqli_stmt_bind_result($check_patient_stmt, $patient_email);
        mysqli_stmt_fetch($check_patient_stmt);
        mysqli_stmt_close($check_patient_stmt);

        if ($patient_email) {
            $insert_sql = "INSERT INTO operation_reports (patient_email, doctor_id, report_notes, status, report_date, report_time)
                        VALUES (?, ?, ?, ?, ?, ?)";
            $insert_stmt = mysqli_prepare($connection, $insert_sql);

            if ($insert_stmt) {
                mysqli_stmt_bind_param($insert_stmt, "sissss", $patient_email, $doctor_id, $report_notes, $status, $report_date, $report_time);
                if (mysqli_stmt_execute($insert_stmt)) {
                    echo "<meta http-equiv='refresh' content='0'>";
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
    } else {
        echo "Error: Unable to prepare the check patient statement.";
    }
    // Redirect after form submission to prevent resubmission on page refresh
    echo '<script>window.location.href = window.location.href;</script>';
    exit();
}


$sql = "SELECT r.patient_email, d.fullname AS doctor_name, 
               p.fullname AS patient_name, r.report_notes, r.status, r.report_date, r.report_time 
        FROM operation_reports r
        INNER JOIN doctors d ON r.doctor_id = d.id
        INNER JOIN patients p ON r.patient_email = p.email
        WHERE r.doctor_id = ?
        LIMIT $itemsPerPage OFFSET " . (($page - 1) * $itemsPerPage);

$result = mysqli_prepare($connection, $sql);

if ($result) {
    mysqli_stmt_bind_param($result, "i", $doctor_id);
    mysqli_stmt_execute($result);
    $result = mysqli_stmt_get_result($result);

    if (!$result) {
        echo "Error: " . mysqli_error($connection);
    }
} else {
    echo "Error: Unable to prepare the query.";
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operation Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Operation Reports</h2>

        <form action="" method="post">
            <div class="row">
                <div class="col-md-6">
                    <label for="patient_name">Patient Name:</label>
                    <input type="text" class="form-control" name="patient_name" required>
                </div>
                <div class="col-md-6">
                    <label for="status">Status:</label>
                    <select class="form-control" name="status" required>
                        <option value="successful">Successful</option>
                        <option value="unsuccessful">Unsuccessful</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label for="report_notes">Report Notes:</label>
                <textarea class="form-control" name="report_notes" required></textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label for="report_date">Report Date:</label>
                    <input type="date" class="form-control" name="report_date" required>
                </div>
                <div class="col-md-6">
                    <label for="report_time">Report Time:</label>
                    <input type="time" class="form-control" name="report_time" required>
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button type="submit" name="add_report" class="btn btn-primary mt-3">Add Report</button>
                <a href="./view_created_report.php">View Report</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>