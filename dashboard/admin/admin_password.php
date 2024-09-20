<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Password</title>
    <!-- Add Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">Administrator Password</h1>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $password = $_POST["password"];
            
            // Replace with your desired password
            $correctPassword = "123456789";
            
            if ($password === $correctPassword) {
                // Redirect to the admin page on successful password
                header("Location: ./login.php");
                exit();
            } else {
                echo '<div class="alert alert-danger">Wrong password. Please try again.</div>';
            }
        }
        ?>
        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Add Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
