<?php
require '../config/database.php';

$tgl_mulai = $_GET['mulai'];
$tgl_sampai = $_GET['sampai'];

// Nama file saat didownload
$filename = "Laporan_GlorySport_" . $tgl_mulai . ".xls";

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=$filename");
?>

<table border="1">
    <tr>
        <th colspan="5" style="background-color: #000; color: #fff; height: 30px;">
            LAPORAN PENDAPATAN GLORY SPORT CENTER
        </th>
    </tr>
    <tr>
        <th colspan="5">Periode: <?= $tgl_mulai ?> s/d <?= $tgl_sampai ?></th>
    </tr>
    <tr style="background-color: #e67e22; color: #fff; font-weight: bold;">
        <th>No</th>
        <th>Tanggal Main</th>
        <th>Nama Pelanggan</th>
        <th>Arena / Lapangan</th>
        <th>Nominal (Rp)</th>
    </tr>
    <?php
    $no = 1; $total = 0;
    $sql = "SELECT b.*, u.nama_lengkap, l.nama_lapangan 
            FROM booking b 
            JOIN user u ON b.id_user = u.id_user 
            JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
            WHERE b.status = 'Lunas' 
            AND b.tgl_main BETWEEN '$tgl_mulai' AND '$tgl_sampai'";
    $query = mysqli_query($db, $sql);
    
    while($row = mysqli_fetch_array($query)):
        $total += $row['total_harga'];
    ?>
    <tr>
        <td align="center"><?= $no++; ?></td>
        <td><?= $row['tgl_main'] ?></td>
        <td><?= strtoupper($row['nama_lengkap']) ?></td>
        <td><?= $row['nama_lapangan'] ?></td>
        <td><?= $row['total_harga'] ?></td>
    </tr>
    <?php endwhile; ?>
    <tr style="background-color: #eee; font-weight: bold;">
        <td colspan="4" align="right">GRAND TOTAL</td>
        <td><?= $total ?></td>
    </tr>
</table>