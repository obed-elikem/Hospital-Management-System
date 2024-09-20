<?php

require_once "../../config/db.php";

session_start(); // Start session


$doctor_id = $_SESSION["id"];

function updateAppointmentStatus($connection, $appointment_id, $status)
{
    $update_sql = "UPDATE appointments SET status = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($connection, $update_sql);

    if ($update_stmt) {
        mysqli_stmt_bind_param($update_stmt, "si", $status, $appointment_id);

        if (mysqli_stmt_execute($update_stmt)) {
            $appointment_updated = true;
        } else {
            $appointment_updated = false;
        }

        mysqli_stmt_close($update_stmt);
    } else {
        echo "Error: Unable to prepare the appointment update statement.";
    }

    return isset($appointment_updated) ? "Appointment status updated successfully!" : "Error updating appointment status.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
}

$appointments_sql = "SELECT a.id AS appointment_id, a.appointment_date, a.appointment_time, p.fullname AS patient_name, a.status
                    FROM appointments a
                    INNER JOIN patients p ON a.patient_id = p.id
                    WHERE a.doctor_id = '$doctor_id'
                    ORDER BY a.appointment_date ASC, a.appointment_time ASC";

$appointments_result = mysqli_query($connection, $appointments_sql);

$appointments_per_page = 5;
$total_appointments = mysqli_num_rows($appointments_result);
$total_pages = ceil($total_appointments / $appointments_per_page);

$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($current_page - 1) * $appointments_per_page;
$paginated_appointments_sql = "$appointments_sql LIMIT $offset, $appointments_per_page";
$paginated_appointments_result = mysqli_query($connection, $paginated_appointments_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointment List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <!-- Manage Appointments Table -->
    <div class="mt-4 container">
        <h3>Manage Appointments</h3>
        <input type="text" id="searchInput" class="form-control" placeholder="Search by Patient Name">
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Date and Time</th>
                    <th>Status</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($appointment = mysqli_fetch_assoc($paginated_appointments_result)) : ?>
                    <tr>
                        <td><?php echo $appointment["patient_name"]; ?></td>
                        <td><?php echo $appointment["appointment_date"] . ' ' . $appointment["appointment_time"]; ?></td>
                        <td><?php echo $appointment["status"]; ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="appointment_id" value="<?php echo $appointment["appointment_id"]; ?>">
                                <select name="status" class="form-select">
                                    <option value="Scheduled">Scheduled</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Canceled">Canceled</option>
                                </select>
                                <div class="my-1 d-flex justify-content-end">
                                    <button type="submit" name="update_status" class="btn btn-secondary">Update</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php if (isset($_SESSION["update_status_message"])) : ?>
            <div class="mt-3">
                <div class="alert <?php echo (strpos($_SESSION["update_status_message"], 'successfully') !== false) ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                    <?php echo $_SESSION["update_status_message"]; ?>
                </div>
            </div>
            <?php unset($_SESSION["update_status_message"]); ?>
        <?php endif; ?>

        <div>
            <!-- Pagination -->
            <nav aria-label="Appointments Pagination">
                <ul class="pagination justify-content-center">
                    <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
                        <li class="page-item<?php echo ($current_page == $page) ? ' active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <p class="text-end"><a href="./doctorsdashboard.php" class="btn btn-secondary">Go back</a></p>

        </div>
    </div>


    <!-- Bootstrap and JavaScript imports -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Search Functionality -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("searchInput");
            const tableBody = document.querySelector(".table tbody");
            const originalTableContent = tableBody.innerHTML;

            searchInput.addEventListener("input", function() {
                const searchText = searchInput.value.toLowerCase();
                tableBody.innerHTML = originalTableContent;

                Array.from(tableBody.rows).forEach(function(row) {
                    const patientName = row.cells[0].textContent.toLowerCase();
                    if (!patientName.includes(searchText)) {
                        row.style.display = "none";
                    }
                });
            });
        });
    </script>
</body>

</html>