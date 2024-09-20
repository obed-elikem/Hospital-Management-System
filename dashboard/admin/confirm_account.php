<?php
// Include the database connection
require_once "../../config/db.php";

// Check if the admin is logged in
if (!isset($_SESSION["id"])) {
    // Redirect to admin login page if not logged in
    // header("Location: ./admin_login.php");
    exit();
}

// Fetch list of registered staff members and their account status
$staff_roles = array("doctors", "pharmacists", "nurses", "accountants", "laboratorists", "patients");

// Handle Account Confirmation
if (isset($_GET["confirm"]) && isset($_GET["role"])) {
    $staff_id = $_GET["confirm"];
    $staff_role = $_GET["role"];

    // Update the staff member's account status to "confirmed"
    $update_sql = "UPDATE $staff_role SET status = 'confirmed' WHERE id = ?";
    $update_stmt = mysqli_prepare($connection, $update_sql);

    if ($update_stmt) {
        mysqli_stmt_bind_param($update_stmt, "i", $staff_id);
        if (mysqli_stmt_execute($update_stmt)) {
            // Output a success message
            echo "Confirmed";
            exit();
        } else {
            echo "Error: " . mysqli_error($connection);
        }
        mysqli_stmt_close($update_stmt);
    } else {
        echo "Error: Unable to prepare the update statement.";
    }
}

// Handle Account Deletion
if (isset($_GET["delete"]) && isset($_GET["role"])) {
    $staff_id = $_GET["delete"];
    $staff_role = $_GET["role"];

    // Delete the staff member's row from the table
    $delete_sql = "DELETE FROM $staff_role WHERE id = ?";
    $delete_stmt = mysqli_prepare($connection, $delete_sql);

    if ($delete_stmt) {
        mysqli_stmt_bind_param($delete_stmt, "i", $staff_id);
        if (mysqli_stmt_execute($delete_stmt)) {
            // Output a success message
            echo "Deleted";
            exit();
        } else {
            echo "Error: " . mysqli_error($connection);
        }
        mysqli_stmt_close($delete_stmt);
    } else {
        echo "Error: Unable to prepare the delete statement.";
    }
}


?>

<!-- ... (previous code) ... -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>

    </style>


</head>

<body>



    <!-- ... (previous code) ... -->

    <?php foreach ($staff_roles as $role) : ?>
        <?php
        // Get the columns names of the current table
        $columns = array();
        $query = "SHOW COLUMNS FROM $role";
        $result = mysqli_query($connection, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            $column_name = $row['Field'];
            if ($column_name !== 'id' && $column_name !== 'password' && $column_name !== 'date_of_birth' && $column_name !== 'address' && $column_name !== 'location'  && $column_name !== 'status') {
                $columns[] = $column_name;
            }
        }
        $columns[] = 'status'; // Add 'status' to the columns array

        ?>

        <h2>Registered <?php echo ucfirst($role); ?></h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <!-- ... (previous code) ... -->

                <thead>
                    <tr>
                        <?php foreach ($columns as $column) : ?>
                            <th><?php echo ucfirst($column); ?></th>
                        <?php endforeach; ?>
                        <th>Actions</th> <!-- Change the column header to "Actions" -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT " . implode(", ", $columns) . ", id, status FROM $role"; // Include 'id' and 'status'
                    $result = mysqli_query($connection, $sql);

                    while ($row = mysqli_fetch_assoc($result)) :
                    ?>
                        <tr>
                            <?php foreach ($columns as $column) : ?>
                                <td><?php echo $row[$column]; ?></td>
                            <?php endforeach; ?>
                            <td class="d-flex justify-content-evenly">
                                <?php if (strtolower($row['status']) !== 'confirmed') : ?>
                                    <a href="#" class="confirm-link" data-id="<?php echo $row['id']; ?>" data-role="<?php echo $role; ?>">
                                        <i class="fas fa-check-circle text-success"></i> <!-- Confirm icon -->
                                    </a>
                                <?php endif; ?>
                                <a href="#" class="delete-link" data-id="<?php echo $row['id']; ?>" data-role="<?php echo $role; ?>">
                                    <i class="fas fa-trash-alt text-danger"></i> <!-- Delete icon -->
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                </tbody>

                <!-- ... (other code) ... -->

            </table>
        </div>
    <?php endforeach; ?>

    <!-- ... (other code) ... -->



    <script>
        // Add event listeners to the Confirm links
        var confirmLinks = document.querySelectorAll('.confirm-link');
        confirmLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                var staffId = link.getAttribute('data-id');
                var staffRole = link.getAttribute('data-role');

                // Send AJAX request to update status
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '?confirm=' + staffId + '&role=' + staffRole, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Update the link to a confirmed icon
                        link.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
                        link.classList.remove('confirm-link'); // Remove the class to prevent further clicks
                    }
                };
                xhr.send();
            });
        });

        var deleteLinks = document.querySelectorAll('.delete-link');
    deleteLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            var staffId = link.getAttribute('data-id');
            var staffRole = link.getAttribute('data-role');

            // Send AJAX request to delete row
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '?delete=' + staffId + '&role=' + staffRole, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Remove the row from the table
                    var row = link.closest('tr');
                    row.remove();

                    // Update the index numbers in the remaining rows
                    var tableRows = document.querySelectorAll('tbody tr');
                    tableRows.forEach(function(row, index) {
                        var cells = row.getElementsByTagName('td');
                        cells[0].textContent = index + 1; // Update index number
                    });
                }
            };
            xhr.send();
        });
    });
    </script>


    <!-- ... (other code) ... -->

</body>

</html>