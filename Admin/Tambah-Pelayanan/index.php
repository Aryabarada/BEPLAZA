<?php include_once("../../layout/navbaradmin.php");

if (!isset($_SESSION['login_admin'])) {
    $_SESSION['warning'] = "Anda tidak memiliki hak akses admin";
    header('Location: ../../Beranda/');
    exit();
}
include_once("../../layout/koneksi.php");



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
    <div class="row ">
        <center>
            <div class="col-xl-9 align-center col-lg-9 col-md-12 col-sm-12 col-11 bg-white p-5 rounded">
                <h3 class="text-primary">Tambah Pelayanan</h3>
                <form role="form" action="../../layout/function.php" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Pelayanan" required>
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" id="message" name="keterangan" placeholder="Keterangan" required
                            data-validation-required-message="Silahkan masukkan keterangan"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" placeholder="Harga Pelayanan"
                            value="10000" required>
                    </div>

                    <div class="form-group">
                        <label>Gambar</label>
                        <input type="file" name="gambar" class="form-control" accept="image/*"
                            onchange="previewImage(event)" required>
                    </div>

                    <div class="form-group">
                        <img id="preview" src="#" alt="Preview Gambar"
                            style="max-width: 100%; height: auto; display: none;" required>
                    </div>

                    <button type="submit" name="tambahlayanan"
                        class="btn btn-primary text-white btn-block fw-bold text-black fs-5">Tambah Layanan</button>

                </form>

            </div>
        </center>

    </div>

</div>
</div>
</body>
<script>
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var output = document.getElementById('preview');
        output.src = reader.result;
        output.style.display = 'block'; // Menampilkan gambar setelah dipilih
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('.dtabel').DataTable();
});
</script>

</html>