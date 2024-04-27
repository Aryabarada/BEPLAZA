<?php
include('config.php');

$nama = $_POST['nama'];
$nomerhp = $_POST['telp'];
$service = $_POST['service'];
$waktu = $_POST['waktu'];
$tanggal = $_POST['tanggal'];
$pesan = $_POST['pesan'];
$harga = $_POST['harga'];

$query =  "INSERT INTO booking(nama_booking , nomerhp_booking , service_booking, waktu_booking, tanggal_booking, pesan_booking, harga_booking) VALUES('$nama' , '$nomerhp' , '$service',  '$waktu', '$tanggal', '$pesan', '$harga')";

if (mysqli_query($conn, $query)) {
	header("location:index.php");
} else {
	echo "ERROR, tidak berhasil" . mysqli_error($conn);
}

mysqli_close($conn);