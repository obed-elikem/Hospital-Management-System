<?php
// Include the database connection
require_once "../../config/db.php";

// Start the session
session_start();
$doctor_id = $_SESSION["id"];

// Pagination settings
$itemsPerPage = 5; // Number of items per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page

// Fetch patient operation records with doctor's operations
$sql = "SELECT o.id, p.fullname AS patient_name,
               o.operation_name, o.operation_date, o.operation_time, o.operation_notes, o.status 
        FROM operations o
        INNER JOIN patients p ON o.patient_id = p.id
        WHERE o.doctor_id = ?
        ORDER BY o.operation_date DESC, o.operation_time DESC
        LIMIT $itemsPerPage OFFSET " . (($page - 1) * $itemsPerPage);


$result = mysqli_prepare($connection, $sql);

if ($result) {
    mysqli_stmt_bind_param($result, "i", $doctor_id);
    mysqli_stmt_execute($result);
    $result = mysqli_stmt_get_result($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Operation Made</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Add any additional CSS stylesheets here -->
</head>

<body>
    <div class="container mt-5">
        <?php if ($result && mysqli_num_rows($result) > 0) : ?>
            <h2 class="mb-4">Doctor's Patient Operation Records</h2>
            <input type="text" class="form-control mb-3" id="searchInput" placeholder="Search by Patient Name">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Operation Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Notes</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $row['patient_name']; ?></td>
                            <td><?php echo $row['operation_name']; ?></td>
                            <td><?php echo $row['operation_date']; ?></td>
                            <td><?php echo $row['operation_time']; ?></td>
                            <td><?php echo $row['operation_notes']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php
                    $totalPages = ceil(mysqli_num_rows(mysqli_query($connection, "SELECT * FROM operations WHERE doctor_id = $doctor_id")) / $itemsPerPage);
                    for ($i = 1; $i <= $totalPages; $i++) :
                    ?>
                        <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php else : ?>
            <p>No patient operation records found.</p>
        <?php endif; ?>
        <p class="text-end"><a href="./doctorsdashboard.php" class="btn btn-secondary">Go back</a></p>
    </div>

    <!-- Add Bootstrap JS -->
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
