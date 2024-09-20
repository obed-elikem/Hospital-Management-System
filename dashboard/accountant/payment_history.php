<?php
// Include the database connection
require_once "../../config/db.php";

// Fetch payment history with patient names from the payments and patients tables
$records_per_page = 5;

if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $current_page = $_GET['page'];
} else {
    $current_page = 1;
}

$offset = ($current_page - 1) * $records_per_page;

$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Modify the query based on whether the search input is empty
if (empty($search)) {
    $payment_history_sql = "SELECT p.fullname AS patient_name, pa.amount, pa.payment_method, pa.payment_date, pa.payment_time, pa.additional_info
                            FROM payments pa
                            JOIN patients p ON pa.patient_id = p.id
                            LIMIT $offset, $records_per_page";
} else {
    $payment_history_sql = "SELECT p.fullname AS patient_name, pa.amount, pa.payment_method, pa.payment_date, pa.payment_time, pa.additional_info
                            FROM payments pa
                            JOIN patients p ON pa.patient_id = p.id
                            WHERE p.fullname LIKE '%$search%'
                            LIMIT $offset, $records_per_page";
}

$payment_history_result = mysqli_query($connection, $payment_history_sql);

// Check if the query was successful
if (!$payment_history_result) {
    echo "Error retrieving payment history: " . mysqli_error($connection);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment History</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Payment History</h2>

        <!-- Search Box -->
        <form id="searchForm" method="GET" class="mb-4">
            <input type="hidden" name="timestamp" value="<?php echo time(); ?>">
            <div class="input-group">
                <input type="text" class="form-control" name="search" placeholder="Search by patient name" value="<?php echo $search; ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <?php if (mysqli_num_rows($payment_history_result) > 0) : ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Patient Name</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Date</th>
                        <th>Payment Time</th>
                        <th>Additional Info</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($payment_history_result)) : ?>
                        <tr>
                            <td><?php echo $row["patient_name"]; ?></td>
                            <td><?php echo $row["amount"]; ?></td>
                            <td><?php echo $row["payment_method"]; ?></td>
                            <td><?php echo $row["payment_date"]; ?></td>
                            <td><?php echo $row["payment_time"]; ?></td>
                            <td><?php echo $row["additional_info"]; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination d-flex justify-content-center">
                    <?php
                    $total_records_sql = "SELECT COUNT(*) FROM payments pa JOIN patients p ON pa.patient_id = p.id WHERE p.fullname LIKE '%$search%'";
                    $total_records_result = mysqli_query($connection, $total_records_sql);
                    $total_records = mysqli_fetch_array($total_records_result)[0];
                    $total_pages = ceil($total_records / $records_per_page);

                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active_class = ($i === $current_page) ? "active" : "";
                        echo "<li class='page-item $active_class'><a class='page-link' href='?page=$i&search=$search'>$i</a></li>";
                    }
                    ?>
                </ul>
            </nav>
        <?php else : ?>
            <p>No payment history found.</p>
        <?php endif; ?>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Include this script at the end of the <body> section -->
    <script>
        // Clear search input field on page load if there is no search query
        window.onload = function() {
            var timestamp = <?php echo isset($_GET['timestamp']) ? $_GET['timestamp'] : 0; ?>;
            if (timestamp === 0) {
                document.getElementById("searchForm").reset();
            }
        };
    </script>

</body>

</html>
