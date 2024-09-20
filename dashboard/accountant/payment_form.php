<?php
// session_start();

require_once "../../config/db.php";

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_SESSION['form_submitted'])) {
    $_SESSION['form_submitted'] = true;

    $patient_fullname = $_POST["patient_fullname"];
    $amount = $_POST["amount"];
    $payment_method = $_POST["payment_method"];
    $payment_date = $_POST["payment_date"];
    $payment_time = $_POST["payment_time"]; // New field
    $additional_info = $_POST["additional_info"];

    // Check if the patient with the given full name exists
    $check_patient_sql = "SELECT id, email FROM patients WHERE fullname = ?";
    $check_patient_stmt = mysqli_prepare($connection, $check_patient_sql);
    mysqli_stmt_bind_param($check_patient_stmt, "s", $patient_fullname);
    mysqli_stmt_execute($check_patient_stmt);
    mysqli_stmt_store_result($check_patient_stmt);

    if (mysqli_stmt_num_rows($check_patient_stmt) > 0) {
        mysqli_stmt_bind_result($check_patient_stmt, $patient_id, $patient_email);
        mysqli_stmt_fetch($check_patient_stmt);

        // Insert payment record into the database using the found patient's email and patient_id
        $insert_sql = "INSERT INTO payments (patient_id, patient_email, amount, payment_method, payment_date, payment_time, additional_info)
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = mysqli_prepare($connection, $insert_sql);
        mysqli_stmt_bind_param($insert_stmt, "issssss", $patient_id, $patient_email, $amount, $payment_method, $payment_date, $payment_time, $additional_info);

        if (mysqli_stmt_execute($insert_stmt)) {
            $message = "Payment recorded successfully!";
            $messageType = "success";
        } else {
            $message = "Error: " . mysqli_error($connection);
            $messageType = "error";
        }

        mysqli_stmt_close($insert_stmt);
    } else {
        $message = "Error: Patient with full name $patient_fullname is not registered.";
        $messageType = "error";
    }

    mysqli_stmt_close($check_patient_stmt);
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <div class="container mt-5">
        <h2>Record Payment</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="patient_fullname" class="form-label">Patient Full Name</label>
                <input type="text" class="form-control" id="patient_fullname" name="patient_fullname" required>
            </div>
            <div class="row">
                <div class="mb-3 col-6">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" required>
                </div>
                <div class="mb-3 col-6">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select name="payment_method" class="form-control" required>
                        <option value="Cash">Cash</option>
                        <option value="Electronic">Electronic</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="mb-3 col-6">
                    <label for="payment_date" class="form-label">Payment Date</label>
                    <input type="date" class="form-control" id="payment_date" name="payment_date" required>
                </div>
                <div class="mb-3 col-6">
                    <label for="payment_time" class="form-label">Payment Time</label>
                    <input type="time" class="form-control" id="payment_time" name="payment_time" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="additional_info" class="form-label">Additional Info</label>
                <textarea class="form-control" id="additional_info" name="additional_info" rows="3"></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">Record Payment</button>
            </div>
        </form>
    </div>

    <?php if ($message !== "") : ?>
        <div class="modal fade show" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-modal="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="messageModalLabel">Message</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-<?php echo $messageType; ?>" role="alert">
                            <?php echo $message; ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            var messageModal = new bootstrap.Modal(document.getElementById("messageModal"));
            messageModal.show();
        </script>
    <?php endif; ?>

</body>

</html>
