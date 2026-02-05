<?php
session_start();
require '../config/database.php';

// Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    exit("Akses ditolak");
}

if (isset($_GET['id'])) {
    $id_booking = $_GET['id'];

    // 1. Ambil data booking untuk cek kolom bukti_bayar
    $query_cek = mysqli_query($db, "SELECT bukti_bayar FROM booking WHERE id_booking = '$id_booking'");
    $data = mysqli_fetch_assoc($query_cek);

    // 2. VALIDASI: Jika bukti_bayar kosong atau tidak ada file-nya
    if (empty($data['bukti_bayar'])) {
        echo "<script>
                alert('GAGAL KONFIRMASI! User ini belum mengunggah bukti pembayaran (Struk).');
                window.location='konfirmasi.php';
              </script>";
    } else {
        // 3. Jika bukti ADA, baru update status ke Lunas
        $update = mysqli_query($db, "UPDATE booking SET status = 'Lunas' WHERE id_booking = '$id_booking'");
        
        if ($update) {
            echo "<script>
                    alert('Konfirmasi Berhasil! Status sekarang LUNAS.');
                    window.location='konfirmasi.php';
                  </script>";
        } else {
            echo "<script>alert('Error: Gagal memperbarui database.'); window.location='konfirmasi.php';</script>";
        }
    }
}
?>