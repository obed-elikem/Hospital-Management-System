<?php
require_once "../../config/db.php";

// Start the session
session_start();

// After successful authentication, replace "doctor_id" with the actual column name in the doctors table for the ID
$doctor_id = $_SESSION["id"]; // Replace with the actual ID retrieved from the login process

// Check if the doctor's ID is available in the session
if (!isset($_SESSION["id"])) {
    // Redirect to the doctor's login page if not logged in
    // header("Location: ./doctor_login.php");
    exit();
}

// Get the doctor's ID from the session (make sure the doctor is logged in)
$doctor_id = $_SESSION["id"];

// Pagination settings
$itemsPerPage = 5; // Number of items per page
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page

// Fetch prescriptions for the current doctor
$sql = "SELECT p.id, pat.fullname AS patient_name, p.medication, p.dosage, p.cost
        FROM prescriptions p
        INNER JOIN patients pat ON p.patient_id = pat.id
        WHERE p.doctor_id = ?
        LIMIT $itemsPerPage OFFSET " . (($page - 1) * $itemsPerPage);

$result = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($result, "i", $doctor_id);
mysqli_stmt_execute($result);
$result = mysqli_stmt_get_result($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Prescriptions</h2>
        <input type="text" class="form-control mb-3" id="searchInput" placeholder="Search by Patient Name">
        <?php if (mysqli_num_rows($result) > 0) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Medication</th>
                        <th>Dosage</th>
                        <th>Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo $row['patient_name']; ?></td>
                            <td><?php echo $row['medication']; ?></td>
                            <td><?php echo $row['dosage']; ?></td>
                            <td><?php echo $row['cost']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php
                    $totalPages = ceil(mysqli_num_rows(mysqli_query($connection, "SELECT id FROM prescriptions WHERE doctor_id = $doctor_id")) / $itemsPerPage);
                    for ($i = 1; $i <= $totalPages; $i++) :
                    ?>
                        <li class="page-item <?php if ($i == $page) echo 'active'; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php else : ?>
            <p>No prescriptions found.</p>
        <?php endif; ?>

        <p class="text-end"><a href="./doctorsdashboard.php" class="btn btn-secondary">Go back</a></p>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Add search functionality -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("#searchInput").addEventListener("input", function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll(".table tbody tr");

                rows.forEach(row => {
                    const patientName = row.querySelector("td:nth-child(2)").textContent.toLowerCase();
                    if (patientName.includes(searchTerm)) {
                        row.style.display = "table-row";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        });
    </script>
</body>

</html>
