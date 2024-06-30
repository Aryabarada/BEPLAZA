<?php
$host = "localhost";
$username = "daag5153_daaw";
$password = "inipasswordSUSAH123**";
$databasename = "daag5153_db_plaza";

// Syntax untuk koneksi ke MySQL
$con = mysqli_connect($host, $username, $password, $databasename);

// Perkondisian jika gagal konek ke MySQL
if (!$con) {
    echo "Error: " . mysqli_connect_error();
    exit();
}