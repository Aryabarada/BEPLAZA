<?php

$id = $_GET['id'];
session_start();
include('../../config.php');

// Query untuk mendapatkan id_pelayanan yang perlu dihapus dari tabel order_layanan
$query_get_order = "SELECT id_pelayanan FROM order_layanan WHERE id_booking = '$id'";
$result_get_order = mysqli_query($con, $query_get_order);

// Loop melalui hasil query untuk mengumpulkan id_pelayanan
$orders_to_delete = array();
while ($row = mysqli_fetch_assoc($result_get_order)) {
    $orders_to_delete[] = $row['id_pelayanan'];
}

// Mengonversi array id_pelayanan menjadi string terpisah dengan koma
$orders_to_delete_str = implode(',', $orders_to_delete);

// Query untuk menghapus data dari tabel order_layanan
$query_delete_order = "DELETE FROM order_layanan WHERE id_booking = '$id'";
if (!mysqli_query($con, $query_delete_order)) {
    echo "ERROR, data order_layanan gagal dihapus" . mysqli_error($con);
    exit; // Menghentikan eksekusi jika terjadi kesalahan
}

// Query untuk menghapus data dari tabel booking
$query_delete_booking = "DELETE FROM booking WHERE id_booking = '$id'";
if (mysqli_query($con, $query_delete_booking)) {
    $_SESSION['success'] = "Berhasil Menghapus data booking";
    header("location:index.php");
} else {
    echo "ERROR, data booking gagal dihapus" . mysqli_error($con);
}

mysqli_close($con);