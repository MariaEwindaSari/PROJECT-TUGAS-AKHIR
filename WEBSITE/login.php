<?php
session_start();

include "koneksi.php";

if(isset($_POST["login"])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $data = mysqli_query($koneksi,"SELECT * FROM user WHERE username='$username' AND password='$password'");
    
    if(mysqli_num_rows($data)>0){
        $row = mysqli_fetch_array($data);
        header("location: index.php?page=dashboard");
        $_SESSION['login'] = true;
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['password'] = $row['password'];
        $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
        $_SESSION['avatar'] = $row['avatar'];
    }
    $error = true;
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #9494ff;
            background-size: cover;
        }

        .card {
            border: none;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            margin-top: 100px;
        }

        .bg-login-image {
            background: url('img/tedu.jpeg');
            background-position: center;
            background-size: cover;
        }

       
    </style>

</head>

<body>

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 ">

                <div class="card">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Selamat datang!</h1>
                                    </div>

                                    <!-- Form login -->
                                    <form class="user" method="post">
                                        <?php if(isset($error)): ?>
                                            <div class="alert alert-danger text-center" role="alert">
                                                Username atau password salah.
                                            </div>
                                        <?php endif; ?>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="username" name="username" placeholder="Masukkan username">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="password" name="password" placeholder="masukan Password">
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <button type="submit" name="login" class="btn btn-info btn-user btn-block">
                                                Login
                                            </button>
                                        </div>
                                        
                                    </form>
                                    <!-- End Form login -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>