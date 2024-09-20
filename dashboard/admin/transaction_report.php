<?php
// Include the database connection
require_once "../../config/db.php"; // Update the path to your database configuration

// Function to delete a payment by ID
// Function to delete an invoice by ID
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_sql = "DELETE FROM payments WHERE id = ?";
    $delete_stmt = mysqli_prepare($connection, $delete_sql);
    mysqli_stmt_bind_param($delete_stmt, "i", $delete_id);
    mysqli_stmt_execute($delete_stmt);
}

// Pagination variables
$results_per_page_5 = 5; // Number of results per page_5
$current_page_5 = isset($_GET['page_5']) ? $_GET['page_5'] : 1; // Current page_5 number

// Fetch payment data with corresponding patient names
$select_sql = "SELECT payments.id, payments.patient_id, patients.fullname AS patient_name, amount, payment_method, payment_time, payment_date, additional_info 
               FROM payments
               INNER JOIN patients ON payments.patient_id = patients.id
               LIMIT ?, ?";
$select_stmt = mysqli_prepare($connection, $select_sql);
$offset = ($current_page_5 - 1) * $results_per_page_5;
mysqli_stmt_bind_param($select_stmt, "ii", $offset, $results_per_page_5);
mysqli_stmt_execute($select_stmt);
$result = mysqli_stmt_get_result($select_stmt);

// Count total payments
$count_sql = "SELECT COUNT(*) AS total FROM payments";
$count_result = mysqli_query($connection, $count_sql);
$count_row = mysqli_fetch_assoc($count_result);
$total_results = $count_row['total'];
$total_page_5s = ceil($total_results / $results_per_page_5);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Report</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Add Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mb-3">Transaction Report</h2>

        <!-- Search Form -->
        <form class="mb-3" id="searchForm">
            <div class="input-group">
                <input type="text" class="form-control" id="searchInput" placeholder="Search by Patient Name">
                <button class="btn btn-outline-primary" type="button" id="searchButton">Search</button>
            </div>
        </form>

        <!-- Table -->
        <table class="table">
            <thead>
                <tr>
                    <th>Patient Name</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Payment Time</th>
                    <th>Payment Date</th>
                    <th>Additional Info</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="paymentsTable">
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['patient_name']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['payment_method']; ?></td>
                        <td><?php echo $row['payment_time']; ?></td>
                        <td><?php echo $row['payment_date']; ?></td>
                        <td><?php echo $row['additional_info']; ?></td>
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
        <nav aria-label="Payments Pagination">
            <ul class="pagination justify-content-center">
                <?php for ($page_5 = 1; $page_5 <= $total_page_5s; $page_5++) : ?>
                    <li class="page_5-item<?php echo ($current_page_5 == $page_5) ? ' active' : ''; ?>">
                        <a class="page-link rounded-3" href=" ?page_5=<?php echo $page_5; ?>"><?php echo $page_5; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <script>
        // Add event listener to delete buttons
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const paymentId = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this payment?')) {
                    // Send a post request to delete the payment
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '', true); // Update the URL for the delete script
                    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                    xhr.onload = function() {
                        if (xhr.status === 200) {
                            // Reload the page_5 after successful deletion
                            location.reload();
                        }
                    };
                    xhr.send(`delete_id=${paymentId}`);
                }
            });
        });


        // Add event listener for the search input
        document.getElementById('searchForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('#paymentsTable tr');

            rows.forEach(row => {
                const patientName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const doctorName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                if (patientName.includes(searchValue) || doctorName.includes(searchValue)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>