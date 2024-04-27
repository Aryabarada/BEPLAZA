<?php 
include_once("../layout/function.php");
$name = isset($_SESSION['name']) ? $_SESSION['name'] : '';

$current_url = $_SERVER['REQUEST_URI']; // Mendapatkan URL saat ini

// Cek apakah URL mengandung '/Beranda/'
$is_beranda = strpos($current_url, '/Beranda/') !== false;
$is_harga = strpos($current_url, '/Price/') !== false;
$is_galeri = strpos($current_url, '/Galeri/') !== false;
$is_pemesanan = strpos($current_url, '/Booking/') !== false;


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Plaza Barbershop</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free Website Template" name="keywords">
    <meta content="Free Website Template" name="description">

    <!-- Favicon -->
    <link href="../Assets/img/favicon.ico" rel="icon">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap"
        rel="stylesheet">

    <!-- CSS Libraries -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="../Assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="../Assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../Assets/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap JavaScript (Popper.js harus dimuat terlebih dahulu, diikuti oleh Bootstrap JS) -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

    <!-- Template Stylesheet -->
    <link href="../Assets/css/style.css" rel="stylesheet">
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
                    <a href="../Beranda/"
                        class="nav-item nav-link <?php echo $is_beranda ? 'active' : ''; ?>">Beranda</a>
                    <a href="../Price/" class="nav-item nav-link <?php echo $is_harga ? 'active' : ''; ?>">Harga</a>
                    <a href="../Galeri/" class="nav-item nav-link <?php echo $is_galeri ? 'active' : ''; ?>">Galeri</a>
                    <a href="../Booking/"
                        class="nav-item nav-link <?php echo $is_pemesanan ? 'active' : ''; ?>">Pemesanan</a>
                    <?php  if (isset($_SESSION['login'])) {?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php echo $name ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <!-- <a class="dropdown-item" href="../UserProfile/">Profile</a> -->
                            <a class="dropdown-item" href="../Auth/Login/logout.php">Logout</a>
                            <!-- Add more dropdown items if needed -->
                        </div>
                    </div>
                    <?php }
                    else {?>
                    <a href="../Auth/Login/" class="nav-item nav-link">Login</a>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <!-- Nav Bar End -->