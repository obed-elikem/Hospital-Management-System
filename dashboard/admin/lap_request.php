<?php
require_once "../../config/db.php"; // Replace with your actual path

// Pagination settings
$recordsPerPage_2 = 5;
$page_2 = isset($_GET['page_2']) ? intval($_GET['page_2']) : 1;

// Fetch data from the lab_requests table and join with the doctors and patients tables
// to get doctor's full name and patient's full name
$selectQuery = "
    SELECT lab_requests.*, doctors.fullname AS doctor_name, patients.fullname AS patient_name
    FROM lab_requests
    JOIN doctors ON lab_requests.doctor_id = doctors.id
    JOIN patients ON lab_requests.patient_id = patients.id
    LIMIT " . (($page_2 - 1) * $recordsPerPage_2) . ", $recordsPerPage_2
";
$result = mysqli_query($connection, $selectQuery);
$rows = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

// Calculate total number of records for pagination
$totalRecordsQuery = "SELECT COUNT(*) AS total FROM lab_requests";
$totalRecordsResult = mysqli_query($connection, $totalRecordsQuery);
$totalRecords = 0;

if ($totalRecordsResult) {
    $totalRecordsRow = mysqli_fetch_assoc($totalRecordsResult);
    $totalRecords = $totalRecordsRow['total'];
}

$totalPage_2s = ceil($totalRecords / $recordsPerPage_2);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Requests</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mt-3">Lab Requests</h2>
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search by name">
        <table class="table">
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Patient Name</th>
                    <th>Test Name</th>
                    <th>Request Date</th>
                    <th>Status</th>
                    <th>Test Results</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo $row['doctor_name']; ?></td>
                        <td><?php echo $row['patient_name']; ?></td>
                        <td><?php echo $row['test_name']; ?></td>
                        <td><?php echo $row['request_date']; ?></td>
                        <td><?php echo $row['status']; ?></td>
                        <td><?php echo $row['test_results']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav aria-label="Page_2 navigation">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPage_2s; $i++) : ?>
                    <li class="page_2-item <?php echo $i === $page_2 ? 'active' : ''; ?>">
                        <a class="page-link rounded-3" href="?page_2=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('tableBody');

        searchInput.addEventListener('input', function() {
            const searchText = searchInput.value.toLowerCase();

            const rows = tableBody.getElementsByTagName('tr');
            for (const row of rows) {
                const cells = row.getElementsByTagName('td');
                let shouldDisplay = false;

                for (const cell of cells) {
                    const text = cell.textContent.toLowerCase();
                    if (text.includes(searchText)) {
                        shouldDisplay = true;
                        break;
                    }
                }

                row.style.display = shouldDisplay ? '' : 'none';
            }
        });
    </script>
</body>

</html>
