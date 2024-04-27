<?php
class Booking
{
    private $con;
    public function __construct($con)
    {
        $this->con = $con;
    }
    public function getAllBooking()
    {
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
                    $DataPel = mysqli_fetch_assoc(mysqli_query($this->con, "SELECT nama FROM pelayanan WHERE id=$id_pelayanan"));
                    $order_layanans[] = $DataPel['nama'];
                }
            }
            $row['order_layanan'] = implode(', ', $order_layanans);
            $booking[] = $row;
        }
        
        return $booking;
        
        
    }
    public function getBookingById($id)
    {
        $query = "SELECT * FROM booking WHERE id_booking = $id";
        $result = mysqli_query($this->con, $query);
        $booking = mysqli_fetch_assoc($result);
        return $booking;
    }
    public function getBookedTimes($tanggal)
    {
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
    
    public function addBooking($data)
    {
        $nama = $data['nama_booking'];
        $telp = $data['nomerhp_booking'];
        $waktu = $data['waktu'];
        $tanggal = $data['tanggal'];
        $pesan = $data['pesan'];
            $query =  "INSERT INTO booking (nama_booking, nomerhp_booking, waktu_booking, tanggal_booking, pesan_booking, harga_booking) VALUES ('$nama', '$telp', '$waktu', '$tanggal', '$pesan', '')";
        
            $result = mysqli_query($this->con, $query);
            if ($result) {
                return true;
            } else {
                return false;
            }
    }
    public function addBookingAdmin($data)
    {
        $nama = $data['nama_booking'];
        $telp = $data['nomerhp_booking'];
        $waktu = $data['waktu'];
        $harga = $data['harga'];
        $tanggal = $data['tanggal'];
        $pesan = $data['pesan'];

        // Menangani layanan yang dipilih
        $selectedServices = isset($data['selectedServices']) ? $data['selectedServices'] : [];

        // Memasukkan data booking ke dalam tabel booking
        $query_booking = "INSERT INTO booking (nama_booking, nomerhp_booking, waktu_booking, tanggal_booking, pesan_booking, harga_booking) VALUES ('$nama', '$telp', '$waktu', '$tanggal', '$pesan', '$harga')";
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
                    mysqli_query($this->con, "DELETE FROM booking WHERE id = '$booking_id'");
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function updateBooking($id, $data)
    {

        $nama = $data['nama_booking'];
        $telp = $data['nomerhp_booking'];
        $waktu = $data['waktu'];
        $harga = $data['harga'];
        $tanggal = $data['tanggal'];
        $pesan = $data['pesan'];

        // Menangani layanan yang dipilih
        $selectedServices = isset($data['selectedServices']) ? $data['selectedServices'] : [];

        // Memasukkan data booking ke dalam tabel booking
        $query_booking = "UPDATE booking SET nama_booking = '$nama', nomerhp_booking = '$telp', waktu_booking = '$waktu' , tanggal_booking = '$tanggal' , pesan_booking = '$pesan', harga_booking = '$harga' WHERE id_booking = '$id' ";
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

    public function deleteBooking($id)
    {
        $query = "DELETE FROM booking WHERE id_booking = $id";
        $result = mysqli_query($this->con, $query);
        if ($result) {
            $query_order_layanan = "DELETE FROM order_layanan WHERE id_booking = $id";
            mysqli_query($this->con, $query_order_layanan);
            return true;
        } else {
            return false;
        }
    }



}