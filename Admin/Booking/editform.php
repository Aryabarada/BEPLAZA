<?php 
include_once("../../layout/navbaradmin.php");
include_once("../../layout/koneksi.php");

// Pastikan parameter 'id' tersedia

$resultpel = mysqli_query($con, "SELECT * FROM pelayanan");

if(isset($_GET['id'])) {
    $id = $_GET['id'];

    
    $resultorder = mysqli_query($con, "SELECT * FROM order_layanan WHERE id_booking=$id");
    
    // Query untuk mendapatkan data booking berdasarkan ID
    $query = "SELECT * FROM booking WHERE id_booking='$id'";
    $result = mysqli_query($con, $query);

    // Periksa apakah query berhasil dieksekusi dan mengembalikan hasil yang valid
    if(mysqli_num_rows($result) > 0) {
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Booking</h3>
                </div>
                <div class="card-body">
                    <form id="bookingForm" action="edit.php" method="post">
                        <?php
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
                        <input type="hidden" name="id" value="<?php echo $row['id_booking']; ?>">
                        <div class="form-group">
                            <label>Nama Booking</label>
                            <input type="text" name="nama" class="form-control"
                                value="<?php echo $row['nama_booking']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nomer HP</label>
                            <input type="text" name="telp" class="form-control"
                                value="<?php echo $row['nomerhp_booking']; ?>" readonly>
                        </div>
                        <style>
                        .form-check {
                            display: flex;
                            align-items: center;
                        }

                        .form-check-input {
                            margin-right: 5px;
                            /* Memberi jarak antara checkbox dan teks */
                        }
                        </style>

                        <div class="form-group">
                            <label>Service</label>
                            <?php 
        foreach ($resultpel as $pel) {

            $check = false;
            foreach ($resultorder as $order) {
                if($order['id_pelayanan']==$pel['id']){
                    $check = true;
                }
            }
        ?>
                            <div class="form-check">
                                <input class="form-check-input service-checkbox" type="checkbox" name="service[]"
                                    value="<?= $pel['id']?>" id="service<?= $pel['id']?>"
                                    data-harga="<?= $pel['harga'] ?>" <?php if($check) { echo 'checked';  } ?>>
                                <label class="form-check-label"
                                    for="service<?= $pel['id']?>"><?= $pel['nama'] .' = Rp. ' . number_format($pel['harga'], 0, ',', '.') ?></label>
                            </div>
                            <?php 
    }?>
                        </div>


                        <div id="total-harga">Total Harga: Rp. 0</div>
                        <div class="form-group">
                            <label>Waktu</label>
                            <input type="time" name="waktu" class="form-control"
                                value="<?php echo $row['waktu_booking']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-control"
                                value="<?php echo $row['tanggal_booking']; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Pesan</label>
                            <input type="text" name="pesan" class="form-control"
                                value="<?php echo $row['pesan_booking']; ?>">
                        </div>
                        <div class="form-group">
                            <button type="submit" name="edit" class="btn btn-primary btn-block"
                                onclick="return validateForm()">Edit
                                Booking</button>
                        </div>
                        <?php
        }
        ?>
                    </form>

                    <script>
                    function validateForm() {
                        var checkboxes = document.querySelectorAll('.service-checkbox');
                        var isChecked = false;

                        checkboxes.forEach(function(checkbox) {
                            if (checkbox.checked) {
                                isChecked = true;
                            }
                        });

                        if (!isChecked) {
                            alert("Pilih minimal satu service.");
                            return false; // Prevent form submission
                        }

                        return true; // Allow form submission
                    }
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php
    } else {
        // Jika query tidak mengembalikan hasil yang valid
        echo "Data booking tidak ditemukan.";
    }
} else {
    // Jika parameter 'id' tidak tersedia
    echo "Parameter 'id' tidak ditemukan.";
}
?>

<script>
// Fungsi untuk memformat angka menjadi format mata uang Rupiah
function formatRupiah(angka) {
    var reverse = angka.toString().split('').reverse().join(''),
        ribuan = reverse.match(/\d{1,3}/g);
    ribuan = ribuan.join('.').split('').reverse().join('');
    return ribuan;
}

// Ambil semua checkbox dengan kelas service-checkbox
var checkboxes = document.querySelectorAll('.service-checkbox');

// Tambahkan event listener untuk setiap checkbox
checkboxes.forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        var totalHarga = 0;

        // Periksa setiap checkbox
        checkboxes.forEach(function(cb) {
            // Jika checkbox dicentang, tambahkan harganya ke totalHarga
            if (cb.checked) {
                totalHarga += parseFloat(cb.getAttribute('data-harga'));
            }
        });

        // Format totalHarga menjadi format Rupiah
        var formattedHarga = 'Rp. ' + formatRupiah(totalHarga.toFixed(0));

        // Update teks di elemen total-harga
        document.getElementById('total-harga').textContent = 'Total Harga: ' + formattedHarga;
    });
});
</script>


<?php include_once("../../layout/footer.php"); ?>