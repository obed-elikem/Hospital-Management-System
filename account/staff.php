<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logining In</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css" rel="stylesheet" />

    <!-- My Style -->
    <link rel="stylesheet" href="../assets/styles/userorstaff.css">
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

    <main class="container d-flex justify-content-center align-items-center p-2">

        <div class="row h-100">
            <a href="../dashboard/admin/admin_password.php" class="col-sm-6 col-md-4">
                <div class="staff d-flex align-items-center justify-content-center flex-column m-2 w-100">
                    <img src="https://cdn-icons-png.flaticon.com/128/9322/9322127.png" class="p-5 img-fluid">
                    <h2 class="text-center">Administrator</h2>
                </div>
            </a>
            
            <a href="../dashboard/doctors/login.php" class="col-sm-6 col-md-4">
                <div class="patient d-flex align-items-center justify-content-center flex-column m-2 w-100">
                    <img src="https://cdn-icons-png.flaticon.com/128/3304/3304567.png" class="p-5 img-fluid">
                    <h2 class="text-center">Doctor</h2>
                </div>
            </a>
            <a href="../dashboard/laboratorist/login.php" class="col-sm-6 col-md-4">
                <div class="staff d-flex align-items-center justify-content-center flex-column m-2 w-100">
                    <img src="https://cdn-icons-png.flaticon.com/128/7388/7388535.png" class="p-5 img-fluid">
                    <h2 class="text-center">Laboratorist</h2>
                </div>
            </a>
            <a href="../dashboard/nurses/login.php" class="col-sm-6 col-md-4">
                <div class="staff d-flex align-items-center justify-content-center flex-column m-2 w-100">
                    <img src="https://cdn-icons-png.flaticon.com/128/1165/1165602.png" class="p-5 img-fluid">
                    <h2 class="text-center">Nurse</h2>
                </div>
            </a>
            <a href="../dashboard/accountant/login.php" class="col-sm-6 col-md-4">
                <div class="staff d-flex align-items-center justify-content-center flex-column m-2 w-100">
                    <img src="https://cdn-icons-png.flaticon.com/128/2973/2973419.png" class="p-5 img-fluid">
                    <h2 class="text-center">Accountant</h2>
                </div>
            </a>
            <a href="../dashboard/pharmacists/login.php" class="col-sm-6 col-md-4">
                <div class="staff d-flex align-items-center justify-content-center flex-column m-2 w-100">
                    <img src="https://cdn-icons-png.flaticon.com/128/1256/1256535.png" class="p-5 img-fluid">
                    <h2 class="text-center">Pharmacist</h2>
                </div>
            </a>
        </div>

    </main>
</body>

</html>