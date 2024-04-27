<?php 
include_once("../layout/koneksi.php");
require_once '../API/curl_helper.php';
$restAPIBaseURL = 'http://localhost/PlazaBarbershop/API';
session_start();

function registerUser($con, $username, $telp, $password, $cpassword) {
    $error = false;
    $errusername = false;
    $errtelp = false;
    $errpassword = false;
    $errcpassword = false;

    if (preg_match('/[<>]/', $username) || preg_match('/[;\'"()|&%*$^]/', $username)) {
        $error = true;
        $errusername = true;
    }
    if (!preg_match('/^[0-9]+$/', $telp)) {
        $error = true;
        $errtelp = true;
    }
    if (preg_match('/[<>]/', $password) || preg_match('/[;\'"()|&%*$^]/', $password) || strpos($password, ' ') !== false) {
        $error = true;
        $errpassword= true;
    }
    if($password !== $cpassword){
        $error = true;
        $errcpassword= true;
    }

    if($error===true){
        $_SESSION['error'] = "Ada kesalahan pada data yang diinputkan :";
        if($errusername===true){
            $_SESSION['error'] .= "<br> - Username tidak boleh mengandung karakter HTML atau Query SQL.";
        }
        if ($errtelp === true) {
            $_SESSION['error'] .= "<br> - Nomor telepon hanya boleh berisi angka saja.";
        }  
        if($errpassword===true){
            $_SESSION['error'] .= "<br> - Password tidak boleh mengandung spasi maupun karakter HTML atau Query SQL.";
        }
        if($errcpassword===true){
            $_SESSION['error'] .= "<br> - Konfirmasi password tidak sesuai dengan password";
        }
        echo '<script>window.location.href = "../Auth/Register/";</script>';
    }
    else {
        $md5password = md5($password);
        $resultz = mysqli_query($con, "INSERT INTO pelanggan (username, no_telp, password) VALUES ('$username', '$telp', '$md5password')");
        if ($resultz) {
            $_SESSION['success'] = "Berhasil mendaftarkan akun silahkan login";
            echo '<script>window.location.href = "../Auth/Login/";</script>';
        } else {
            $_SESSION['error'] = "Gagal mendaftarkan akun";
            echo '<script>window.location.href = "../Auth/Register/";</script>';
        }
    }
}

// Fungsi untuk melakukan login pengguna
function loginUser($con, $username, $password) {
    $query = "SELECT * FROM pelanggan";
    $result = mysqli_query($con, $query);
    $query2 = "SELECT * FROM admin";
    $result2 = mysqli_query($con, $query2);

    if (mysqli_num_rows($result) > 0 || mysqli_num_rows($result2) > 0) {
        // Loop melalui hasil query pengguna
        while ($row = mysqli_fetch_assoc($result)) {
            $username_db = $row['username'];
            $password_db = $row['password'];
            $enteredPassword = md5($password);
            if ($username === $username_db && $enteredPassword === $password_db) {
                $_SESSION['login'] = true;
                $_SESSION['auth_id'] = $row['id'];
                $query = "SELECT * FROM pelanggan WHERE id = $row[id];";
                $result = mysqli_query($con, $query);
                $user = mysqli_fetch_assoc($result);
                $name = $user['username'];
                $_SESSION['name'] = $name;
                unset($_SESSION['error']);
                header('Location: ../Beranda/');
                exit();
            }
        }
        // Loop melalui hasil query admin
        while ($row = mysqli_fetch_assoc($result2)) {
            $username_db = $row['username'];
            $password_db = $row['password'];
            $enteredPassword = md5($password);
            if ($username === $username_db && $enteredPassword === $password_db) {
                $_SESSION['login_admin'] = true;
                $_SESSION['auth_id'] = $row['id'];
                $query = "SELECT * FROM admin WHERE id = $row[id];";
                $result = mysqli_query($con, $query);
                $user = mysqli_fetch_assoc($result);
                $name = $user['username'];
                $_SESSION['success'] = "Welcome $name";
                unset($_SESSION['error']);
                header('Location: ../Admin/');
                exit();
            }
        }
        $_SESSION['error'] = 'Username atau password salah. Coba lagi.';
        header('Location: ../Auth/Login/');
    } else {
        echo "Tidak ada data yang ditemukan.";
    }
}

function bookAppointment($con, $nama, $telp, $waktu, $tanggal, $pesan) {
    $query =  "INSERT INTO booking (nama_booking, nomerhp_booking, waktu_booking, tanggal_booking, pesan_booking, harga_booking) VALUES ('$nama', '$telp', '$waktu', '$tanggal', '$pesan', '')";

    if (mysqli_query($con, $query)) {
        $_SESSION['success'] = "Berhasil Melakukan Booking";
        header("location:../Booking/");
    } else {
        echo "ERROR, tidak berhasil" . mysqli_error($con);
    }
}

