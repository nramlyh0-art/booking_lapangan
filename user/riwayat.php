<?php
session_start();
require_once '../config/database.php';

// Proteksi Login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$nama_user = $_SESSION['nama_lengkap'] ?? 'User';

// Ambil riwayat booking user
$query_riwayat = mysqli_query($db, "SELECT b.*, l.nama_lapangan 
                                    FROM booking b 
                                    JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
                                    WHERE b.id_user = '$id_user' 
                                    ORDER BY b.id_booking DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Booking | GLORY SPORT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), 
                        url('../assets/images/bg-stadium.jpg') no-repeat center center fixed;
            background-size: cover; color: white; min-height: 100vh;
        }
        .navbar { background: rgba(0, 0, 0, 0.9); border-bottom: 1px solid rgba(255,255,255,0.1); }
        .header-section { margin-top: 50px; margin-bottom: 40px; }
        .header-section h1 { font-weight: 900; font-size: 3rem; text-transform: uppercase; }
        .header-section h1 span { color: #ff416c; }
        
        .booking-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px; padding: 25px; margin-bottom: 25px;
            position: relative; transition: 0.3s;
        }
        
        /* CSS BADGE AGAR TULISAN MUNCUL SEPERTI PUNYA TERE */
        .status-badge {
            position: absolute; 
            top: 20px; 
            right: 20px;
            padding: 8px 15px; 
            border-radius: 50px;
            font-size: 11px; 
            font-weight: 900; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            white-space: nowrap; /* Mencegah tulisan turun ke bawah */
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            z-index: 100;
        }

        .lapangan-title { font-weight: 900; font-size: 1.4rem; text-transform: uppercase; margin: 15px 0; width: 70%; }
        .price-tag { font-weight: 900; font-size: 1.8rem; color: white; margin-top: 10px; }
        
        .btn-action {
            border-radius: 12px; padding: 12px; font-weight: 700;
            text-decoration: none; display: flex; align-items: center;
            justify-content: center; gap: 8px; transition: 0.3s; margin-top: 20px; border: none;
        }
        .btn-bayar { background: #ff416c; color: white; }
        .btn-bayar:hover { background: #ff1f50; color: white; transform: scale(1.02); }
        .btn-wa { background: #25d366; color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="#">GLORY <span style="color: #ff416c;">SPORT</span></a>
    </div>
</nav>

<div class="container">
    <div class="header-section d-flex justify-content-between align-items-center">
        <div>
            <h1>RIWAYAT <span>BOOKING</span></h1>
            <p class="text-white-50">Cek status dan detail reservasi lapangan Anda.</p>
        </div>
        <a href="dashboard.php" class="btn btn-outline-light rounded-pill px-4 fw-bold">KEMBALI</a>
    </div>

    <div class="row">
        <?php if ($query_riwayat && mysqli_num_rows($query_riwayat) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($query_riwayat)): 
                // LOGIKA UTAMA: CEK APAKAH SUDAH UPLOAD BUKTI ATAU BELUM
                $bukti = trim($row['bukti_bayar']);
                $status_db = $row['status'];

                // Jika bukti bayar KOSONG
                if (empty($bukti)) {
                    $label_status = "MENUNGGU PEMBAYARAN";
                    $bg_status = "#ffc107"; // Kuning seperti punya Tere
                    $text_color = "#000";   // Tulisan Hitam
                } else {
                    // Jika sudah ada bukti, gunakan status dari database
                    $label_status = ($status_db == 'Lunas' || $status_db == 'Berhasil' || $status_db == 'DIKONFIRMASI') ? "DIKONFIRMASI" : strtoupper($status_db);
                    $text_color = "#fff";
                    $bg_status = ($label_status == 'DIKONFIRMASI') ? "#007bff" : "#dc3545";
                }
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="booking-card">
                    <span class="status-badge" style="background: <?= $bg_status ?>; color: <?= $text_color ?>;">
                        <?= $label_status ?>
                    </span>

                    <small class="text-white-50 fw-bold">#<?= $row['id_booking'] ?></small>
                    <div class="lapangan-title"><?= $row['nama_lapangan'] ?></div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <small class="d-block text-white-50 small fw-bold">TANGGAL</small>
                            <span class="fw-bold"><?= date('d M Y', strtotime($row['tgl_main'])) ?></span>
                        </div>
                        <div class="text-end">
                            <small class="d-block text-white-50 small fw-bold">DURASI</small>
                            <span class="fw-bold" style="color: #ff416c;"><?= substr($row['jam_mulai'],0,5) ?> - <?= substr($row['jam_selesai'],0,5) ?> WIB</span>
                        </div>
                    </div>

                    <div class="price-tag">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></div>
                    
                    <?php if (empty($bukti) && $status_db != 'Dibatalkan'): ?>
                        <a href="pembayaran.php?id=<?= $row['id_booking'] ?>" class="btn-action btn-bayar">
                            <i class="fas fa-receipt"></i> BAYAR SEKARANG
                        </a>
                    <?php else: ?>
                        <a href="https://wa.me/6289509935256" target="_blank" class="btn-action btn-wa">
                            <i class="fab fa-whatsapp"></i> HUBUNGI ADMIN
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <h4 class="text-white-50">Belum ada riwayat booking.</h4>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>