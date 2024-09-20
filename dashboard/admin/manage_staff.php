<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff</title>
</head>

<body>
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

        <style>
            main {
                height: 100%;
            }

            main .patient,
            main .staff {
                border: 1px solid gray;
                border-radius: 10px;
                transition: linear .5s;
            }

            main .patient:hover,
            main .staff:hover {
                background-color: red;
            }

            main img {
                margin: auto;
                width: 150px;
                max-width: 100%;
            }
        </style>
    </head>

    <body>

        <!-- ======= Header ======= -->

        <main class="container d-flex justify-content-center align-items-center p-2">

            <div class="row">

                <a href="./doctors/landing_page.php" class="col-sm-6 col-md-4">
                    <div class="patient d-flex align-items-center justify-content-center flex-column m-2 w-100">
                        <img src="https://cdn-icons-png.flaticon.com/128/3304/3304567.png" class="p-5 img-fluid">
                        <h2 class="text-center">Doctor</h2>
                    </div>
                </a>
                <a href="./laboratorists/landing_page.php" class="col-sm-6 col-md-4">
                    <div class="staff d-flex align-items-center justify-content-center flex-column m-2 w-100">
                        <img src="https://cdn-icons-png.flaticon.com/128/7388/7388535.png" class="p-5 img-fluid">
                        <h2 class="text-center">Laboratorist</h2>
                    </div>
                </a>
                <a href="./nurses/landing_page.php" class="col-sm-6 col-md-4">
                    <div class="staff d-flex align-items-center justify-content-center flex-column m-2 w-100">
                        <img src="https://cdn-icons-png.flaticon.com/128/1165/1165602.png" class="p-5 img-fluid">
                        <h2 class="text-center">Nurse</h2>
                    </div>
                </a>
                <a href="./accountants/landing_page.php" class="col-sm-6 col-md-4">
                    <div class="staff d-flex align-items-center justify-content-center flex-column m-2 w-100">
                        <img src="https://cdn-icons-png.flaticon.com/128/2973/2973419.png" class="p-5 img-fluid">
                        <h2 class="text-center">Accountant</h2>
                    </div>
                </a>
                <a href="./pharmacists/landing_page.php" class="col-sm-6 col-md-4">
                    <div class="staff d-flex align-items-center justify-content-center flex-column m-2 w-100">
                        <img src="https://cdn-icons-png.flaticon.com/128/1256/1256535.png" class="p-5 img-fluid">
                        <h2 class="text-center">Pharmacist</h2>
                    </div>
                </a>
                <a href="./patients/landing_page.php" class="col-sm-6 col-md-4">
                    <div class="staff d-flex align-items-center justify-content-center flex-column m-2 w-100">
                        <img src="https://cdn-icons-png.flaticon.com/128/1513/1513366.png" class="p-5">
                        <h2 class="text-center">Patient</h2>
                    </div>
                </a>
            </div>

        </main>
    </body>

    </html>
</body>

</html>