<?php
// Include the database connection
require_once "../../config/db.php"; // Update the path to your database configuration

// Function to delete an invoice by ID
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_sql = "DELETE FROM invoices WHERE id = ?";
    $delete_stmt = mysqli_prepare($connection, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, "i", $delete_id);
    mysqli_stmt_execute($delete_stmt);
}
// Pagination variables
$results_per_page_6 = 5; // Number of results per page_6
$current_page_6 = isset($_GET['page_6']) ? $_GET['page_6'] : 1; // Current page_6 number

// Fetch invoice data with corresponding patient names
$select_sql = "SELECT invoices.id, invoices.patient_id, patients.fullname AS patient_name, invoice_amount, invoice_date, invoice_time 
               FROM invoices
               INNER JOIN patients ON invoices.patient_id = patients.id
               LIMIT ?, ?";
$select_stmt = mysqli_prepare($connection, $select_sql);
$offset = ($current_page_6 - 1) * $results_per_page_6;
mysqli_stmt_bind_param($select_stmt, "ii", $offset, $results_per_page_6);
mysqli_stmt_execute($select_stmt);
$result = mysqli_stmt_get_result($select_stmt);

// Count total invoices
$count_sql = "SELECT COUNT(*) AS total FROM invoices";
$count_result = mysqli_query($connection, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_results = $count_row['total'];
$total_page_6s = ceil($total_results / $results_per_page_6);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Invoices</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-3">View Invoices</h2>

        <!-- Search Form -->
        <form class="mb-3" id="searchForm">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Search by Patient Name">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </div>
        </form>

        <!-- Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Invoice Amount</th>
                    <th>Invoice Date</th>
                    <th>Invoice Time</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="invoicesTable">
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['patient_name']; ?></td>
                        <td><?php echo $row['invoice_amount']; ?></td>
                        <td><?php echo $row['invoice_date']; ?></td>
                        <td><?php echo $row['invoice_time']; ?></td>
                        <td>
                            <button class="btn btn-danger delete-btn" data-id="<?php echo $row['id']; ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Invoices Pagination">
            <ul class="pagination justify-content-center">
                <?php for ($page_6 = 1; $page_6 <= $total_page_6s; $page_6++) : ?>
                    <li class="page_6-item<?php echo ($current_page_6 == $page_6) ? ' active' : ''; ?>">
                        <a class="page-link rounded-3" href="?page_6=<?php echo $page_6; ?>"><?php echo $page_6; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <script>
        // Add event listener for the search input
        document.getElementById('searchForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#invoicesTable tr');

            rows.forEach(row => {
                const patientName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                
                if (patientName.includes(searchValue)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Add event listener to delete buttons
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const invoiceId = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this invoice?')) {
                    // Send a post request to delete the invoice
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '', true); // Update the URL for the delete script
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function () {
                        if (xhr.status === 200) {
                            // Reload the page_6 after successful deletion
                            location.reload();
                        }
                    };
                    xhr.send(`delete_id=${invoiceId}`);
                }
            });
        });
    </script>
</body>

</html>
