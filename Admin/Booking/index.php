<?php
include_once("../../layout/navbaradmin.php");
include_once("../../layout/koneksi.php");

$query = "SELECT * FROM booking";
$result = mysqli_query($con, $query);
$result2 = mysqli_query($con, $query);

$querypel = "SELECT * FROM pelayanan";
$resultpel = mysqli_query($con, $querypel);

$resultpelanggan = mysqli_query($con, "SELECT * FROM pelanggan");

$querybo = "SELECT * FROM booking";
$resultbo = mysqli_query($con, $querybo);
?>

<div class="container">
    <div class="alert-container col-11 justify-align-center">
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger col-12 mx-auto text-center p-2 border rounded ">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['warning'])) {
            echo '<div class="alert alert-warning col-12 mx-auto text-center p-2 border rounded ">' . $_SESSION['warning'] . '</div>';
            unset($_SESSION['warning']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success col-12 mx-auto text-center p-2 border rounded ">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>
    </div>
    <div class="row">
        <div class="col-xl-4 align-center col-lg-7 col-md-12 col-sm-12 col-12 bg-white p-5 rounded">
            <h3>Tambah Pelanggan</h3>
            <form role="form" action="../../layout/function.php" method="post">
                <?php 
                $nama_nomor_pelanggan = array();
                while ($rowpelanggan = mysqli_fetch_assoc($resultpelanggan)) {
                    $nama_nomor_pelanggan[$rowpelanggan['username']] = $rowpelanggan['no_telp'];
                }
                ?>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" autocomplete="off" list="datalist_nama" onchange="populateNomor()" required>
                    <datalist id="datalist_nama">
                        <?php
                        foreach ($nama_nomor_pelanggan as $nama => $nomor) {
                            echo "<option value='$nama'>";
                        }
                        ?>
                    </datalist>
                </div>
                <div class="form-group">
                    <label>Nomer Hp</label>
                    <input type="text" name="telp" id="telp" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Service</label>
                    <?php foreach ($resultpel as $pel) {?>
                        <div class="form-check">
                            <input class="form-check-input service-checkbox" type="checkbox" name="service[]" value="<?= $pel['id']?>" id="service<?= $pel['id']?>" data-harga="<?= $pel['harga'] ?>">
                            <label class="form-check-label" for="service<?= $pel['id']?>"><?= $pel['nama'] .' = Rp. ' . number_format($pel['harga'], 0, ',', '.') ?></label>
                        </div>
                    <?php }?>
                </div>
                <div id="total-harga">Total Harga: Rp. 0</div>
                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" required="required" class="form-control" data-validation-required-message="Silahkan masukan tanggal booking" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
                <div class="form-group">
                    <label>Waktu</label>
                    <select id="waktu" name="waktu" required="required" class="form-control" disabled>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pesan</label>
                    <input type="text" name="pesan" class="form-control" required>
                </div>
                <button type="submit" name="bookingadmin" class="btn btn-info btn-block fw-bold text-black fs-5">Tambah Booking</button>
                <a target="_blank" href="Cetak/" class="btn btn-info btn-block fw-bold text-black fs-5">Cetak Daftar Booking</a>
            </form>
        </div>
        <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-11 bg-white p-5 rounded ms-lg-2 mt-5 mt-xl-0 ms-xl-2">
            <h3>Daftar Bookingan Pelanggan</h3>
            <table class="table table-responsive table-hover dtabel" id="bookingTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Nomer Hp</th>
                        <th>Waktu</th>
                        <th>Tanggal</th>
                        <th>Pesan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
            <h3 class="mt-5 pt-5">Daftar Riwayat Potong Rambut</h3>
            <table class="table table-responsive table-hover dtabel">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Nomer Hp</th>
                        <th>Service</th>
                        <th>Waktu</th>
                        <th>Tanggal</th>
                        <th>Pesan</th>
                        <th>Harga</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="bookingTableBody"></tbody>
            </table>
        </div>
    </div>
</div>
</body>
<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script src="fetchBookData.js"></script>

<script>
$(document).ready(function() {
    $('.dtabel').DataTable();
});
</script>
<script>
function populateNomor() {
    var namaInput = document.getElementsByName('nama')[0].value;
    var nomorInput = document.getElementById('telp');
    var nomor = <?php echo json_encode($nama_nomor_pelanggan); ?>;
    nomorInput.value = nomor[namaInput] || '';
}
</script>
<script>
function formatRupiah(angka) {
    var reverse = angka.toString().split('').reverse().join(''),
        ribuan = reverse.match(/\d{1,3}/g);
    ribuan = ribuan.join('.').split('').reverse().join('');
    return ribuan;
}

var checkboxes = document.querySelectorAll('.service-checkbox');

checkboxes.forEach(function(checkbox) {
    checkbox.addEventListener('change', function() {
        var totalHarga = 0;
        checkboxes.forEach(function(cb) {
            if (cb.checked) {
                totalHarga += parseFloat(cb.getAttribute('data-harga'));
            }
        });
        var formattedHarga = 'Rp. ' + formatRupiah(totalHarga.toFixed(0));
        document.getElementById('total-harga').textContent = 'Total Harga: ' + formattedHarga;
    });
});
</script>
<script src="time.js"></script>
</html>
