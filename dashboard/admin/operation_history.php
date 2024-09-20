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
        <h1>Patient Operations</h1>

        <!-- Search Bar -->
        <div class="input-group">
            <input type="text" class="form-control" id="searchInput" placeholder="Search by Patient Name">
            <button class="btn btn-outline-primary" type="button" id="searchButton">Search</button>
        </div>

        <?php
        // Include the database connection
        require_once "../../config/db.php";

        // Check if a delete request was made
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_id"])) {
            $deleteId = $_POST["delete_id"];

            $deleteSql = "DELETE FROM operation_reports WHERE id = '$deleteId'";
            $deleteResult = mysqli_query($connection, $deleteSql);

            if (!$deleteResult) {
                echo "Error deleting record: " . mysqli_error($connection);
            }
        }

        // Pagination settings
        $itemsPerPage_4 = 7; // Number of items per page_4
        $page_4 = isset($_GET['page_4']) ? $_GET['page_4'] : 1; // Get current page_4

        // Calculate the offset for pagination
        $offset = ($page_4 - 1) * $itemsPerPage_4;

        // Fetch patient operation records with patient and doctor names
        $sql = "SELECT o.id, p.fullname AS patient_name, d.fullname AS doctor_name, 
                       o.report_notes, o.status, o.report_date, o.report_time 
                FROM operation_reports o
                INNER JOIN patients p ON o.patient_email = p.email
                INNER JOIN doctors d ON o.doctor_id = d.id
                ORDER BY o.report_date DESC, o.report_time DESC
                LIMIT $itemsPerPage_4 OFFSET $offset";

        $result = mysqli_query($connection, $sql);

        // Count total items for pagination
        $totalItemsSql = "SELECT COUNT(o.id) AS total FROM operation_reports o
INNER JOIN patients p ON o.patient_email = p.email
INNER JOIN doctors d ON o.doctor_id = d.id";
        $totalItemsResult = mysqli_query($connection, $totalItemsSql);
        $totalItemsRow = mysqli_fetch_assoc($totalItemsResult);
        $totalItems = $totalItemsRow['total'];
        $totalPage_4s = ceil($totalItems / $itemsPerPage_4);

        ?>

        <?php if (mysqli_num_rows($result) > 0) : ?>
            <div class="tableContainer">
                <table class="table" id="dataTable">
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Doctor Name</th>
                            <th>Report Notes</th>
                            <th>Status</th>
                            <th>Report Date</th>
                            <th>Report Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                            <tr>
                                <td><?php echo $row['patient_name']; ?></td>
                                <td><?php echo $row['doctor_name']; ?></td>
                                <td><?php echo $row['report_notes']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td><?php echo $row['report_date']; ?></td>
                                <td><?php echo $row['report_time']; ?></td>
                                <td>
                                    <button class="btn btn-danger delete-btn" data-id="<?php echo $row['id']; ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>

                    </tbody>
                <?php endwhile; ?>

                </table>
                <!-- Pagination -->
                <nav aria-label="Page_4 navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPage_4s; $i++) : ?>
                            <li class="page_4-item <?php echo $page_4 == $i ? 'active' : ''; ?>">
                                <a class="page-link rounded-3" href="?page_4=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>


        <?php else : ?>
            <p>No patient operation records found.</p>
        <?php endif; ?>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Function to fetch and update page content
        function fetchPage(pageNumber) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `?page_4=${pageNumber}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const tableContainer = document.getElementById('tableContainer');
                    tableContainer.innerHTML = xhr.responseText;
                    attachDeleteEventListeners();
                }
            };
            xhr.send();
        }

        // Attach event listeners to pagination links
        const paginationLinks = document.querySelectorAll('.page_4-item a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                const pageNumber = parseInt(link.textContent);
                fetchPage(pageNumber);
            });
        });

        // Function to filter table rows based on search input
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("dataTable");
            tr = table.getElementsByTagName("tr");

            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0]; // Assuming the name is in the first column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        // Add event listener to delete buttons
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const reportId = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this record?')) {
                    // Send a post request to delete the record
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '', true);
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            // Reload the page_4 after successful deletion
                            location.reload();
                        }
                    };
                    xhr.send(`delete_id=${reportId}`);
                }
            });
        });
    </script>
</body>

</html>