function bookAppointmentAdmin($con, $nama, $telp, $services, $waktu, $tanggal, $pesan, $harga) {
    // Query untuk menyisipkan data pemesanan ke dalam tabel booking
    $query = "INSERT INTO booking (nama_booking, nomerhp_booking, waktu_booking, tanggal_booking, pesan_booking, harga_booking) VALUES ('$nama', '$telp', '$waktu', '$tanggal', '$pesan', '$harga')";

    // Eksekusi query
    if (mysqli_query($con, $query)) {
        // Mendapatkan ID booking yang baru saja dimasukkan
        $id_booking = mysqli_insert_id($con);

        // Loop melalui setiap layanan yang dipilih
        foreach ($services as $service) {
            $id_service = $service['id_service']; // Mengambil ID layanan dari array $service
            
            // Query untuk menyisipkan data ID layanan ke dalam tabel order_layanan
            $query_order = "INSERT INTO order_layanan (id_booking, id_pelayanan) VALUES ('$id_booking', '$id_service')";
            
            // Eksekusi query
            if (!mysqli_query($con, $query_order)) {
                echo "ERROR, tidak berhasil" . mysqli_error($con);
                return;
            }
        }

        $_SESSION['success'] = "Berhasil Melakukan Booking";
        header("location:../Admin/");
    } else {
        echo "ERROR, tidak berhasil" . mysqli_error($con);
    }
}




// Fungsi untuk menambahkan layanan baru
function addService($con, $nama, $keterangan, $harga, $nama_unik_gambar) {
    $query = "INSERT INTO pelayanan (nama, keterangan, harga, gambar) VALUES ('$nama', '$keterangan', '$harga', '$nama_unik_gambar')";
    if (mysqli_query($con, $query)) {
        $_SESSION['success'] = "Berhasil Menambah Pelayanan";
        header("location:../Admin/Service");
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat menambah pelayanan.";
        header("HTTP/1.1 302 Found");
        header("location: " . $_SERVER['HTTP_REFERER']); // Kembali ke halaman sebelumnya
    }
}


// Fungsi untuk mengedit layanan
function editService($con, $id, $nama, $keterangan, $harga, $nama_unik_gambar) {
    $query = "UPDATE pelayanan SET keterangan='$keterangan', harga='$harga', nama='$nama', gambar='$nama_unik_gambar' WHERE id ='$id'";
    if (mysqli_query($con, $query)) {
        $_SESSION['success'] = "Berhasil Mengupdate Pelayanan";
        header("location:../Admin/Service");
    } else {
        echo "ERROR, tidak berhasil" . mysqli_error($con);
    }
}

// Fungsi untuk mendapatkan waktu yang sudah di-booking pada tanggal tertentu
function getBookedTimes($con, $tanggal) {
    $query = "SELECT waktu_booking FROM booking WHERE tanggal_booking = '$tanggal'";
    $result = mysqli_query($con, $query);
    
    $booked_times = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $booked_times[] = $row['waktu_booking'];
    }
    
    return $booked_times;
}

// Fungsi untuk mendapatkan data pengguna berdasarkan ID
function getUserDataByID($con, $auth_id) {
  $query = "SELECT * FROM pelanggan WHERE id = '$auth_id'";
  $result = mysqli_query($con, $query);
  return mysqli_fetch_assoc($result);
}


// Fungsi untuk mendapatkan data booking
function getBookingData($con) {
  $query = "SELECT * FROM booking";
  $result = mysqli_query($con, $query);
  $data = array();
  while ($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
  }
  return $data;
}
// Fungsi untuk mendapatkan data pelayanan
function getServiceData($con) {
  $query = "SELECT * FROM pelayanan";
  $result = mysqli_query($con, $query);
  $data = array();
  while ($row = mysqli_fetch_assoc($result)) {
      $data[] = $row;
  }
  return $data;
}

// Fungsi untuk mendapatkan harga layanan dari database berdasarkan ID layanan
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


function getAPIPelangganDataLogin() {
    $pelanggan = sendRequest("http://localhost/PlazaBarbershop/API/api.php/pelanggan/$_SESSION[auth_id]", 'GET');
    $pelanggans = json_decode($pelanggan, true);
    $datapel[] = $pelanggans;
    $response_array = json_decode($datapel[0]['response'], true);
    return $response_array;
}




// Panggil fungsi registerUser jika terdapat permintaan registrasi
if (isset($_POST['register'])) {
  $username = $_POST['username'];
  $telp = $_POST['telp'];
  $password = $_POST['password'];
  $cpassword = $_POST['cpassword'];
  registerUser($con, $username, $telp, $password, $cpassword);
}

// Panggil fungsi loginUser jika terdapat permintaan login
elseif (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  loginUser($con, $username, $password);
}

// Panggil fungsi bookAppointment jika terdapat permintaan booking
elseif (isset($_POST['booking'])) {
  $nama = $_POST['nama'];
  $telp = $_POST['telp'];
  $waktu = $_POST['waktu'];
  $tanggal = $_POST['tanggal'];
  $pesan = $_POST['pesan'];
  bookAppointment($con, $nama, $telp, $waktu, $tanggal, $pesan);
}


