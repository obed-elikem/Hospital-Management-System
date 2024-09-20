<?php

require_once "../../config/db.php";
session_start();

$doctor_id = $_SESSION["id"];

$itemsPerPage = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View_Created_Report</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Operation Reports</h2>
        <input type="text" class="form-control mb-3" id="searchInput" placeholder="Search by Patient Name">
        <?php if (mysqli_num_rows($result) > 0) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Report Notes</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $row['patient_name']; ?></td>
                            <td><?php echo $row['report_notes']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td><?php echo $row['report_date']; ?></td>
                            <td><?php echo $row['report_time']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php
                    $totalPages = ceil(mysqli_num_rows(mysqli_query($connection, "SELECT * FROM operation_reports WHERE doctor_id = $doctor_id")) / $itemsPerPage);
                    for ($i = 1; $i <= $totalPages; $i++) :
                    ?>
                        <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php else : ?>
            <p>No operation reports found.</p>
        <?php endif; ?>
        <p class="text-end"><a href="./doctorsdashboard.php" class="btn btn-secondary">Go back</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById("searchInput").addEventListener("input", function() {
            var filter = this.value.toUpperCase();
            var rows = document.querySelectorAll("tbody tr");

            rows.forEach(function(row) {
                var patientName = row.querySelector("td:first-child").textContent.toUpperCase();
                if (patientName.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    </script>
</body>

</html>