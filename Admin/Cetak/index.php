<?php

include_once("../../layout/koneksi.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak</title>

</head>

<body>
    <h3>Data Booking</h3>
    <table border="1" cellpadding="10" cellspacing="0">
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
            </tr>
        </thead>
        <?php
        $no = 1;
        $booking = mysqli_query($con, "SELECT * FROM booking ORDER BY nama_booking ASC");
        while ($row = mysqli_fetch_assoc($booking)) {
        ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $row['nama_booking']; ?></td>
            <td><?php echo $row['nomerhp_booking']; ?></td>
            <td><?php echo $row['service_booking']; ?></td>
            <td><?php echo $row['waktu_booking']; ?></td>
            <td><?php echo $row['tanggal_booking']; ?></td>
            <td><?php echo $row['pesan_booking']; ?></td>
            <td><?php echo $row['harga_booking']; ?></td>
        </tr>

        <?php
        }
        mysqli_close($con);
        ?>
    </table>

    <script type="text/javascript">
    window.print();
    </script>
</body>

</html>