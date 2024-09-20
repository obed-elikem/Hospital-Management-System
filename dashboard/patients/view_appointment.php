<?php
// Include the database connection
require_once "../../config/db.php";

if (!isset($_SESSION["id"])) {
    // Redirect to the patient login page if not logged in
    // header("Location: ./patient_login.php");
    exit();
}

// Get the patient's ID from the session
$patient_id = $_SESSION["id"];

// Pagination variables
$items_per_page = 5;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Fetch appointments with doctor's name for the logged-in patient from the appointment table
$appointments_sql = "SELECT a.*, d.fullname AS doctor_name
                    FROM appointments a
                    INNER JOIN doctors d ON a.doctor_id = d.id
                    WHERE a.patient_id = '$patient_id'
                    ORDER BY appointment_date DESC, appointment_time DESC
                    LIMIT $offset, $items_per_page";
$appointments_result = mysqli_query($connection, $appointments_sql);

// Get total number of appointments for pagination
$total_appointments_sql = "SELECT COUNT(*) as total_appointments FROM appointments WHERE patient_id = '$patient_id'";
$total_appointments_result = mysqli_query($connection, $total_appointments_sql);
$total_appointments_row = mysqli_fetch_assoc($total_appointments_result);
$total_appointments = $total_appointments_row['total_appointments'];
$total_pages = ceil($total_appointments / $items_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h3>Your Appointments</h3>

        <!-- Appointments Table -->
        <table class="table table-responsive">
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display appointments in the table
                while ($appointment = mysqli_fetch_assoc($appointments_result)) {
                    echo '<tr>';
                    echo '<td>' . $appointment["doctor_name"] . '</td>';
                    echo '<td>' . $appointment["appointment_date"] . '</td>';
                    echo '<td>' . $appointment["appointment_time"] . '</td>';
                    echo '<td>' . $appointment["status"] . '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php
                for ($page = 1; $page <= $total_pages; $page++) {
                    echo '<li class="page-item"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
                }
                ?>
            </ul>
        </nav>
    </div>

    <!-- Add Bootstrap JavaScript and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
