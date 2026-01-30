<?php
session_start();
require '../config/database.php';

// Cek akses admin
if ($_SESSION['role'] != 'admin') exit;

// Ambil parameter tanggal dari URL
$tgl_awal = $_GET['awal'];
$tgl_akhir = $_GET['akhir'];

// Header untuk Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=Laporan_GlorySport_$tgl_awal.xls");

$query = mysqli_query($conn, "SELECT b.*, u.nama_lengkap, l.nama_lapangan 
                              FROM booking b 
                              JOIN user u ON b.id_user = u.id_user 
                              JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
                              WHERE b.status = 'Lunas' 
                              AND b.tgl_main BETWEEN '$tgl_awal' AND '$tgl_akhir'");
?>

<center><h2>LAPORAN PENDAPATAN GLORY SPORT</h2></center>
<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Booking</th>
            <th>Tanggal Main</th>
            <th>Pelanggan</th>
            <th>Lapangan</th>
            <th>Total Bayar</th>
        </tr>
    </thead>
    <tbody>
        <?php $no=1; $total=0; while($row = mysqli_fetch_assoc($query)): ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= $row['kode_booking'] ?></td>
            <td><?= $row['tgl_main'] ?></td>
            <td><?= $row['nama_lengkap'] ?></td>
            <td><?= $row['nama_lapangan'] ?></td>
            <td><?= $row['total_harga'] ?></td>
        </tr>
        <?php $total += $row['total_harga']; endwhile; ?>
        <tr>
            <th colspan="5">TOTAL PENDAPATAN</th>
            <th><?= $total ?></th>
        </tr>
    </tbody>
</table>