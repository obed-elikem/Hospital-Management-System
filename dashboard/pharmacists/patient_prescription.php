<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
require_once "../../config/db.php";

// Start the session (make sure this is placed before any output to the browser)
// session_start();

// Check if the pharmacist's ID is available in the session
if (!isset($_SESSION["id"])) {
    // Redirect to the pharmacist's login page if not logged in
    // header("Location: ./login.php");
    exit();
}

// Get the pharmacist's ID from the session (make sure the pharmacist is logged in)
$pharmacist_id = $_SESSION["id"];

// Function to fetch patient ID based on patient's fullname
function getPatientIdByfullname($connection, $patient_fullname)
{
    // Prepare the SQL query to fetch patient ID using the patient's fullname
    $sql = "SELECT id FROM patients WHERE fullname = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $patient_fullname);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $patient_id);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $patient_id;
    } else {
        echo "Error: Unable to prepare the patient query.";
        return false;
    }
}

// Function to fetch doctor's name based on doctor's ID
function getDoctorNameById($connection, $doctor_id)
{
    // Prepare the SQL query to fetch doctor's name using the doctor's ID
    $sql = "SELECT fullname FROM doctors WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $doctor_name);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        return $doctor_name;
    } else {
        echo "Error: Unable to prepare the doctor query.";
        return false;
    }
}

// Function to fetch patient prescriptions based on patient's ID
function fetchPrescriptionsByPatientId($connection, $patient_id)
{
    // Prepare the SQL query to fetch patient prescriptions using the patient's ID
    $sql = "SELECT * FROM prescriptions WHERE patient_id = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($connection, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $patient_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);

        return $result;
    } else {
        echo "Error: Unable to prepare the prescription query.";
        return false;
    }
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["search_prescriptions"])) {
    $patient_fullname = $_POST["patient_fullname"];
    $patient_id = getPatientIdByfullname($connection, $patient_fullname);

    if ($patient_id) {
        $prescriptions = fetchPrescriptionsByPatientId($connection, $patient_id);
    } else {
        // echo "Error: Patient with fullname $patient_fullname not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Patient Prescriptions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>View Patient Prescriptions</h1>
        <form action="" method="post">
            <div class="mb-3">
                <label for="patient_fullname" class="form-label">Patient Name</label>
                <input type="text" class="form-control" name="patient_fullname" required>
            </div>
            <div class="d-flex justify-content-end">
            <button type="submit" name="search_prescriptions" class="btn btn-primary">Search Prescriptions</button>
            </div>
        </form>

        <?php if (isset($prescriptions)) : ?>
            <h2>Prescriptions</h2>
            <?php if (mysqli_num_rows($prescriptions) > 0) : ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Doctor Name</th>
                            <th>Medication</th>
                            <th>Dosage</th>
                            <th>Cost</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($prescriptions)) : ?>
                            <tr>
                                <td><?php echo getDoctorNameById($connection, $row['doctor_id']); ?></td>
                                <td><?php echo $row['medication']; ?></td>
                                <td><?php echo $row['dosage']; ?></td>
                                <td><?php echo $row['cost']; ?></td>
                                <td><?php echo $row['created_at']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p class="text-danger">No prescriptions found for the patient.</p>
            <?php endif; ?>
        <?php elseif (isset($_POST["search_prescriptions"]) && !isset($prescriptions)) : ?>
            <p class="text-danger">Error: Patient with fullname <?php echo $_POST["patient_fullname"]; ?> not found.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>