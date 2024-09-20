<?php
// Start a session to manage user authentication
session_start();

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    require_once "../../config/db.php";
    $sql = "SELECT * FROM admin WHERE email = '$email'";
    $result = mysqli_query($connection, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
    if ($user) {
        // verify password
        if (password_verify($password, $user["password"])) {
            // Store the patient ID and email in the session variables
            $_SESSION["user"] = "yes";
            $_SESSION["id"] = $user["id"]; // Assuming 'id' is the column name for patient ID in the database
            $_SESSION["email"] = $email; // Store the entered email in the session

            // Redirect to the patient dashboard
            header("Location: ./admindashboard.php");
            die();
        } else {
            echo "<div class='alert alert-danger'>Password does not exist</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Email does not exist</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="../../assets/styles/account.css">
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="">

        <!-- ======= Top Bar ======= -->
        <div id="topbar" class="d-flex align-items-center">
            <div class="container d-flex justify-content-between">
                <div class="contact-info d-flex align-items-center">
                    <i class="fas fa-envelope"></i> <a href="mailto:contact@example.com">ayaomed@gmail.com</a>
                    <i class="fas fa-phone">054 0000 0000</i>
                </div>
                <div class="d-none d-lg-flex social-links align-items-center">
                    <a href="#" class="twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
        <!-- ======= Top Bar ======= -->

        <!-- ======= Navbar ======= -->
        <nav class="navbar navbar-expand-lg bg-white">
            <div class="container w-100">

                <a class="navbar-brand" href="#">Ayao<span>MED</span></a>
                <button class="navbar-toggler" type="button" data-mdb-toggle="collapse" data-mdb-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fas fa-bars fs-1"></i>
                </button>

                <div class="collapse d-lg-flex justify-content-lg-end navbar-collapse w-100" id="navbarNav">
                    <ul class="navbar-nav d-flex align-items-center py-2">
                        <li class="nav-item">
                            <a class="nav-link" href="../../index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../index.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../index.php">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../../account/userorstaff.php"><i class="fas fa-user fa-lg"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- ======= Navbar ======= -->

    </header>
    <!-- ======= Header ======= -->


    <main class="container my-5">
        <div class="text-center fw-bold h1">LOGIN</div>
        <h4 class="text-center text-blue mb-2">Administrator</h4>
        <form action="login.php" method="post">
            <!-- Email -->
            <div class="mb-4">
                <input type="email" id="loginName" name="email" class="form-control p-2" placeholder="Email" required />

            </div>

            <!-- Password -->
            <div class="mb-4">
                <input type="password" id="loginPassword" name="password" class="form-control p-2" placeholder="Password" required />

            </div>

            <!-- Submit button -->
            <input type="submit" name="login" class="btn btn-primary btn-block mb-4" value="Login">

        </form>
    </main>

    <!-- Bootstrap -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>

    <!-- AOS Animate -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        AOS.init();
    </script>
</body>

</html>
