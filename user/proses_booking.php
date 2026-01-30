<?php
session_start();
require_once '../config/database.php';

if (isset($_POST['pesan'])) {
    // Pastikan user sudah login
    if (!isset($_SESSION['id_user'])) {
        die("Akses ditolak. Silakan login terlebih dahulu.");
    }

    $id_user = $_SESSION['id_user'];
    $id_lapangan = mysqli_real_escape_string($db, $_POST['id_lapangan']);
    $tgl_main = mysqli_real_escape_string($db, $_POST['tgl_main']);
    $jam_mulai = mysqli_real_escape_string($db, $_POST['jam_mulai']);
    $durasi = (int)$_POST['durasi'];
    
    // 1. GENERATE KODE BOOKING UNIK (Contoh: GLR-20240520-X8Y1)
    $prefix = "GLRY";
    $date_part = date('Ymd');
    $random_part = strtoupper(substr(md5(uniqid(rand(), true)), 0, 4));
    $kode_booking = $prefix . "-" . $date_part . "-" . $random_part;
    
    // 2. HITUNG JAM SELESAI
    $jam_selesai = date('H:i', strtotime("+$durasi hour", strtotime($jam_mulai)));
    $tgl_booking = date('Y-m-d H:i:s');

    // 3. CEK KETERSEDIAAN REAL-TIME (Anti Double Booking)
    // Logika: Cek apakah ada jadwal yang bertabrakan di lapangan dan tanggal yang sama
    $cek_jadwal = mysqli_query($db, "SELECT * FROM booking 
        WHERE id_lapangan = '$id_lapangan' AND tgl_main = '$tgl_main' 
        AND status != 'Dibatalkan'
        AND (
            ('$jam_mulai' >= jam_mulai AND '$jam_mulai' < jam_selesai) OR 
            ('$jam_selesai' > jam_mulai AND '$jam_selesai' <= jam_selesai) OR
            (jam_mulai >= '$jam_mulai' AND jam_mulai < '$jam_selesai')
        )");

    if (mysqli_num_rows($cek_jadwal) > 0) {
        echo "<script>alert('Maaf, jam tersebut sudah dibooking. Silakan pilih jam lain.'); window.history.back();</script>";
        exit;
    }

    // 4. AMBIL HARGA DARI DATABASE
    $q_lap = mysqli_query($db, "SELECT harga_per_jam FROM lapangan WHERE id_lapangan = '$id_lapangan'");
    $d_lap = mysqli_fetch_assoc($q_lap);
    
    if (!$d_lap) {
        die("Data lapangan tidak ditemukan.");
    }

    $total_harga = $d_lap['harga_per_jam'] * $durasi;

    // 5. SIMPAN KE DATABASE
    $query_simpan = "INSERT INTO booking (id_user, id_lapangan, kode_booking, tgl_booking, tgl_main, jam_mulai, jam_selesai, durasi, total_harga, status) 
                     VALUES ('$id_user', '$id_lapangan', '$kode_booking', '$tgl_booking', '$tgl_main', '$jam_mulai', '$jam_selesai', '$durasi', '$total_harga', 'Menunggu Pembayaran')";

    if (mysqli_query($db, $query_simpan)) {
        // Berhasil simpan, arahkan ke konfirmasi bayar
        header("Location: konfirmasi_bayar.php?kode=$kode_booking");
        exit;
    } else {
        echo "Gagal menyimpan booking: " . mysqli_error($db);
    }
} else {
    // Jika akses file ini tanpa melalui POST pesan
    header("Location: booking.php");
    exit;
}
?>