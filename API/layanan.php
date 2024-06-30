<?php
class Layanan
{
    private $con;
    public function __construct($con)
    {
        $this->con = $con;
    }
    public function getAllLayanan()
    {
        $query = "SELECT * FROM pelayanan";
        $result = mysqli_query($this->con, $query);
        $layanan = [];
        while ($row = mysqli_fetch_assoc($result)) {
            // Ganti nama kolom id_pelayanan menjadi id
            $row['id'] = $row['id_pelayanan'];
            unset($row['id_pelayanan']);
            
            // Tambahkan prefix ke setiap gambar
            $row['gambar'] = "https://beplazabarber.my.id/Storage/" . $row['gambar'];
            
            $layanan[] = $row;
        }
        return $layanan;
    }

    public function getLayananById($id)
    {
        $query = "SELECT * FROM pelayanan WHERE id_pelayanan = $id";
        $result = mysqli_query($this->con, $query);
        $layanan = mysqli_fetch_assoc($result);
        
        if ($layanan) {
            // Ganti nama kolom id_pelayanan menjadi id
            $layanan['id'] = $layanan['id_pelayanan'];
            unset($layanan['id_pelayanan']);
            
            // Tambahkan prefix ke setiap gambar
            $layanan['gambar'] = "https://beplazabarber.my.id/Storage/" . $layanan['gambar'];
        }
    
        return $layanan;
    }

    public function addGambarLayanan($gambarInfo)
    {
        $gambarName = $gambarInfo['name'];
        $gambarTmpName = $gambarInfo['tmp_name'];
    
        $gambarExt = strtolower(pathinfo($gambarName, PATHINFO_EXTENSION));
    
        $queryData = "SELECT * FROM pelayanan ORDER BY id_pelayanan DESC LIMIT 1";
        $resultdata = mysqli_query($this->con, $queryData);
        $row = mysqli_fetch_assoc($resultdata);

        $nama_unik_gambar = $row['nama'].'_' . time() . '.' . $gambarExt;
        $gambar_destination = '../Storage/' . $nama_unik_gambar;
    
        if (!move_uploaded_file($gambarTmpName, $gambar_destination)) {
            return false;
        }
        $query = "UPDATE pelayanan SET gambar = '$nama_unik_gambar' WHERE id_pelayanan = '$row[id_pelayanan]' ";
        $result = mysqli_query($this->con, $query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function addLayanan($data)
    {
        $nama = $data['nama'];
        $keterangan = $data['keterangan'];
        $harga = $data['harga'];
        $tampilkan = $data['tampilkan'];

        $query = "INSERT INTO pelayanan (nama, keterangan, harga, tampilkan) VALUES ('$nama', '$keterangan', '$harga', '$tampilkan')";
        $result = mysqli_query($this->con, $query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updateLayanan($id, $data)
    {
        $nama = $data['nama'];
        $keterangan = $data['keterangan'];
        $harga = $data['harga'];
        $tampilkan = $data['tampilkan'];

        $query = "UPDATE pelayanan SET nama = '$nama', keterangan = '$keterangan', harga = '$harga', tampilkan = '$tampilkan' WHERE id_pelayanan = $id";
        $result = mysqli_query($this->con, $query);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function updateGambarLayanan($id, $gambarInfo)
    {
        $gambarName = $gambarInfo['name'];
        $gambarTmpName = $gambarInfo['tmp_name'];
        $gambarExt = strtolower(pathinfo($gambarName, PATHINFO_EXTENSION));

        $queryData = "SELECT * FROM pelayanan WHERE id_pelayanan = '$id'";
        $resultdata = mysqli_query($this->con, $queryData);
        $row = mysqli_fetch_assoc($resultdata);

        $nama_unik_gambar = $row['nama'].'_UPDATE_' . time() . '.' . $gambarExt;
        $gambar_destination = '../Storage/' . $nama_unik_gambar;

        if (!move_uploaded_file($gambarTmpName, $gambar_destination)) {
            return false;
        }

        if (!empty($row['gambar'])) {
            $gambarPath = '../Storage/' . $row['gambar'];
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
        }

        $query = "UPDATE pelayanan SET gambar = '$nama_unik_gambar' WHERE id_pelayanan = $id";
        $result = mysqli_query($this->con, $query);
        if ($result) {
            return true;
        } else {
            return false;
        }
        
    }
    public function deleteLayanan($id)
{
    // Check if the id is linked to any data in order_layanan table
    $checkQuery = "SELECT COUNT(*) as count FROM order_layanan WHERE id_pelayanan = $id";
    $checkResult = mysqli_query($this->con, $checkQuery);
    $row = mysqli_fetch_assoc($checkResult);
    $count = $row['count'];

    // If there are linked records, do not delete
    if ($count > 0) {
        return "Layanan tidak dapat dihapus karena terhubung dengan riwayat booking.";
    }

    // If no linked records, proceed with deletion
    $query = "SELECT gambar FROM pelayanan WHERE id_pelayanan = $id";
    $result = mysqli_query($this->con, $query);
    $row = mysqli_fetch_assoc($result);
    $gambar = $row['gambar'];

    $deleteQuery = "DELETE FROM pelayanan WHERE id_pelayanan = $id";
    $deleteResult = mysqli_query($this->con, $deleteQuery);

    if ($deleteResult) {
        if (!empty($gambar)) {
            $gambarPath = '../Storage/' . $gambar;
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
        }
        return true;
    } else {
        return false;
    }
}

    
    
    
}