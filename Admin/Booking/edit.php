<?php
include_once("../../layout/koneksi.php");
session_start();
// Fungsi untuk mengupdate data booking

function bookAppointmentAdmin($id, $con, $nama, $telp, $services, $waktu, $tanggal, $pesan, $harga) {
    // Query untuk menyisipkan data pemesanan ke dalam tabel booking
    $query = "UPDATE booking SET nama_booking='$nama', nomerhp_booking='$telp', waktu_booking=' $waktu', tanggal_booking='$tanggal', pesan_booking='$pesan', harga_booking='$harga' WHERE id_booking='$id'";
    
    // Eksekusi query
    if (mysqli_query($con, $query)) {
    
        // Menghapus semua data order_layanan dengan id_booking yang sama
        $query_delete = "DELETE FROM order_layanan WHERE id_booking = '$id'";
        if (!mysqli_query($con, $query_delete)) {
            echo "ERROR, tidak berhasil menghapus data order_layanan" . mysqli_error($con);
            return;
        }
    
        // Loop melalui setiap layanan yang dipilih
        foreach ($services as $service) {
            $id_service = $service['id_service']; // Mengambil ID layanan dari array $service
            
            // Query untuk menyisipkan data ID layanan ke dalam tabel order_layanan
            $query_order = "INSERT INTO order_layanan (id_booking, id_pelayanan) VALUES ('$id', '$id_service')";
            
            // Eksekusi query
            if (!mysqli_query($con, $query_order)) {
                echo "ERROR, tidak berhasil" . mysqli_error($con);
                return;
            }
        }
    
        $_SESSION['success'] = "Berhasil Melakukan Update Booking";
        header("location:../../Admin/");
    }
    else {
        echo "ERROR, tidak berhasil" . mysqli_error($con);
    }
}


function getHargaServiceFromDatabase($con, $id_service) {
    // Lakukan query ke database untuk mendapatkan harga layanan berdasarkan ID
    $query = "SELECT harga FROM pelayanan WHERE id = ?";
    $statement = $con->prepare($query);
    $statement->bind_param("i", $id_service); // Mengikat parameter ke placeholder (?)
    $statement->execute();
    $result = $statement->get_result();

    // Periksa apakah query berhasil dieksekusi
    if ($result->num_rows > 0) {
        // Ambil hasil query dan kembalikan harga layanan
        $row = $result->fetch_assoc();
        return $row['harga'];
    } else {
        // Jika tidak ada hasil dari query, kembalikan nilai default atau lakukan penanganan kesalahan lainnya sesuai kebutuhan Anda
        return 0;
    }
}

// Memeriksa apakah metode yang digunakan adalah POST
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $waktu = $_POST['waktu'];
    $tanggal = $_POST['tanggal'];
    $pesan = $_POST['pesan'];
    
    // Mendapatkan semua layanan yang dicentang
    $id_service_array = $_POST['service'];
    $harga_total = 0;

    // Menginisialisasi array untuk menyimpan data layanan yang dipilih
    $services = array();

    // Loop melalui setiap layanan yang dicentang
    foreach ($id_service_array as $id_service) {
        $harga_service = getHargaServiceFromDatabase($con, $id_service);
    
        $harga_total += $harga_service;

        $services[] = array(
            'id_service' => $id_service,
        );
    }

    // Sekarang Anda memiliki semua data yang diperlukan untuk memanggil fungsi bookAppointmentAdmin
    bookAppointmentAdmin($id, $con, $nama, $telp, $services, $waktu, $tanggal, $pesan, $harga_total);
} 
// Menutup koneksi
mysqli_close($con);