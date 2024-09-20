<?php
// Include the database connection
require_once "../../config/db.php";

// Handle form submission and PRG pattern
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $_SESSION["form_submitted"] = true; // Mark form as submitted

    // Retrieve form input data
    $patient_name = $_POST["patient_name"];
    $invoice_amount = $_POST["invoice_amount"];
    $additional_info = $_POST["additional_info"];

    if (!empty($patient_name)) {
        // Get patient ID based on full name
        $get_patient_id_sql = "SELECT id FROM patients WHERE fullname = ?";
        $get_patient_id_stmt = mysqli_prepare($connection, $get_patient_id_sql);
        mysqli_stmt_bind_param($get_patient_id_stmt, "s", $patient_name);
        mysqli_stmt_execute($get_patient_id_stmt);
        mysqli_stmt_bind_result($get_patient_id_stmt, $patient_id);
        mysqli_stmt_fetch($get_patient_id_stmt);
        mysqli_stmt_close($get_patient_id_stmt);

        if ($patient_id) {
            // Insert the invoice into the database
            $insert_invoice_sql = "INSERT INTO invoices (patient_id, invoice_amount, invoice_date, invoice_time, additional_info) VALUES (?, ?, ?, ?, ?)";
            $insert_invoice_stmt = mysqli_prepare($connection, $insert_invoice_sql);

            $invoice_date = date("Y-m-d"); // Get the current date
            $invoice_time = date("H:i:s"); // Get the current time

            mysqli_stmt_bind_param($insert_invoice_stmt, "idsss", $patient_id, $invoice_amount, $invoice_date, $invoice_time, $additional_info);

            if (mysqli_stmt_execute($insert_invoice_stmt)) {
                $_SESSION["success_message"] = "Invoice inserted successfully for $patient_name.";
            } else {
                $_SESSION["error_message"] = "Error inserting invoice: " . mysqli_error($connection);
            }

            mysqli_stmt_close($insert_invoice_stmt);
        } else {
            $_SESSION["error_message"] = "Patient with name $patient_name not found.";
        }
    } else {
        $_SESSION["error_message"] = "Patient name is required.";
    }

    echo '<script>window.location.href = window.location.href;</script>';
    exit();
}

// Display success or error messages if set
$success_message = isset($_SESSION["success_message"]) ? $_SESSION["success_message"] : "";
$error_message = isset($_SESSION["error_message"]) ? $_SESSION["error_message"] : "";

// Clear session messages
unset($_SESSION["success_message"]);
unset($_SESSION["error_message"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-4">Create Invoice</h2>

        <?php if (!empty($success_message)) : ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php elseif (!empty($error_message)) : ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="patient_name" class="form-label">Patient Name:</label>
                <input type="text" class="form-control" name="patient_name" required>
            </div>

            <div class="mb-3">
                <label for="invoice_amount" class="form-label">Invoice Amount:</label>
                <input type="number" class="form-control" name="invoice_amount" required>
            </div>

            <div class="mb-3">
                <label for="additional_info" class="form-label">Additional Info</label>
                <textarea name="additional_info" class="form-control" required></textarea>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Create Invoice</button>
            </div>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>