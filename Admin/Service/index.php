<?php include_once("../../layout/navbaradmin.php");

if (!isset($_SESSION['login_admin'])) {
    $_SESSION['warning'] = "Anda tidak memiliki hak akses admin";
    header('Location: ../Beranda/');
    exit();
}
include_once("../../layout/koneksi.php");

$query = "SELECT * FROM pelayanan";

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
            <div
                class="col-xl-10 col-lg-10 col-md-11 col-sm-11 col-11 bg-white p-5 rounded ms-lg-2 mt-5 mt-xl-0 ms-xl-2  ">
                <h3>Daftar Pelayanan Barber</h3>
                <table class="table table-responsive table-hover dtabel">
                    <a href="../Tambah-Pelayanan/" class="btn btn-primary rounded text-white">Tambah Pelayanan</a>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Keterangan</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $row['nama']; ?></td>
                            <td><?php echo $row['keterangan']; ?></td>
                            <td><?php echo $row['harga']; ?></td>
                            <td>
                                <a href="../Edit-Pelayanan/?id=<?php echo $row['id']; ?>" class="btn btn-success"
                                    role="button">Edit</a>
                                <a href="delete.php/?id=<?php echo $row['id']; ?>" class="btn btn-danger"
                                    role="button">Delete</a>
                            </td>
                        </tr>

                        <?php
                        }
                        mysqli_close($con);
                        ?>
                    </tbody>
                </table>
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
    $('.dtabel').DataTable({
        "scrollX": true, // Enable horizontal scrolling if needed
        "scrollCollapse": true, // Enable scroll collapse
        "paging": true, // Enable pagination
        "searching": true, // Enable search box
        "ordering": true, // Enable ordering
        "info": true, // Enable info display
        "autoWidth": false, // Disable auto width calculation
        "columnDefs": [{
            "width": "10%", // Set width for the first column
            "targets": 0 // Target the second column (index 1)
        }, {
            "width": "25%", // Set width for the first column
            "targets": 1 // Target the second column (index 1)
        }, {
            "width": "35%", // Set width for the first column
            "targets": 2 // Target the second column (index 1)
        }, {
            "width": "20%", // Set width for the first column
            "targets": 3 // Target the second column (index 1)
        }, {
            "width": "20%", // Set width for the first column
            "targets": 4 // Target the second column (index 1)
        }, ]
    });
});
</script>




</html>