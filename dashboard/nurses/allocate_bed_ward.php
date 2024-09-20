<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allocate Bed and Ward</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h3>Allocate Bed and Ward</h3>

        <?php
        // Display success message if available
        if (!empty($_GET['success_message'])) {
            echo "<div class='alert alert-success'>{$_GET['success_message']}</div>";
        }

        // Display error message if available
        if (!empty($_GET['error_message'])) {
            echo "<div class='alert alert-danger'>{$_GET['error_message']}</div>";
        }
        ?>

        <form action="./process_bed_ward.php" method="post">
            <div class="mb-3">
                <label for="patient_name" class="form-label">Patient Name:</label>
                <input type="text" class="form-control" name="patient_name" required>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="bed_number" class="form-label">Bed Number:</label>
                    <input type="text" class="form-control" name="bed_number" required>
                </div>
                <div class="col">
                    <label for="ward" class="form-label">Ward:</label>
                    <input type="text" class="form-control" name="ward" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Allocate</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
