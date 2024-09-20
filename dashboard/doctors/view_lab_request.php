<?php
// Include the database connection

require_once "../../config/db.php";

// Start the session
session_start();

// Check if the doctor is logged in
if (!isset($_SESSION["id"])) {
    // Redirect to doctor login page or show an error message
    // header("Location: ./doctor_login.php");
    exit();
}

// Get the doctor ID from the session
$doctor_id = $_SESSION["id"];

// Pagination variables
$results_per_page = 5; // Number of results per page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number

// Count total lab requests
$count_sql = "SELECT COUNT(id) AS total FROM lab_requests WHERE doctor_id = ?";
$count_stmt = mysqli_prepare($connection, $count_sql);
mysqli_stmt_bind_param($count_stmt, "i", $doctor_id);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$row = mysqli_fetch_assoc($count_result);
$total_results = $row['total'];

// Calculate total pages
$total_pages = ceil($total_results / $results_per_page);

// Calculate offset for pagination
$offset = ($current_page - 1) * $results_per_page;

// Fetch lab requests for the logged-in doctor
$select_sql = "SELECT lab_requests.id, patients.fullname AS patient_name, lab_requests.test_name, lab_requests.request_date, lab_requests.status, lab_requests.test_results
               FROM lab_requests
               INNER JOIN patients ON lab_requests.patient_id = patients.id
               WHERE lab_requests.doctor_id = ?
               ORDER BY lab_requests.request_date DESC
               LIMIT $offset, $results_per_page";

$select_stmt = mysqli_prepare($connection, $select_sql);
mysqli_stmt_bind_param($select_stmt, "i", $doctor_id);
mysqli_stmt_execute($select_stmt);
$result = mysqli_stmt_get_result($select_stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Lab Requests</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-3">View Lab Requests</h2>

        <!-- Search Form -->
        <form class="mb-3" method="get">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by Patient Name">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </div>
        </form>

        <!-- Table -->
        <!-- ... other HTML code ... -->

        <table class="table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Test Name</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Test Results</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['patient_name']; ?></td>
                        <td><?php echo $row['test_name']; ?></td>
                        <td><?php echo $row['request_date']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['test_results']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>


        <nav aria-label="Lab Requests Pagination">
            <ul class="pagination justify-content-center">
                <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
                    <li class="page-item<?php echo ($current_page == $page) ? ' active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>

        <p class="text-end"><a href="./doctorsdashboard.php" class="btn btn-secondary">Go back</a></p>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            // Add event listener for the search input
            document.querySelector("form").addEventListener("submit", function(event) {
                event.preventDefault(); // Prevent form submission

                const searchValue = document.querySelector("input[name='search']").value.toLowerCase();
                const rows = document.querySelectorAll("tbody tr");

                rows.forEach(row => {
                    const patientName = row.querySelector("td:nth-child(1)").textContent.toLowerCase();
                    if (patientName.includes(searchValue)) {
                        row.style.display = "table-row";
                    } else {
                        row.style.display = "none";
                    }
                });
            });
        </script>

</body>

</html>