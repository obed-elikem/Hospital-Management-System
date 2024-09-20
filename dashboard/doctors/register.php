<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Doctor</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

    </header>
    <!-- ======= Header ======= -->

    <main class="container my-5">
        <?php
        if (isset($_POST["submit"])) {
            $fullname = $_POST["fullname"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $date_of_birth = $_POST["date_of_birth"];
            $gender = $_POST["gender"];
            $address = $_POST["address"];
            $location = $_POST["location"];
            $specialization = $_POST["specialization"];
            $password = $_POST["password"];
            $confirmpassword = $_POST["confirmpassword"];

            $passwordhash = password_hash($password, PASSWORD_DEFAULT);
            $errors = array();

            if (empty($fullname) or empty($email) or empty($phone) or empty($date_of_birth) or empty($gender) or empty($address) or empty($location) or empty($specialization) or empty($password) or empty($confirmpassword)) {
                array_push($errors, "All fields are required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "Email is not valid");
            }
            if (strlen($password) < 8) {
                array_push($errors, "Password must be at least 8 characters long.");
            }
            if ($password !== $confirmpassword) {
                array_push($errors, "Password does not match");
            }
            require_once "../../config/db.php";

            $sql = "SELECT * FROM doctors WHERE email = '$email'";
            $result = mysqli_query($connection, $sql);
            $rowcount = mysqli_num_rows($result);
            if ($rowcount > 0) {
                array_push($errors, "Email already exist");
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {

                $sql = "INSERT INTO doctors (fullname,email,phone,date_of_birth,gender,address,location,specialization,password) VALUE (?,?,?,?,?,?,?,?,?)";
                $stmt = mysqli_stmt_init($connection);
                $preparestmt = mysqli_stmt_prepare($stmt, $sql);
                if ($preparestmt) {
                    mysqli_stmt_bind_param($stmt, "sssssssss", $fullname, $email, $phone, $date_of_birth, $gender, $address, $location, $specialization, $passwordhash);
                    mysqli_stmt_execute($stmt);
                    echo "<div class='alert alert-success'>Registered</div>";
                    echo "<div class='alert alert-success'>Please Login</div>";
                } else {
                    die("Something went wrong");
                }
            }
        }
        ?>

        <div class="text-center fw-bold h1">REGISTER</div>
        <div class="text-center fw-bolder h3 text-blue">Doctor</div>
        <form action="register.php" method="post">
            <div class="form-group mb-3">
                <input type="text" class="form-control p-2" name="fullname" placeholder="Full Name" required>
            </div>

            <div class="form-group mb-3">
                <input type="email" class="form-control p-2" name="email" placeholder="Your Email" required>
            </div>

            <div class="form-group mb-3">
                <input type="tel" class="form-control p-2" name="phone" placeholder="Phone Number" required>
            </div>

            <div class="form-group mb-3">
                <label for="date_of_birth">Date Of Birth</label>
                <input type="date" class="form-control p-2" name="date_of_birth" placeholder="Date Of Birth" required>
            </div>

            <div class="form-group mb-3">
                <label for="gender">Gender:</label>
                <select class="form-control p-2" name="gender" id="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group mb-3">
                <input type="text" class="form-control p-2" name="address" placeholder="Address" required>
            </div>

            <div class="form-group mb-3">
                <input type="text" class="form-control p-2" name="location" placeholder="Location" required>
            </div>

            <div class="form-group mb-3">
                <input type="text" class="form-control p-2" name="specialization" placeholder="Specialization" required>
            </div>

            <div class="form-group mb-3">
                <input type="password" class="form-control p-2" name="password" placeholder="Password" required>
            </div>

            <div class="form-group mb-3">
                <input type="password" class="form-control p-2" name="confirmpassword" placeholder="Confirm Password" required>
            </div>

            <button class=" btn btn-primary btn-block mb-4" type="submit" name="submit">Register</button>
            
        </form>
        <p class="text-center"><a href="../../dashboard/admin/doctors/landing_page.php" class="text-center">Go back</a></p>
    </main>


</body>

</html>