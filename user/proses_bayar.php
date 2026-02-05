<?php
session_start();
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_booking = $_POST['id_booking'];
    $nama_file = $_FILES['bukti_bayar']['name'];
    $tmp_file = $_FILES['bukti_bayar']['tmp_name'];
    
    // Beri nama unik pada file
    $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_baru = "BUKTI_" . $id_booking . "_" . time() . "." . $ekstensi;
    $path = "../assets/bukti_bayar/" . $nama_baru;

    if (move_uploaded_file($tmp_file, $path)) {
        // Update database (Ubah status jadi Menunggu agar diverifikasi admin)
        mysqli_query($db, "UPDATE booking SET bukti_bayar = '$nama_baru', status = 'Menunggu' WHERE id_booking = '$id_booking'");
        header("Location: riwayat.php?pesan=berhasil");
    } else {
        echo "Gagal mengunggah gambar.";
    }
}