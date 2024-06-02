<?php
class Booking
{
    private $con;
    public function __construct($con) {
        $this->con = $con;
    }
    public function getAllBooking() {
        $query = "SELECT * FROM booking";
        $queryPelayanan = "SELECT id_booking, id_pelayanan FROM order_layanan";
        $result = mysqli_query($this->con, $query);
        $resultPelayanan = mysqli_query($this->con, $queryPelayanan);
        
        // Simpan semua hasil $resultPelayanan ke dalam array
        $orderLayanans = array();
        while ($rowPel = mysqli_fetch_assoc($resultPelayanan)) {
            $orderLayanans[$rowPel['id_booking']][] = $rowPel['id_pelayanan'];
        }
        
        $booking = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $id_booking = $row['id_booking'];
            $order_layanans = array();
            // Periksa jika ada data pelayanan untuk booking saat ini
            if (isset($orderLayanans[$id_booking])) {
                foreach ($orderLayanans[$id_booking] as $id_pelayanan) {
                    $DataPel = mysqli_fetch_assoc(mysqli_query($this->con, "SELECT nama FROM pelayanan WHERE id_pelayanan=$id_pelayanan"));
                    $order_layanans[] = $DataPel['nama'];
                }
            }
            $row['order_layanan'] = implode(', ', $order_layanans);
            $cari = "SELECT username,no_telp FROM user WHERE userID = '$row[userID]'";
            $koneksi = mysqli_query($this->con, $cari);
            $ketemu = mysqli_fetch_assoc($koneksi);
            $row['nama_booking'] = $ketemu['username'];
            $row['nomerhp_booking'] = $ketemu['no_telp'];
            $booking[] = $row;
        }
        return $booking; 
    }
    public function getAllBookingWithNullPrice() {
        $query = "SELECT * FROM booking WHERE harga_booking = ''";
        $queryPelayanan = "SELECT id_booking, id_pelayanan FROM order_layanan";
        $result = mysqli_query($this->con, $query);
        $resultPelayanan = mysqli_query($this->con, $queryPelayanan);
        
        // Simpan semua hasil $resultPelayanan ke dalam array
        $orderLayanans = array();
        while ($rowPel = mysqli_fetch_assoc($resultPelayanan)) {
            $orderLayanans[$rowPel['id_booking']][] = $rowPel['id_pelayanan'];
        }
        
        $booking = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $id_booking = $row['id_booking'];
            $order_layanans = array();
            // Periksa jika ada data pelayanan untuk booking saat ini
            if (isset($orderLayanans[$id_booking])) {
                foreach ($orderLayanans[$id_booking] as $id_pelayanan) {
                    $DataPel = mysqli_fetch_assoc(mysqli_query($this->con, "SELECT nama FROM pelayanan WHERE id_pelayanan=$id_pelayanan"));
                    $order_layanans[] = $DataPel['nama'];
                }
            }
            $row['order_layanan'] = implode(', ', $order_layanans);
            $cari = "SELECT username,no_telp FROM user WHERE userID = '$row[userID]'";
            $koneksi = mysqli_query($this->con, $cari);
            $ketemu = mysqli_fetch_assoc($koneksi);
            $row['nama_booking'] = $ketemu['username'];
            $row['nomerhp_booking'] = $ketemu['no_telp'];
            $booking[] = $row;
        }
        return $booking; 
    }

    
    public function getBookingById($id) {
        $query = "SELECT * FROM booking WHERE id_booking = $id";
        $result = mysqli_query($this->con, $query);
        $booking = mysqli_fetch_assoc($result);
        return $booking;
    }
    public function getAllBookedHistory() {
        // Query untuk mendapatkan data pemesanan
        $query = "SELECT * FROM booking WHERE harga_booking != 0";
        $result = mysqli_query($this->con, $query);
        
        // Array untuk menyimpan data pemesanan
        $booking = array();
        
        // Query untuk mendapatkan data pelayanan
        $queryPelayanan = "SELECT id_booking, id_pelayanan FROM order_layanan";
        $resultPelayanan = mysqli_query($this->con, $queryPelayanan);
        
        // Array untuk menyimpan data pelayanan
        $orderLayanans = array();
        
        // Melakukan iterasi untuk setiap baris hasil query
        while ($rowPel = mysqli_fetch_assoc($resultPelayanan)) {
            $orderLayanans[$rowPel['id_booking']][] = $rowPel['id_pelayanan'];
        }
        
        // Melakukan iterasi untuk setiap baris hasil query
        while ($row = mysqli_fetch_assoc($result)) {
            // Mendapatkan ID booking
            $id_booking = $row['id_booking'];
            
            // Inisialisasi array untuk menyimpan data pelayanan
            $order_layanans = array();
            
            // Periksa jika ada data pelayanan untuk booking saat ini
            if (isset($orderLayanans[$id_booking])) {
                foreach ($orderLayanans[$id_booking] as $id_pelayanan) {
                    // Query untuk mendapatkan nama pelayanan berdasarkan ID
                    $queryPelayanan = "SELECT nama FROM pelayanan WHERE id_pelayanan = $id_pelayanan";
                    $resultPelayanan = mysqli_query($this->con, $queryPelayanan);
                    
                    // Mendapatkan data pelayanan
                    $DataPel = mysqli_fetch_assoc($resultPelayanan);
                    
                    // Menyimpan nama pelayanan ke dalam array
                    $order_layanans[] = $DataPel['nama'];
                }
            }
            
            // Menggabungkan nama pelayanan menjadi satu string dengan pemisah koma
            $row['order_layanan'] = implode(', ', $order_layanans);
            
            // Query untuk mendapatkan data pengguna (user)
            $queryUser = "SELECT username, no_telp FROM user WHERE userID = '$row[userID]'";
            $resultUser = mysqli_query($this->con, $queryUser);
            
            // Mendapatkan data pengguna (user)
            $userData = mysqli_fetch_assoc($resultUser);
            
            // Menyimpan nama dan nomor telepon pengguna ke dalam array
            $row['nama_booking'] = $userData['username'];
            $row['nomerhp_booking'] = $userData['no_telp'];
            
            // Menambahkan data booking ke dalam array
            $booking[] = $row;
        }
        
        // Mengembalikan data pemesanan
        return $booking;
    }
    public function getBookedHistoryByRange($tanggalAwal, $tanggalAkhir) {
        if (!strtotime($tanggalAwal) || !strtotime($tanggalAkhir)) {
            // Tanggapan jika tanggal tidak valid
            return array("error" => "Invalid date format. Please provide a valid date.");
        }

        $queryPelayanan = "SELECT id_booking, id_pelayanan FROM order_layanan";
        $resultPelayanan = mysqli_query($this->con, $queryPelayanan);
        
        $orderLayanans = array();
        while ($rowPel = mysqli_fetch_assoc($resultPelayanan)) {
            $orderLayanans[$rowPel['id_booking']][] = $rowPel['id_pelayanan'];
        }

        $query = "SELECT * FROM booking WHERE tanggal_booking >= '$tanggalAwal' AND tanggal_booking <= '$tanggalAkhir' AND harga_booking != 0";
        $result = mysqli_query($this->con, $query);
        $booking = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $id_booking = $row['id_booking'];
            $order_layanans = array();
            // Periksa jika ada data pelayanan untuk booking saat ini
            if (isset($orderLayanans[$id_booking])) {
                foreach ($orderLayanans[$id_booking] as $id_pelayanan) {
                    $DataPel = mysqli_fetch_assoc(mysqli_query($this->con, "SELECT nama FROM pelayanan WHERE id_pelayanan=$id_pelayanan"));
                    $order_layanans[] = $DataPel['nama'];
                }
            }
            $row['order_layanan'] = implode(', ', $order_layanans);
            $cari = "SELECT username,no_telp FROM user WHERE userID = '$row[userID]'";
            $koneksi = mysqli_query($this->con, $cari);
            $ketemu = mysqli_fetch_assoc($koneksi);
            $row['nama_booking'] = $ketemu['username'];
            $row['nomerhp_booking'] = $ketemu['no_telp'];
            $booking[] = $row;
        }

        return $booking;
    }
    
    public function getBookedTimes($tanggal) {
        // Validasi tanggal
        if (!strtotime($tanggal)) {
            // Tanggapan jika tanggal tidak valid
            return array("error" => "Invalid date format. Please provide a valid date.");
        }
    
        // Gunakan prepared statement untuk mencegah serangan injeksi SQL
        $query = "SELECT waktu_booking FROM booking WHERE tanggal_booking = ?";
        $stmt = mysqli_prepare($this->con, $query);
    
        if ($stmt) {
            // Bind parameter tanggal ke prepared statement
            mysqli_stmt_bind_param($stmt, "s", $tanggal);
    
            // Eksekusi prepared statement
            mysqli_stmt_execute($stmt);
    
            // Ambil hasil kueri
            mysqli_stmt_bind_result($stmt, $waktu_booking);
    
            $booked_times = array();
            // Loop melalui hasil kueri
            while (mysqli_stmt_fetch($stmt)) {
                $booked_times[] = $waktu_booking;
            }
    
            // Tutup prepared statement
            mysqli_stmt_close($stmt);
    
            return $booked_times;
        } else {
            // Tanggapan jika kueri gagal dieksekusi
            return array("error" => "Unable to execute query.");
        }
    }
    
    public function addBooking($data) {
        $nama = $data['nama_booking'];
        $telp = $data['nomerhp_booking'];
        $waktu = $data['waktu'];
        $tanggal = $data['tanggal'];
        $pesan = $data['pesan'];

        $cari = "SELECT userID FROM user WHERE username = '$nama' AND no_telp ='$telp'";
        $koneksi = mysqli_query($this->con, $cari);
        $ketemu = mysqli_fetch_assoc($koneksi);
        if($ketemu){
            $query =  "INSERT INTO booking (userID, waktu_booking, tanggal_booking, pesan_booking, harga_booking) VALUES ('$ketemu[userID]', '$waktu', '$tanggal', '$pesan', '')";
            $result = mysqli_query($this->con, $query);
            if ($result) {
                return true;
            } else {
                return false;
            }
        } else {
            $add =  "INSERT INTO user (username, no_telp, userRole, password) VALUES ('$nama', '$telp', 'pelanggan', '')";
            $berhasil = mysqli_query($this->con, $add);
            if ($berhasil) {
                $userID = mysqli_insert_id($this->con);
                $query =  "INSERT INTO booking (userID, waktu_booking, tanggal_booking, pesan_booking, harga_booking) VALUES ('$userID', '$waktu', '$tanggal', '$pesan', '')";
                $result = mysqli_query($this->con, $query);
                if ($result) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }
    public function addBookingAdmin($data){
        $nama = $data['nama_booking'];
        $telp = $data['nomerhp_booking'];
        $waktu = $data['waktu'];
        $harga = $data['harga'];
        $tanggal = $data['tanggal'];
        $pesan = $data['pesan'];

        $cari = "SELECT userID FROM user WHERE username = '$nama' AND no_telp ='$telp'";
        $koneksi = mysqli_query($this->con, $cari);
        $ketemu = mysqli_fetch_assoc($koneksi);

        // Menangani layanan yang dipilih
        $selectedServices = isset($data['selectedServices']) ? $data['selectedServices'] : [];

        if($ketemu){
            // Memasukkan data booking ke dalam tabel booking
            $query_booking = "INSERT INTO booking (userID, waktu_booking, tanggal_booking, pesan_booking, harga_booking) VALUES ('$ketemu[userID]', '$waktu', '$tanggal', '$pesan', '$harga')";
            $result_booking = mysqli_query($this->con, $query_booking);

            if ($result_booking) {
                // Ambil ID booking yang baru saja dimasukkan
                $booking_id = mysqli_insert_id($this->con);

                // Memasukkan data layanan ke dalam tabel order_layanan
                foreach ($selectedServices as $service) {
                    $query_order_layanan = "INSERT INTO order_layanan (id_booking, id_pelayanan) VALUES ('$booking_id', '$service')";
                    $result_order_layanan = mysqli_query($this->con, $query_order_layanan);

                    if (!$result_order_layanan) {
                        // Jika gagal memasukkan data ke dalam order_layanan, rollback proses memasukkan booking
                        mysqli_query($this->con, "DELETE FROM booking WHERE id_booking = '$booking_id'");
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
        } else {
            $add =  "INSERT INTO user (username, no_telp, userRole, password) VALUES ('$nama', '$telp', 'pelanggan', '')";
            $berhasil = mysqli_query($this->con, $add);
            if ($berhasil) {
                $userID = mysqli_insert_id($this->con);
                $query_booking = "INSERT INTO booking (userID, waktu_booking, tanggal_booking, pesan_booking, harga_booking) VALUES ('$userID', '$waktu', '$tanggal', '$pesan', '$harga')";
                $result_booking = mysqli_query($this->con, $query_booking);
    
                if ($result_booking) {
                    // Ambil ID booking yang baru saja dimasukkan
                    $booking_id = mysqli_insert_id($this->con);
    
                    // Memasukkan data layanan ke dalam tabel order_layanan
                    foreach ($selectedServices as $service) {
                        $query_order_layanan = "INSERT INTO order_layanan (id_booking, id_pelayanan) VALUES ('$booking_id', '$service')";
                        $result_order_layanan = mysqli_query($this->con, $query_order_layanan);
    
                        if (!$result_order_layanan) {
                            // Jika gagal memasukkan data ke dalam order_layanan, rollback proses memasukkan booking
                            mysqli_query($this->con, "DELETE FROM booking WHERE id_booking = '$booking_id'");
                            return false;
                        }
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    public function updateBooking($id, $data){
        $nama = $data['nama_booking'];
        $telp = $data['nomerhp_booking'];
        $waktu = $data['waktu'];
        $harga = $data['harga'];
        $tanggal = $data['tanggal'];
        $pesan = $data['pesan'];

        $cari = "SELECT userID FROM user WHERE username = '$nama' AND no_telp ='$telp'";
        $koneksi = mysqli_query($this->con, $cari);
        $ketemu = mysqli_fetch_assoc($koneksi);

        // Menangani layanan yang dipilih
        $selectedServices = isset($data['selectedServices']) ? $data['selectedServices'] : [];
        if($ketemu){
            // Memasukkan data booking ke dalam tabel booking
            $query_booking = "UPDATE booking SET userID = '$ketemu[userID]', waktu_booking = '$waktu' , tanggal_booking = '$tanggal' , pesan_booking = '$pesan', harga_booking = '$harga' WHERE id_booking = '$id' ";
            $result_booking = mysqli_query($this->con, $query_booking);

            if ($result_booking) {
                $query_order_layanan = "DELETE FROM order_layanan WHERE id_booking = '$id'";
                mysqli_query($this->con, $query_order_layanan);
                foreach ($selectedServices as $service) {
                    $query_order_layanan = "INSERT INTO order_layanan (id_booking, id_pelayanan) VALUES ('$id', '$service')";
                    mysqli_query($this->con, $query_order_layanan);
                }
                return true;
            } else {
                return false;
            }
        }
        else {
            $add =  "INSERT INTO user (username, no_telp, userRole, password) VALUES ('$nama', '$telp', 'pelanggan', '')";
            $berhasil = mysqli_query($this->con, $add);
            if ($berhasil) {
                $userID = mysqli_insert_id($this->con);
                $query_booking = "UPDATE booking SET userID = '$userID', waktu_booking = '$waktu' , tanggal_booking = '$tanggal' , pesan_booking = '$pesan', harga_booking = '$harga' WHERE id_booking = '$id' ";
                $result_booking = mysqli_query($this->con, $query_booking);

                if ($result_booking) {
                    $query_order_layanan = "DELETE FROM order_layanan WHERE id_booking = '$id'";
                    mysqli_query($this->con, $query_order_layanan);
                    foreach ($selectedServices as $service) {
                        $query_order_layanan = "INSERT INTO order_layanan (id_booking, id_pelayanan) VALUES ('$id', '$service')";
                        mysqli_query($this->con, $query_order_layanan);
                    }
                    return true;
                } else {
                    return false;
                }        
            } else {
                return false;
            }

        }
    }

    public function deleteBooking($id){
        $query = "DELETE FROM order_layanan WHERE id_booking = $id";
        $result = mysqli_query($this->con, $query);
        if ($result) {
            $query_order_layanan = "DELETE FROM booking WHERE id_booking = $id";
            mysqli_query($this->con, $query_order_layanan);
            return true;
        } else {
            return false;
        }
    }
}