<?php
session_start();
require_once '../config/database.php';

// 1. Proteksi Login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth.php");
    exit;
}

// 2. Ambil ID Booking dari URL
$id_booking = mysqli_real_escape_string($db, $_GET['id'] ?? '');

if (empty($id_booking)) {
    header("Location: riwayat.php");
    exit;
}

// 3. Ambil Detail Data Booking
$query = mysqli_query($db, "SELECT b.*, l.nama_lapangan 
                            FROM booking b 
                            JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
                            WHERE b.id_booking = '$id_booking' AND b.id_user = '{$_SESSION['id_user']}'");
$data = mysqli_fetch_assoc($query);

// Jika data tidak ditemukan atau sudah lunas, arahkan balik
if (!$data || !empty($data['bukti_bayar'])) {
    header("Location: riwayat.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selesaikan Pembayaran | GLORY SPORT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.85), rgba(0, 0, 0, 0.85)), 
                        url('../assets/images/bg-stadium.jpg') no-repeat center center fixed;
            background-size: cover; color: white; min-height: 100vh;
            display: flex; align-items: center; justify-content: center; padding: 20px;
        }
        .pay-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px; padding: 40px;
            width: 100%; max-width: 550px; text-align: center;
        }
        .title { font-weight: 900; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 5px; }
        .subtitle { color: rgba(255,255,255,0.5); font-size: 0.9rem; margin-bottom: 30px; }
        
        .bank-card {
            background: #ff416c; border-radius: 15px; padding: 20px;
            margin-bottom: 25px; display: flex; align-items: center; justify-content: center; gap: 15px;
        }
        .total-box { margin: 30px 0; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; }
        .total-label { font-size: 0.8rem; color: rgba(255,255,255,0.6); text-transform: uppercase; font-weight: 700; }
        .total-amount { font-size: 2.2rem; font-weight: 900; color: #ff416c; }

        .upload-area {
            border: 2px dashed rgba(255,255,255,0.2); border-radius: 20px;
            padding: 30px; cursor: pointer; transition: 0.3s; position: relative;
        }
        .upload-area:hover { border-color: #ff416c; background: rgba(255,65,108,0.05); }
        #preview { width: 100%; border-radius: 15px; display: none; margin-top: 15px; }

        .btn-submit {
            background: #ff416c; color: white; border: none; width: 100%;
            padding: 15px; border-radius: 15px; font-weight: 700; margin-top: 25px;
            text-transform: uppercase; transition: 0.3s;
        }
        .btn-submit:hover { background: #ff1f50; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(255,65,108,0.3); }
        .btn-back { color: rgba(255,255,255,0.4); text-decoration: none; font-size: 0.8rem; margin-top: 20px; display: inline-block; }
        .btn-back:hover { color: white; }
    </style>
</head>
<body>

<div class="pay-container">
    <h2 class="title">Selesaikan Pembayaran</h2>
    <p class="subtitle">ID BOOKING #<?= $data['id_booking'] ?> - <?= $data['nama_lapangan'] ?></p>

    <div class="bank-card">
        <i class="fas fa-university fa-2x"></i>
        <div class="text-start">
            <small class="d-block" style="font-size: 0.7rem; opacity: 0.8;">TRANSFER KE REKENING BCA</small>
            <strong style="font-size: 1.2rem;">1234 5678 90</strong>
            <small class="d-block" style="font-size: 0.7rem;">A/N GLORY SPORT MANAGEMENT</small>
        </div>
    </div>

    <div class="total-box">
        <div class="total-label">Total yang harus dibayar</div>
        <div class="total-amount">Rp <?= number_format($data['total_harga'], 0, ',', '.') ?></div>
    </div>

    <form action="proses_bayar.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_booking" value="<?= $data['id_booking'] ?>">
        
        <div class="upload-area" onclick="document.getElementById('bukti_bayar').click()">
            <div id="upload-placeholder">
                <i class="fas fa-cloud-upload-alt fa-3x mb-3" style="color: #ff416c;"></i>
                <p class="m-0 fw-bold">Pilih Bukti Transfer</p>
                <small class="text-white-50">Format: JPG, PNG (Max 2MB)</small>
            </div>
            <img id="preview" src="#" alt="Preview">
            <input type="file" name="bukti_bayar" id="bukti_bayar" hidden required onchange="previewImage(this)">
        </div>

        <button type="submit" class="btn-submit">
            <i class="fas fa-check-circle me-2"></i> Konfirmasi Pembayaran
        </button>
    </form>

    <a href="riwayat.php" class="btn-back">Kembali ke Riwayat Booking</a>
</div>

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview');
        const placeholder = document.getElementById('upload-placeholder');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</body>
</html>