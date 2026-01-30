<?php
session_start();
require_once '../config/database.php';

// Proteksi Login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth.php");
    exit;
}

$kode_booking = $_GET['kode'] ?? '';

// Ambil data booking berdasarkan kode untuk verifikasi tampilan
$query_cek = mysqli_query($db, "SELECT b.*, l.nama_lapangan 
                               FROM booking b 
                               JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
                               WHERE b.kode_booking = '$kode_booking'");
$data = mysqli_fetch_assoc($query_cek);

// Jika kode booking tidak ditemukan di database
if (!$data) {
    echo "<script>alert('Kode Booking tidak valid!'); window.location='booking.php';</script>";
    exit;
}

// Proses Upload Bukti
if (isset($_POST['upload'])) {
    $target_dir = "../assets/bukti_bayar/";
    
    // Buat folder jika belum ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = $_FILES['bukti_transfer']['name'];
    $file_tmp = $_FILES['bukti_transfer']['tmp_name'];
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_ext = ['jpg', 'jpeg', 'png'];

    // Nama file baru agar tidak bentrok (BUKTI-KODEBOOKING.jpg)
    $new_file_name = "BUKTI-" . $kode_booking . "." . $file_ext;
    $target_file = $target_dir . $new_file_name;

    if (in_array($file_ext, $allowed_ext)) {
        if (move_uploaded_file($file_tmp, $target_file)) {
            // Update status di database
            $update = mysqli_query($db, "UPDATE booking SET 
                                        bukti_bayar = '$new_file_name', 
                                        status = 'Menunggu Verifikasi' 
                                        WHERE kode_booking = '$kode_booking'");
            
            if ($update) {
                echo "<script>alert('Bukti transfer berhasil diunggah! Mohon tunggu verifikasi admin.'); window.location='dashboard.php';</script>";
            }
        } else {
            echo "<script>alert('Gagal mengunggah file.');</script>";
        }
    } else {
        echo "<script>alert('Format file harus JPG, JPEG, atau PNG!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pembayaran | GLORY SPORT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.9), rgba(0, 0, 0, 0.9)), 
                        url('../assets/images/bg-stadium.jpg') no-repeat center center fixed;
            background-size: cover; color: white; min-height: 100vh;
        }
        .card-pay {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px; padding: 40px; margin-top: 50px;
        }
        .kode-box {
            background: #ff416c; color: white;
            padding: 10px; border-radius: 10px;
            font-weight: 900; font-size: 1.5rem; letter-spacing: 2px;
        }
        .btn-upload {
            background: #28a745; border: none; color: white;
            font-weight: 700; padding: 12px; width: 100%;
        }
        .instruction { font-size: 0.9rem; color: #ccc; }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card-pay text-center shadow-lg">
                <h2 class="fw-900 mb-2">PEMBAYARAN</h2>
                <p class="text-white-50 mb-4">Silakan selesaikan pembayaran Anda</p>

                <div class="mb-4">
                    <p class="mb-1">Kode Booking Anda:</p>
                    <div class="kode-box d-inline-block px-4"><?= $kode_booking ?></div>
                </div>

                <div class="text-start mb-4 p-3 bg-dark rounded border border-secondary">
                    <h6 class="text-info fw-bold">Detail Tagihan:</h6>
                    <small>Arena: <?= $data['nama_lapangan'] ?></small><br>
                    <small>Total: <strong>Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></strong></small><br>
                    <small>Metode: Transfer Bank (BCA 123-456-789 a/n Glory Sport)</small>
                </div>

                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-4 text-start">
                        <label class="form-label fw-bold">Upload Bukti Transfer (JPG/PNG)</label>
                        <input type="file" name="bukti_transfer" class="form-control bg-transparent text-white" required>
                        <p class="instruction mt-2">*Pastikan gambar terlihat jelas dan struk asli.</p>
                    </div>

                    <button type="submit" name="upload" class="btn btn-upload mb-3">KIRIM KONFIRMASI</button>
                    <a href="dashboard.php" class="text-white-50 text-decoration-none small">Nanti Saja</a>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>