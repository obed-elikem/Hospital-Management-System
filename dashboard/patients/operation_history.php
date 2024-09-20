<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Operations</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h3>Patient Operations</h3>

        <?php
        // Start the session
        // session_start();

        // Check if the patient's ID is available in the session
        if (!isset($_SESSION["id"])) {
            // Redirect to the login page if not logged in
            // header("Location: ./login.php");
            exit();
        }

        // Include the database connection
        require_once "../../config/db.php";

        // Get the patient's ID from the session
        $patient_id = $_SESSION["id"];

        // Fetch the patient's email based on their ID
        $get_patient_email_sql = "SELECT email FROM patients WHERE id = '$patient_id'";
        $get_patient_email_result = mysqli_query($connection, $get_patient_email_sql);
        $patient_email_row = mysqli_fetch_assoc($get_patient_email_result);
        $patient_email = $patient_email_row['email'];

        // Pagination settings
        $itemsPerPage = 7; // Number of items per page
        $page = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page

        // Calculate the offset for pagination
        $offset = ($page - 1) * $itemsPerPage;

        // Fetch patient operation records with patient and doctor names
        $sql = "SELECT o.patient_email, p.fullname AS patient_name, o.doctor_id, d.fullname AS doctor_name, 
                       o.report_notes, o.status, o.report_date, o.report_time 
                FROM operation_reports o
                INNER JOIN patients p ON o.patient_email = p.email
                INNER JOIN doctors d ON o.doctor_id = d.id
                WHERE o.patient_email = '$patient_email'
                ORDER BY o.report_date DESC, o.report_time DESC
                LIMIT $itemsPerPage OFFSET $offset";

        $result = mysqli_query($connection, $sql);

        // Count total items for pagination
        $totalItemsSql = "SELECT COUNT(o.id) AS total FROM operation_reports o
                INNER JOIN patients p ON o.patient_email = p.email
                INNER JOIN doctors d ON o.doctor_id = d.id
                WHERE o.patient_email = '$patient_email'";
        $totalItemsResult = mysqli_query($connection, $totalItemsSql);
        $totalItemsRow = mysqli_fetch_assoc($totalItemsResult);
        $totalItems = $totalItemsRow['total'];
        $totalPages = ceil($totalItems / $itemsPerPage);

        ?>

        <?php if (mysqli_num_rows($result) > 0) : ?>
            <!-- Appointments Table -->
            <table class="table" id="dataTable">
                <thead>
                    <tr>
                        <th>Doctor Name</th>
                        <th>Report Notes</th>
                        <th>Status</th>
                        <th>Report Date</th>
                        <th>Report Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $row['doctor_name']; ?></td>
                            <td><?php echo $row['report_notes']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['report_date']; ?></td>
                            <td><?php echo $row['report_time']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php else : ?>
            <p>No patient operation records found.</p>
        <?php endif; ?>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
