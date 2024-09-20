<?php

// Include the database connection
require_once "../../config/db.php"; // Update the path to your database configuration

// Handle row deletion
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_sql = "DELETE FROM appointments WHERE id = ?";
    $delete_stmt = mysqli_prepare($connection, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, "i", $delete_id);
    mysqli_stmt_execute($delete_stmt);
}

// Pagination variables
$results_per_page_1 = 5; // Number of results per page_1
$current_page_1 = isset($_GET['page_1']) ? $_GET['page_1'] : 1; // Current page_1 number

// Fetch appointments with corresponding doctor and patient names
$select_sql = "SELECT appointments.id, appointments.patient_id, patients.fullname AS patient_name, appointments.doctor_id, doctors.fullname AS doctor_name, 
                      appointments.appointment_date, appointments.appointment_time, appointments.status 
               FROM appointments
               INNER JOIN patients ON appointments.patient_id = patients.id
               INNER JOIN doctors ON appointments.doctor_id = doctors.id
               LIMIT ?, ?";
$select_stmt = mysqli_prepare($connection, $select_sql);
$offset = ($current_page_1 - 1) * $results_per_page_1;
mysqli_stmt_bind_param($select_stmt, "ii", $offset, $results_per_page_1);
mysqli_stmt_execute($select_stmt);
$result = mysqli_stmt_get_result($select_stmt);

// Count total appointments
$count_sql = "SELECT COUNT(*) AS total FROM appointments";
$count_result = mysqli_query($connection, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_results = $count_row['total'];
$total_page_1s = ceil($total_results / $results_per_page_1);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Add Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-3">View Appointments</h2>

        <!-- Search Form -->
        <form class="mb-3" id="searchForm">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Search by Doctor or Patient Name">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </div>
        </form>

        <!-- Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="appointmentsTable">
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['patient_name']; ?></td>
                        <td><?php echo $row['doctor_name']; ?></td>
                        <td><?php echo $row['appointment_date']; ?></td>
                        <td><?php echo $row['appointment_time']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td>
                            <button class="btn btn-danger delete-btn" data-id="<?php echo $row['id']; ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Appointments Pagination">
            <ul class="pagination justify-content-center">
                <?php for ($page_1 = 1; $page_1 <= $total_page_1s; $page_1++) : ?>
                    <li class="page_1-item<?php echo ($current_page_1 == $page_1) ? ' active' : ''; ?>">
                        <a class="page-link rounded-3" href="?page_1=<?php echo $page_1; ?>"><?php echo $page_1; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <script>
        // Add event listener for the search input
        document.getElementById('searchForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#appointmentsTable tr');

            rows.forEach(row => {
                const patientName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const doctorName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                if (patientName.includes(searchValue) || doctorName.includes(searchValue)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Add event listener to delete buttons
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const appointmentId = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this appointment?')) {
                    // Send a post request to delete the appointment
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            // Remove the row after successful deletion
                            const row = button.closest('tr');
                            row.parentNode.removeChild(row);
                        }
                    };
                    xhr.send(`delete_id=${appointmentId}`);
                }
            });
        });
    </script>
</body>

</html>


