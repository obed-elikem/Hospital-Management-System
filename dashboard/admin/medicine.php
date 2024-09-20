<?php
require_once "../../config/db.php"; // Replace with your actual path

// Pagination settings
$recordsPerPage_3 = 8;
$page_3 = isset($_GET['page_3']) ? intval($_GET['page_3']) : 1;

// Fetch data from the medicines table and join with the medicine_categories table
$selectQuery = "
    SELECT medicines.*, medicine_categories.name AS category_name
    FROM medicines
    JOIN medicine_categories ON medicines.category_id = medicine_categories.id
    LIMIT " . (($page_3 - 1) * $recordsPerPage_3) . ", $recordsPerPage_3
";
$result = mysqli_query($connection, $selectQuery);
$rows = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}

// Calculate total number of records for pagination
$totalRecordsQuery = "SELECT COUNT(*) AS total FROM medicines";
$totalRecordsResult = mysqli_query($connection, $totalRecordsQuery);
$totalRecords = 0;

if ($totalRecordsResult) {
    $totalRecordsRow = mysqli_fetch_assoc($totalRecordsResult);
    $totalRecords = $totalRecordsRow['total'];
}

$totalPage_3s = ceil($totalRecords / $recordsPerPage_3);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicines</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mt-3">Medicines</h2>
        <div class="input-group mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by name">
            <button class="btn btn-primary" type="button" id="searchButton">Search</button>
        </div>
        <div class="tableContainer">
            <table class="table">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Medicine Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- PHP loop to populate the table rows -->
                    <?php foreach ($rows as $row) : ?>
                        <tr>
                            <td><?php echo $row['category_name']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <nav aria-label="Page_3 navigation">
                <ul class="pagination">
                    <!-- PHP loop for pagination links -->
                    <?php for ($i = 1; $i <= $totalPage_3s; $i++) : ?>
                        <li class="page_3-item <?php echo $page_3 == $i ? 'active' : ''; ?>">
                            <a class="page-link rounded-3" href="?page_3=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const tableBody = document.getElementById('tableBody');
            const originalTableRows = Array.from(tableBody.getElementsByTagName('tr'));

            searchButton.addEventListener('click', function() {
                const searchText = searchInput.value.toLowerCase();

                originalTableRows.forEach(row => {
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
                });
            });
        });

    </script>
</body>

</html>