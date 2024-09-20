<?php
require_once "../../config/db.php"; // Replace with your actual path

// Initialize variables
$message = '';

// Handle quantity updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $bloodGroupToUpdate = $_POST['blood_group'];
    $updateQuantity = intval($_POST['update_quantity']);

    $updateQuery = "UPDATE blood_bank SET quantity = ? WHERE blood_group = ?";
    $updateStmt = mysqli_prepare($connection, $updateQuery);

    if ($updateStmt) {
        mysqli_stmt_bind_param($updateStmt, "is", $updateQuantity, $bloodGroupToUpdate);
        if (mysqli_stmt_execute($updateStmt)) {
            $message = "Quantity updated successfully.";
        } else {
            $message = "Error updating quantity: " . mysqli_error($connection);
        }
    }
}

// Fetch data from the blood_bank table
$selectQuery = "SELECT * FROM blood_bank";
$result = mysqli_query($connection, $selectQuery);
$rows = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blood Bank Dashboard</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="mt-3">Blood Bank Records</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Blood Group</th>
                    <th>Quantity</th>
                    <th>Update Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo $row['blood_group']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="blood_group" value="<?php echo $row['blood_group']; ?>">
                                <input type="number" name="update_quantity" value="0">
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if ($message): ?>
            <div class="alert alert-success mt-3"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
