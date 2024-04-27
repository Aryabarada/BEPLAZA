<?php

session_start();
if (!isset($_SESSION['login_admin'])) {
    $_SESSION['warning'] = "Anda tidak memiliki hak akses admin";
    header('Location: ../../Beranda/');
    exit();
}

$current_url = $_SERVER['REQUEST_URI']; // Mendapatkan URL saat ini

// Cek apakah URL mengandung '/Beranda/'
$is_booking = strpos($current_url, '/Booking/') !== false;
$is_pelayanan = (strpos($current_url, '/Service/') !== false) || (strpos($current_url, '/Tambah-Pelayanan') !== false);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Plaza Barbershop</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free Website Template" name="keywords">
    <meta content="Free Website Template" name="description">
    <script src="../../Assets/js/jquery.js"></script>
    <script src="../../Assets/bootstrap/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">

    <!-- Favicon -->
    <link href="../../Assets/img/favicon.ico" rel="icon">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap"
        rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../../Assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="../../Assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../../Assets/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    <!-- Template Stylesheet -->
    <link href="../../Assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Nav Bar Start -->
    <div class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
            <a href="index.html" class="navbar-brand"><span>PLAZA</span></a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                <div class="navbar-nav ml-auto">
                    <a href="../../Admin/"
                        class="nav-item nav-link <?php echo $is_booking ? 'active' : ''; ?>">Booking</a>
                    <a href="../../Admin/Service/"
                        class="nav-item nav-link <?php echo $is_pelayanan ? 'active' : ''; ?>">Service</a>
                    <?php  if (!isset($_SESSION['login'])||isset($_SESSION['login_admin'])) {?>
                    <a href="../../Auth/Login/logout.php" class="nav-item nav-link">Logout</a>
                    <?php }
                    else {
                    ?>
                    <a href="../../Auth/Login/" class="nav-item nav-link">Login</a>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <!-- Nav Bar End -->

    <div class="page-header">
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger col-12 mx-auto text-center p-2 border rounded ">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['warning'])) {
            echo '<div class="alert alert-warning col-12 mx-auto text-center p-2 border rounded ">' . $_SESSION['warning'] . '</div>';
            unset($_SESSION['warning']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success col-12 mx-auto text-center p-2 border rounded ">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
    ?>