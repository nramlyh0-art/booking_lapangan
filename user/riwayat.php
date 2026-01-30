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
                                    ORDER BY b.tgl_booking DESC");
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
        .header-section h1 { font-weight: 900; font-size: 3.5rem; text-transform: uppercase; letter-spacing: -1px; }
        .header-section h1 span { color: #ff416c; }
        .btn-kembali { border: 2px solid white; color: white; border-radius: 50px; padding: 8px 30px; font-weight: 700; text-decoration: none; text-transform: uppercase; transition: 0.3s; }
        .btn-kembali:hover { background: white; color: black; }

        /* Desain Card Glassmorphism Premium */
        .booking-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 25px; padding: 30px; margin-bottom: 30px;
            position: relative; transition: 0.3s ease;
        }
        .booking-card:hover { transform: translateY(-10px); border-color: #ff416c; }
        .booking-card::before {
            content: ''; position: absolute; left: 0; top: 20%; bottom: 20%; width: 5px; background: #ff416c; border-radius: 0 5px 5px 0;
        }

        .status-badge {
            position: absolute; top: 25px; right: 25px; padding: 5px 15px; border-radius: 20px;
            font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;
        }
        .kode-text { color: rgba(255, 255, 255, 0.4); font-size: 0.85rem; font-weight: 600; margin-bottom: 10px; }
        .lapangan-title { font-weight: 900; font-size: 1.6rem; text-transform: uppercase; margin-bottom: 20px; }
        .label-small { color: rgba(255, 255, 255, 0.4); font-size: 0.75rem; text-transform: uppercase; font-weight: 700; margin-bottom: 5px; }
        .value-bold { font-weight: 700; font-size: 1.1rem; }
        .value-red { color: #ff416c; font-weight: 700; font-size: 1.1rem; }
        .price-tag { font-weight: 900; font-size: 1.8rem; margin-top: 20px; }

        /* Tombol Chat Admin WhatsApp */
        .btn-wa {
            background: #25d366; color: white; border-radius: 12px; padding: 12px;
            font-weight: 700; text-decoration: none; display: flex; align-items: center;
            justify-content: center; gap: 10px; transition: 0.3s; margin-top: 20px; border: none; width: 100%;
        }
        .btn-wa:hover { background: #1eb954; color: white; transform: scale(1.02); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand text-white fw-bold" href="#">GLORY <span style="color: #ff416c;">SPORT</span></a>
        <div class="ms-auto d-flex align-items-center small fw-bold">
            <a href="booking.php" class="text-white text-decoration-none me-3">BOOKING</a>
            <a href="dashboard.php" class="text-white text-decoration-none me-3">DASHBOARD</a>
            <span class="text-white-50">| <?= $nama_user ?></span>
        </div>
    </div>
</nav>

<div class="container">
    <div class="header-section d-flex justify-content-between align-items-end">
        <div>
            <h1>RIWAYAT <span>BOOKING</span></h1>
            <p class="text-white-50 m-0">Cek status dan detail reservasi lapangan Anda.</p>
        </div>
        <a href="dashboard.php" class="btn-kembali">KEMBALI</a>
    </div>

    <div class="row">
        <?php 
        if ($query_riwayat && mysqli_num_rows($query_riwayat) > 0):
            while($row = mysqli_fetch_assoc($query_riwayat)): 
                // Konfigurasi WhatsApp
                $no_admin = "6289509935256";
                $pesan_wa = "🔔 *KONFIRMASI PEMBAYARAN* 🔔\n\nHalo Admin, user *{$nama_user}* baru saja mengunggah bukti bayar.\n🎫 *Kode:* {$row['kode_booking']}\n\nSilakan cek Dashboard Admin!";
                $wa_url = "https://wa.me/{$no_admin}?text=" . urlencode($pesan_wa);
                
                // Warna Status Dinamis
                $status = $row['status'];
                $bg_status = "#007bff"; // Menunggu
                if($status == 'Berhasil' || $status == 'DIKONFIRMASI') $bg_status = "#28a745";
                if($status == 'Dibatalkan' || $status == 'DIBATALKAN') $bg_status = "#dc3545";
        ?>
            <div class="col-md-6 col-lg-4">
                <div class="booking-card">
                    <span class="status-badge" style="background: <?= $bg_status ?>;"><?= $status ?></span>
                    <div class="kode-text">#<?= $row['kode_booking'] ?></div>
                    <div class="lapangan-title"><?= $row['nama_lapangan'] ?></div>
                    
                    <div class="row g-0">
                        <div class="col-6">
                            <div class="label-small">Tanggal</div>
                            <div class="value-bold"><?= date('d M Y', strtotime($row['tgl_main'])) ?></div>
                        </div>
                        <div class="col-6 text-end">
                            <div class="label-small">Durasi</div>
                            <div class="value-red"><?= substr($row['jam_mulai'],0,5) ?> - <?= substr($row['jam_selesai'],0,5) ?> WIB</div>
                        </div>
                    </div>

                    <div class="price-tag">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></div>
                    
                    <?php if($status != 'Berhasil' && $status != 'Dibatalkan'): ?>
                        <a href="<?= $wa_url ?>" target="_blank" class="btn-wa">
                            <i class="fab fa-whatsapp fa-lg"></i> CHAT ADMIN
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-calendar-times fa-4x text-white-50 mb-3"></i>
                <h4 class="text-white-50">Belum ada riwayat booking.</h4>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>