<?php
// Include the database connection
require_once "../../config/db.php";

// Fetch the list of confirmed doctors from the database
$doctors_sql = "SELECT * FROM doctors WHERE status = 'confirmed'";
$doctors_result = mysqli_query($connection, $doctors_sql);

// Check if there are any confirmed doctors in the database
if (mysqli_num_rows($doctors_result) > 0) {
    // Display the list of confirmed doctors in a table
    echo "<div class='container mt-5'>";
    echo "<h3>List of Confirmed Doctors</h3>";

    // Search Form
    echo "<form class='mb-3' method='get'>";
    echo "<div class='input-group'>";
    echo "<input type='text' class='form-control' name='search' placeholder='Search by Name'>";
    echo "</div>";
    echo "</form>";

    // Table
    echo "<table class='table table-bordered'>";
    echo "<thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Specialization</th>
            </tr>
        </thead>
        <tbody>";

    // Pagination
    $doctors_per_page = 5; // Number of doctors per page
    $total_doctors = mysqli_num_rows($doctors_result); // Total number of doctors
    $total_pages = ceil($total_doctors / $doctors_per_page); // Calculate total pages

    $current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Current page number
    $offset = ($current_page - 1) * $doctors_per_page; // Calculate offset

    // Fetch doctors with pagination
    $doctors_paginated_sql = "SELECT * FROM doctors WHERE status = 'confirmed' LIMIT $offset, $doctors_per_page";
    $doctors_paginated_result = mysqli_query($connection, $doctors_paginated_sql);

    while ($row = mysqli_fetch_assoc($doctors_paginated_result)) {
        echo "<tr>";
        echo "<td>" . $row["fullname"] . "</td>";
        echo "<td>" . $row["email"] . "</td>";
        echo "<td>" . $row["specialization"] . "</td>";
        echo "</tr>";
    }

    echo "</tbody>
        </table>";

    // Pagination Links
    echo "<nav aria-label='Doctors Pagination'>
            <ul class='pagination justify-content-center'>";
    for ($page = 1; $page <= $total_pages; $page++) {
        echo "<li class='page-item" . ($current_page == $page ? ' active' : '') . "'>
                <a class='page-link' href='?page=$page'>$page</a>
            </li>";
    }
    echo "</ul>
        </nav>";

    echo "</div>"; // Closing container
} else {
    echo "No confirmed doctors found in the database.";
}

// Close the database connection
// mysqli_close($connection);
?>

<!-- Add Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<!-- JavaScript for Search Functionality -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector('input[name="search"]');
        const tableRows = document.querySelectorAll('.table tbody tr');

        searchInput.addEventListener("input", function() {
            const searchText = searchInput.value.toLowerCase().trim();

            tableRows.forEach(function(row) {
                const nameColumn = row.querySelector('td:nth-child(2)');
                const name = nameColumn.textContent.toLowerCase();

                if (name.includes(searchText)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    });
</script>