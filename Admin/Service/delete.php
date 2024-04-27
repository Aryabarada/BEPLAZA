<?php

include_once("../../layout/koneksi.php");
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil nama file gambar sebelum menghapus data dari database
    $query_select = "SELECT gambar FROM pelayanan WHERE id='$id'";
    $result_select = mysqli_query($con, $query_select);
    if ($result_select) {
        $row = mysqli_fetch_assoc($result_select);
        $gambar = $row['gambar'];

        // Hapus file gambar jika ada
        if ($gambar != '') {
            $file_path = "../../Assets/storage/" . $gambar;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }
    }

    $query = "DELETE FROM pelayanan WHERE id='$id'";

    if (mysqli_query($con, $query)) {
        $_SESSION['success'] = "Berhasil Menghapus Pelayanan";
        header("location:../../Service");
    } else {
        echo "ERROR, tidak berhasil" . mysqli_error($con); // Gunakan $con bukan $conn
    }
}