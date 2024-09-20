<?php
// Include the database connection
require_once "../../config/db.php";

// Pagination
$items_per_page = 5;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

// Fetch the lab requests for the doctor from the database with pagination
$sql = "SELECT lr.id AS request_id, d.fullname AS doctor_name, p.fullname AS patient_name, lr.test_name, lr.status
        FROM lab_requests AS lr
        INNER JOIN doctors AS d ON lr.doctor_id = d.id
        INNER JOIN patients AS p ON lr.patient_id = p.id
        ORDER BY lr.id DESC
        LIMIT $offset, $items_per_page";

$result = mysqli_query($connection, $sql);

// Fetch total number of lab requests
$total_requests_sql = "SELECT COUNT(*) AS total_requests FROM lab_requests";
$total_requests_result = mysqli_query($connection, $total_requests_sql);
$total_requests_row = mysqli_fetch_assoc($total_requests_result);
$total_requests = $total_requests_row['total_requests'];

// Calculate total pages for pagination
$total_pages = ceil($total_requests / $items_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Requests</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-3">Lab Requests</h1>

        <!-- Search form -->
        <form class="mb-3">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search by patient name" id="searchInput">
                <button class="btn btn-outline-secondary" type="button" id="searchButton">Search</button>
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Patient Name</th>
                    <th>Test Name</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if there are any lab requests
                if (mysqli_num_rows($result) > 0) {
                    while ($request = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>' . $request["doctor_name"] . '</td>';
                        echo '<td>' . $request["patient_name"] . '</td>';
                        echo '<td>' . $request["test_name"] . '</td>';
                        echo '<td>' . $request["status"] . '</td>';
                        echo '<td><a href="lab_feedback.php?request_id=' . $request["request_id"] . '" class="btn btn-primary">Send Feedback</a></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5">No lab requests found.</td></tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Lab Requests Pagination">
            <ul class="pagination justify-content-center">
                <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
                    <li class="page-item<?php echo ($current_page == $page) ? ' active' : ''; ?>">
                        <a class="page-link" href="?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Search functionality
        document.getElementById("searchButton").addEventListener("click", function () {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.querySelector("table");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Change index to the desired column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        });
    </script>
</body>

</html>
