<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacist Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">

        <!-- Add Medicine Category Form -->
        <h2>Add Medicine Category</h2>
        <?php
        require_once "../../config/db.php"; // Include your database connection here

        error_reporting(E_ALL);
        ini_set('display_errors', 1);


        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_category"])) {
            $category_name = $_POST["category_name"];
            $sql = "INSERT INTO medicine_categories (name) VALUES (?)";
            $stmt = mysqli_prepare($connection, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $category_name);
                if (mysqli_stmt_execute($stmt)) {
                    echo '<div class="alert alert-success">Medicine category added successfully.</div>';
                } else {
                    echo '<div class="alert alert-danger">Error adding medicine category: ' . mysqli_error($connection) . '</div>';
                }
                mysqli_stmt_close($stmt);
            }
        }
        ?>
        <form action="" method="post">
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name:</label>
                <input type="text" class="form-control" name="category_name" required>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" name="add_category" class="btn btn-primary">Add Category</button>
            </div>
        </form>

        <!-- Add Medicine Stock Form -->
        <h2>Add Medicine Stock</h2>
        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_stock"])) {
            $medicine_name = $_POST["medicine_name"];
            $category_id = $_POST["category_id"];
            $quantity = $_POST["quantity"];

            $sql_insert_medicine = "INSERT INTO medicines (name, category_id, quantity) VALUES (?, ?, ?)";
            $stmt_insert_medicine = mysqli_prepare($connection, $sql_insert_medicine);

            if ($stmt_insert_medicine) {
                mysqli_stmt_bind_param($stmt_insert_medicine, "sii", $medicine_name, $category_id, $quantity); // Bind values properly
                if (mysqli_stmt_execute($stmt_insert_medicine)) {
                    $_SESSION["success_message"] = "Medicine added successfully.";
                    echo '<script>window.location.href = window.location.href;</script>'; // Redirect using JavaScriptresubmission
                    exit();
                } else {
                    $_SESSION["error_message"] = "Error adding medicine: " . mysqli_error($connection);
                    echo '<script>window.location.href = window.location.href;</script>'; // Redirect using JavaScriptresubmission
                    exit();
                }
                mysqli_stmt_close($stmt_insert_medicine);
            }
        }

        // Display success or error messages
        if (isset($_SESSION["success_message"])) {
            echo '<div class="alert alert-success">' . $_SESSION["success_message"] . '</div>';
            unset($_SESSION["success_message"]); // Clear the message
        }
        if (isset($_SESSION["error_message"])) {
            echo '<div class="alert alert-danger">' . $_SESSION["error_message"] . '</div>';
            unset($_SESSION["error_message"]); // Clear the message
        }
        ?>

        <form action="" method="post">
            <div class="mb-3">
                <label for="medicine_name" class="form-label">Medicine Name:</label>
                <input type="text" class="form-control" name="medicine_name" required>
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Category:</label>
                <select class="form-control" name="category_id" required>
                    <option value="" selected disabled>Select Category</option>
                    <?php
                    $sql_categories = "SELECT * FROM medicine_categories";
                    $result_categories = mysqli_query($connection, $sql_categories);
                    while ($row = mysqli_fetch_assoc($result_categories)) {
                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity:</label>
                <input type="number" class="form-control" name="quantity" required>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" name="add_stock" class="btn btn-primary">Add Stock</button>
            </div>
        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>