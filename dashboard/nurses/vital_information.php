<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Vital Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <?php
        // Display success message if available
        if (!empty($_GET['success_message'])) {
            echo "<div class='alert alert-success'>{$_GET['success_message']}</div>";
        }

        // Display error message if available
        if (!empty($_GET['error_message'])) {
            echo "<div class='alert alert-danger'>{$_GET['error_message']}</div>";
        }

        // Display error for patient name
        if (!empty($_GET['error_patient'])) {
            echo "<div class='alert alert-danger'>{$_GET['error_patient']}</div>";
        }
        ?>

        <h3>Record Vital Information</h3>
        <form action="save_vital_information.php" method="post">
            <div class="mb-3">
                <label for="patient_fullname" class="form-label">Patient Name:</label>
                <input type="text" class="form-control" name="patient_fullname" required>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="temperature" class="form-label">Temperature (celsius):</label>
                    <input type="tel" class="form-control" name="temperature" required>
                </div>
                <div class="col">
                    <label for="blood_pressure" class="form-label">Blood Pressure:</label>
                    <input type="tel" class="form-control" name="blood_pressure" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="heart_rate" class="form-label">Heart Rate:</label>
                    <input type="tel" class="form-control" name="heart_rate" required>
                </div>
                <div class="col">
                    <label for="weight" class="form-label">Weight (kg):</label>
                    <input type="tel" class="form-control" name="weight" required>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label for="height" class="form-label">Height (cm):</label>
                    <input type="tel" class="form-control" name="height" required>
                </div>
                <div class="col">
                    <label for="sugar_level" class="form-label">Sugar Level:</label>
                    <input type="tel" class="form-control" name="sugar_level" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="record_date" class="form-label">Record Date:</label>
                <input type="date" class="form-control" name="record_date" required>
            </div>
            <!-- Add more vital information fields as needed -->
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>