// Panggil fungsi bookAppointmentAdmin jika terdapat permintaan booking dari admin
elseif (isset($_POST['bookingadmin'])) {
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
    bookAppointmentAdmin($con, $nama, $telp, $services, $waktu, $tanggal, $pesan, $harga_total);
}


// Panggil fungsi addService jika terdapat permintaan untuk menambahkan layanan baru

elseif (isset($_POST['tambahlayanan'])) {
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];
    $harga = $_POST['harga'];

    // Proses gambar yang diunggah
    $gambar = $_FILES['gambar']; // Dapatkan informasi tentang file gambar
    $ekstensi = pathinfo($gambar['name'], PATHINFO_EXTENSION); // Nama file gambar
    $gambar_tmp = $gambar['tmp_name']; // Lokasi sementara file gambar
    $gambar_size = $gambar['size']; // Ukuran file gambar
    $gambar_error = $gambar['error']; // Kode error jika ada

    // Periksa apakah gambar telah diunggah tanpa kesalahan
    if ($gambar_error === 0) {
        // Generate nama unik untuk gambar
        $nama_unik_gambar = $nama . '_' . time() . '.' . $ekstensi;
        $gambar_destination = '../Assets/storage/' . $nama_unik_gambar;
        
        // Pindahkan file gambar yang diunggah ke lokasi tujuan
        move_uploaded_file($gambar_tmp, $gambar_destination);

        // Panggil fungsi untuk menambah layanan dengan gambar
        addService($con, $nama, $keterangan, $harga, $nama_unik_gambar);
    } else {
        // Jika terjadi kesalahan saat mengunggah gambar
        $_SESSION['error'] = "Terjadi kesalahan saat mengunggah gambar.";
        header('Location: ../halaman_error.php'); // Redirect ke halaman error atau sesuai kebutuhan aplikasi Anda
        exit();
    }
}

// Panggil fungsi editService jika terdapat permintaan untuk mengedit layanan
elseif (isset($_POST['editlayanan'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $keterangan = $_POST['keterangan'];
    $harga = $_POST['harga'];

    $query = "SELECT gambar FROM pelayanan WHERE id = $id";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    $gambar_lama = $row['gambar'];

    // Jika gambar baru diunggah
    if ($_FILES['gambar']['error'] === 0) {
        // Proses gambar yang diunggah
        $gambar = $_FILES['gambar'];
        $ekstensi = pathinfo($gambar['name'], PATHINFO_EXTENSION);
        $gambar_tmp = $gambar['tmp_name'];
        $gambar_size = $gambar['size'];
        $gambar_error = $gambar['error'];

        // Periksa apakah gambar telah diunggah tanpa kesalahan
        if ($gambar_error === 0) {
            // Generate nama unik untuk gambar baru
            $nama_unik_gambar = $nama . '_' . time() . '.' . $ekstensi;
            $gambar_destination = '../Assets/storage/' . $nama_unik_gambar;

            // Pindahkan file gambar baru ke lokasi tujuan
            move_uploaded_file($gambar_tmp, $gambar_destination);

            // Dapatkan informasi gambar lama
           

            // Hapus gambar lama jika ada
            if (!empty($gambar_lama)) {
                unlink('../Assets/storage/' . $gambar_lama);
            }

            // Panggil fungsi untuk mengedit layanan dengan gambar baru
            editService($con, $id, $nama, $keterangan, $harga, $nama_unik_gambar);
        } else {
            // Jika terjadi kesalahan saat mengunggah gambar baru
            $_SESSION['error'] = "Terjadi kesalahan saat mengunggah gambar baru.";
            header('Location: ../halaman_error.php');
            exit();
        }
    } else {
        // Jika tidak ada gambar baru diunggah
        editService($con, $id, $nama, $keterangan, $harga, $nama_unik_gambar);
    }
}

// Panggil fungsi getBookedTimes jika terdapat permintaan untuk mendapatkan waktu yang sudah di-booking
elseif (isset($_GET['tanggal'])) {
  $tanggal = $_GET['tanggal'];
  $bookedTimes = getBookedTimes($con, $tanggal);
  echo json_encode($bookedTimes);
}



elseif (isset($_POST['APIbooking'])) {

    $data = [
        'nama_booking' => $_POST['nama'],
        'nomerhp_booking' => $_POST['telp'],
        'tanggal_booking' => $_POST['tanggal'],
        'waktu_booking' => $_POST['waktu'],
        'pesan_booking' => $_POST['pesan'],
    ];
    $result = sendRequest($restAPIBaseURL.'/api.php/booking', 'POST', $data);
    $results = json_decode($result, true);


    if ($results) {
        $_SESSION['success'] = "Berhasil Melakukan Booking";
        header("location:../Booking/");
    } else {
        echo "ERROR, tidak berhasil" . mysqli_error($con);
    }
    
  }