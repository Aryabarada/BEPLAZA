<?php include_once("../../layout/navbaradmin.php");

if (!isset($_SESSION['login_admin'])) {
    $_SESSION['warning'] = "Anda tidak memiliki hak akses admin";
    header('Location: ../../Beranda/');
    exit();
}
include_once("../../layout/koneksi.php");


	$id = $_GET['id'];


	//query
	$query = "SELECT * FROM pelayanan WHERE id='$id'";
	$result = mysqli_query($con, $query);


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
                <h3 class="text-primary">Edit Pelayanan</h3>
                <form role="form" action="../../layout/function.php" method="post" enctype="multipart/form-data">

                    <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Pelayanan"
                            value="<?= $row['nama'] ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Keterangan</label>
                        <textarea class="form-control" id="message" name="keterangan" placeholder="Keterangan"
                            required="required"
                            data-validation-required-message="Silahkan masukkan keterangan"><?= $row['keterangan'] ?></textarea>
                    </div>
                    <div class="form-group">
                        <label>Harga</label>
                        <input type="number" name="harga" class="form-control" placeholder="Harga Pelayanan"
                            value="<?= $row['harga'] ?>" required>
                    </div>
                    <div class="form-group">
                        <?php if(!empty($row['gambar'])): ?>
                        <img id="gambar-sebelumnya" src="../../Assets/storage/<?= $row['gambar'] ?>"
                            alt="Gambar Layanan" style="max-width: 200px;">
                        <?php else: ?>
                        <p>Tidak ada gambar sebelumnya.</p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Unggah Gambar Baru</label>
                        <input type="file" name="gambar" class="form-control" onchange="previewImage(this);">
                    </div>
                    <button type="submit" name="editlayanan"
                        class="btn btn-primary text-white btn-block fw-bold text-black fs-5">Update Layanan</button>
                    <?php
                        }
                        mysqli_close($con);
                        ?>
                </form>

                <script>
                function previewImage(input) {
                    if (input.files && input.files[0]) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            document.getElementById('gambar-sebelumnya').src = e.target.result;
                        }

                        reader.readAsDataURL(input.files[0]); // Mengubah gambar menjadi URL
                    }
                }
                </script>



            </div>
        </center>

    </div>

</div>
</div>
</body>

<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('.dtabel').DataTable();
});
</script>

</html>