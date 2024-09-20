<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <?php
    // Include the database connection
    require_once "../../config/db.php";

    // Assuming you have a session variable for patient ID and email
    $patient_id = $_SESSION["id"];
    // $patient_email = $_SESSION["email"];

    // Get invoice information for the patient
    $get_invoice_sql = "SELECT id, invoice_amount, invoice_date FROM invoices WHERE patient_id = ? ORDER BY invoice_date DESC";
    $get_invoice_stmt = mysqli_prepare($connection, $get_invoice_sql);
    mysqli_stmt_bind_param($get_invoice_stmt, "i", $patient_id);

    // Execute the query and handle errors
    if (mysqli_stmt_execute($get_invoice_stmt)) {
        mysqli_stmt_bind_result($get_invoice_stmt, $invoice_id, $invoice_amount, $invoice_date);

        // Fetch all invoices and store in an array
        $invoices = [];
        while (mysqli_stmt_fetch($get_invoice_stmt)) {
            $invoices[] = [
                "id" => $invoice_id,
                "amount" => $invoice_amount,
                "date" => $invoice_date
            ];
        }

        // Display invoices or not found message
        if (!empty($invoices)) {
            echo "<h3>Invoices</h3>";
            echo "<div class='table-responsive'>";
            echo "<table class='table table-striped table-bordered'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Invoice ID</th>";
            echo "<th>Invoice Amount</th>";
            echo "<th>Invoice Date</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            // Pagination
            $items_per_page = 5;
            $total_pages = ceil(count($invoices) / $items_per_page);
            $current_page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;

            $start = ($current_page - 1) * $items_per_page;
            $end = min($start + $items_per_page, count($invoices));

            for ($i = $start; $i < $end; $i++) {
                echo "<tr>";
                echo "<td>{$invoices[$i]['id']}</td>";
                echo "<td>{$invoices[$i]['amount']}</td>";
                echo "<td>{$invoices[$i]['date']}</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";

            // Pagination links
            echo "<nav aria-label='Page navigation'>";
            echo "<ul class='pagination justify-content-center'>";
            for ($page = 1; $page <= $total_pages; $page++) {
                echo "<li class='page-item " . ($current_page == $page ? 'active' : '') . "'>";
                echo "<a class='page-link' href='?page=$page'>$page</a>";
                echo "</li>";
            }
            echo "</ul>";
            echo "</nav>";

            echo "</div>";
        } else {
            echo "<p class='text-danger'>Invoices not found.</p>";
        }

        mysqli_stmt_close($get_invoice_stmt);
    } else {
        echo "Error executing query: " . mysqli_error($connection);
    }
    ?>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
