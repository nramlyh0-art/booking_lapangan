<?php
session_start();
require '../config/database.php';

if (isset($_POST['upload'])) {
    $id_booking = $_POST['id_booking'];
    
    // Konfigurasi Upload
    $nama_file = $_FILES['bukti_bayar']['name'];
    $tmp_file  = $_FILES['bukti_bayar']['tmp_name'];
    $ekstensi  = pathinfo($nama_file, PATHINFO_EXTENSION);
    
    // Beri nama unik agar tidak tertukar
    $nama_baru = "STRUK-" . time() . "." . $ekstensi;
    $path = "../assets/uploads/" . $nama_baru;

    if (move_uploaded_file($tmp_file, $path)) {
        // Update Status jadi 'Menunggu Validasi' agar muncul di dashboard Admin
        mysqli_query($conn, "UPDATE booking SET 
            bukti_bayar = '$nama_baru', 
            status = 'Menunggu Validasi' 
            WHERE id_booking = '$id_booking'");
        
        echo "<script>alert('Bukti Terkirim! Mohon tunggu validasi admin.'); window.location='riwayat.php';</script>";
    } else {
        echo "<script>alert('Gagal Upload Gambar!'); window.history.back();</script>";
    }
}
?>