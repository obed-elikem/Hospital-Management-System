<?php
require_once "../../../config/db.php";
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Administrator</title>

  <!-- Importing styles -->

  <link rel="stylesheet" href="../../../assets/styles/d_db_style.css">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/fontawesome.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/brands.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/solid.min.css">



</head>

<body>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 col-lg-2 bg-light">
        <ul class="nav flex-column py-5 my-5 position-fixed">
          <li class="nav-item">
            <a class="nav-link" href="#">
              <i class="bi bi-grid me-2"></i>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../../account/logout.php">
              <i class="bi bi-arrow-right-from-bracket me-2"></i>
              Log Out
            </a>
          </li>
        </ul>
      </div>

      <div class="col-md-9 col-lg-9">
        <main class="main mt-5 pt-5">

          <div class="topbar fixed-top bg-white">
            <button class="btn d-flex align-items-center" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">
              <h2 class=""><i class="pl-5 bi bi-list fs-2"></i><span class="text-black">Ayao</span><span class="text-blue">MED</span></h2>
            </button>
          </div>


          <section class="container">
            <section class="container">
              <?php

              // Include the database connection
              // require_once "../../config/db.php";

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

              $role = "patients";

              // Pagination
              $items_per_page = 5;
              $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
              $offset = ($current_page - 1) * $items_per_page;

              // Get the columns names of the current table
              $columns = array();
              $query = "SHOW COLUMNS FROM $role";
              $result = mysqli_query($connection, $query);
              while ($row = mysqli_fetch_assoc($result)) {
                $column_name = $row['Field'];
                if ($column_name !== 'id' && $column_name !== 'password' && $column_name !== 'date_of_birth' && $column_name !== 'address' && $column_name !== 'location' && $column_name !== 'status') {
                  $columns[] = $column_name;
                }
              }
              $columns[] = 'status'; // Add 'status' to the columns array

              ?>

              <div class="d-flex justify-content-between">
                <h2>Registered <?php echo ucfirst($role); ?></h2>
                <p><a href="../../../account/register.php" class="nav-link"><i class="fas fa-plus fa-md rounded-circle">Add Patient</i></a></p>
              </div>
              <div class="table-responsive">
                <input type="text" class="form-control mb-2" id="searchInput" placeholder="Search by name">
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
                    $sql .= " LIMIT $items_per_page OFFSET $offset";
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

              <!-- Pagination links -->
              <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                  <?php
                  $page_count = ceil(mysqli_num_rows(mysqli_query($connection, "SELECT * FROM $role")) / $items_per_page);
                  for ($i = 1; $i <= $page_count; $i++) :
                  ?>
                    <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                  <?php endfor; ?>
                </ul>
              </nav>

              <!-- ... (other code) ... -->
            </section>


          </section>
        </main>
      </div>


    </div>
  </div>


  <!-- Importing scripts -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

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

    // Add event listener to the search input
    var searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
      var searchText = searchInput.value.toLowerCase();
      var tableRows = document.querySelectorAll('tbody tr');

      tableRows.forEach(function(row) {
        var cells = row.getElementsByTagName('td');
        var shouldDisplay = false;

        for (var i = 0; i < cells.length - 1; i++) { // Exclude the last column (Actions)
          var cellText = cells[i].textContent.toLowerCase();
          if (cellText.includes(searchText)) {
            shouldDisplay = true;
            break;
          }
        }

        row.style.display = shouldDisplay ? '' : 'none';
      });
    });
  </script>
</body>

</html>