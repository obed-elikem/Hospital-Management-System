<?php
// Start the session
// session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
require_once "../../config/db.php";

// Check if the patient is logged in
if (!isset($_SESSION["id"])) {
    // Redirect to the patient login page if not logged in
    // header("Location: ./patient_login.php");
    exit();
}

// Get the patient's ID from the session (make sure the patient is logged in)
$patient_id = $_SESSION["id"];

// Number of prescriptions per page
$prescriptions_per_page = 5;

// Fetch total number of prescriptions for the patient
$total_prescriptions_sql = "SELECT COUNT(*) as total FROM prescriptions WHERE patient_id = $patient_id";
$total_prescriptions_result = mysqli_query($connection, $total_prescriptions_sql);
$total_prescriptions = mysqli_fetch_assoc($total_prescriptions_result)['total'];

// Calculate total pages
$total_pages = ceil($total_prescriptions / $prescriptions_per_page);

// Get current page from query parameter
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for fetching prescriptions
$offset = ($current_page - 1) * $prescriptions_per_page;

// Fetch prescriptions for the patient from the prescriptions table with pagination
$prescriptions_sql = "SELECT * FROM prescriptions WHERE patient_id = $patient_id ORDER BY created_at DESC LIMIT $offset, $prescriptions_per_page";
$prescriptions_result = mysqli_query($connection, $prescriptions_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Prescriptions</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h3>View Prescriptions</h3>

        <!-- Search Form -->
        <form class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Search by Medication">
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>Medication</th>
                    <th>Dosage</th>
                    <th>Cost</th> <!-- Add the cost column header -->
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display existing prescriptions in the table
                if (mysqli_num_rows($prescriptions_result) > 0) {
                    while ($prescription = mysqli_fetch_assoc($prescriptions_result)) {
                        echo '<tr>';
                        echo '<td>' . $prescription["medication"] . '</td>';
                        echo '<td>' . $prescription["dosage"] . '</td>';
                        echo '<td>' . $prescription["cost"] . '</td>'; // Display the cost
                        echo '<td>' . $prescription["created_at"] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4">No prescriptions found.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Prescriptions Pagination">
            <ul class="pagination">
                <?php
                for ($page = 1; $page <= $total_pages; $page++) {
                    echo "<li class='page-item" . ($current_page == $page ? ' active' : '') . "'>
                            <a class='page-link' href='?page=$page'>$page</a>
                        </li>";
                }
                ?>
            </ul>
        </nav>
    </div>

    <!-- Add your Bootstrap and JavaScript imports here -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("searchInput");
            const tableRows = document.querySelectorAll('.table tbody tr');

            searchInput.addEventListener("input", function () {
                const searchText = searchInput.value.toLowerCase().trim();

                tableRows.forEach(function (row) {
                    const medicationColumn = row.querySelector('td:nth-child(1)');
                    const medication = medicationColumn.textContent.toLowerCase();

                    if (medication.includes(searchText)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        });
    </script>
</body>

</html>
