<?php
session_start();
require_once '../config/database.php';

// Proteksi Login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth.php");
    exit;
}

// Ambil ID Lapangan dan Tanggal dari Filter
$id_lapangan = $_GET['id_lapangan'] ?? '';
$tgl_pilih = $_GET['tanggal'] ?? date('Y-m-d');

// Ambil daftar lapangan untuk dropdown
$list_lapangan = mysqli_query($db, "SELECT * FROM lapangan");

// Ambil data booking yang sudah ada
$booked_slots = [];
if ($id_lapangan != '') {
    $query_booked = mysqli_query($db, "SELECT jam_mulai FROM booking 
                                      WHERE id_lapangan = '$id_lapangan' 
                                      AND tgl_main = '$tgl_pilih' 
                                      AND status != 'Dibatalkan'");
    while ($row = mysqli_fetch_assoc($query_booked)) {
        $booked_slots[] = substr($row['jam_mulai'], 0, 5);
    }
}

// Jam Operasional 08:00 - 22:00
$jam_buka = 8;
$jam_tutup = 22;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Lapangan | GLORY SPORT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --glory-red: #ff416c;
            --glass-white: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #000;
            background-image: radial-gradient(circle at top right, rgba(255, 65, 108, 0.1), transparent),
                              radial-gradient(circle at bottom left, rgba(0, 0, 0, 1), transparent);
            color: white;
            min-height: 100vh;
        }

        .navbar { background: #000; border-bottom: 1px solid var(--glass-border); padding: 15px 0; }
        
        .header-title { font-weight: 900; font-size: 3rem; text-transform: uppercase; margin: 50px 0 10px; }
        .header-title span { color: var(--glory-red); }

        .btn-kembali {
            border: 2px solid white; color: white; border-radius: 50px;
            padding: 8px 30px; font-weight: 700; text-decoration: none;
            transition: 0.3s; text-transform: uppercase; font-size: 0.9rem;
        }
        .btn-kembali:hover { background: white; color: black; }

        /* Filter Section */
        .glass-panel {
            background: var(--glass-white);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 25px;
            padding: 35px;
            margin-bottom: 30px;
        }

        .form-label-custom {
            color: rgba(255,255,255,0.4);
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
            display: block;
        }

        .filter-select, .filter-date {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid var(--glass-border) !important;
            color: white !important;
            border-radius: 12px !important;
            padding: 12px 20px !important;
            font-weight: 600;
        }

        /* Slot Grid */
        .slot-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 20px;
        }

        .slot-card {
            background: var(--glass-white);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 25px 15px;
            text-align: center;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-decoration: none !important;
        }

        /* Slot Tersedia */
        .slot-available { border-bottom: 4px solid #28a745; cursor: pointer; }
        .slot-available:hover {
            background: rgba(40, 167, 69, 0.1);
            border-color: #28a745;
            transform: translateY(-5px);
        }
        .slot-available .time { font-size: 1.5rem; font-weight: 900; color: white; }
        .slot-available .status { color: #28a745; font-size: 0.7rem; font-weight: 800; text-transform: uppercase; margin-top: 5px; }

        /* Slot Terpakai */
        .slot-booked {
            border-bottom: 4px solid var(--glory-red);
            opacity: 0.6;
            background: rgba(255, 65, 108, 0.05);
            cursor: not-allowed;
        }
        .slot-booked .time { font-size: 1.5rem; font-weight: 900; color: rgba(255,255,255,0.3); }
        .slot-booked .status { color: var(--glory-red); font-size: 0.7rem; font-weight: 800; text-transform: uppercase; margin-top: 5px; }

        .empty-state {
            padding: 80px 0;
            text-align: center;
            color: rgba(255,255,255,0.3);
            font-weight: 600;
        }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container d-flex justify-content-between">
        <a class="navbar-brand text-white fw-bold" href="dashboard.php">GLORY <span style="color: var(--glory-red);">SPORT</span></a>
        <a href="dashboard.php" class="btn-kembali">KEMBALI</a>
    </div>
</nav>

<div class="container">
    <h1 class="header-title">JADWAL <span>LAPANGAN</span></h1>
    <p class="text-white-50 mb-5">Pilih arena dan waktu favorit Anda untuk bertanding.</p>

    <div class="glass-panel">
        <form action="" method="GET">
            <div class="row g-4 align-items-end">
                <div class="col-md-5">
                    <label class="form-label-custom">Pilih Arena</label>
                    <select name="id_lapangan" class="form-select filter-select" onchange="this.form.submit()">
                        <option value="">-- Pilih Lapangan --</option>
                        <?php while($lap = mysqli_fetch_assoc($list_lapangan)): ?>
                            <option value="<?= $lap['id_lapangan'] ?>" <?= ($id_lapangan == $lap['id_lapangan']) ? 'selected' : '' ?>>
                                <?= strtoupper($lap['nama_lapangan']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label-custom">Pilih Tanggal</label>
                    <input type="date" name="tanggal" class="form-control filter-date" value="<?= $tgl_pilih ?>" onchange="this.form.submit()">
                </div>
                <div class="col-md-2 text-md-end">
                    <button type="submit" class="btn btn-danger w-100 py-3 rounded-3 shadow fw-bold">CEK JADWAL</button>
                </div>
            </div>
        </form>
    </div>

    <?php if ($id_lapangan == ''): ?>
        <div class="glass-panel empty-state">
            <i class="fas fa-search fa-3x mb-3 d-block"></i>
            Silakan pilih lapangan terlebih dahulu untuk melihat slot waktu.
        </div>
    <?php else: ?>
        <div class="slot-container mb-5">
            <?php 
            for ($i = $jam_buka; $i < $jam_tutup; $i++): 
                $time_val = sprintf("%02d:00", $i);
                $is_booked = in_array($time_val, $booked_slots);
            ?>
                
                <?php if ($is_booked): ?>
                    <div class="slot-card slot-booked shadow-lg">
                        <div class="time"><?= $time_val ?></div>
                        <div class="status">NOT AVAILABLE</div>
                    </div>
                <?php else: ?>
                    <a href="booking.php?id_lapangan=<?= $id_lapangan ?>&tgl=<?= $tgl_pilih ?>&jam=<?= $time_val ?>" class="slot-card slot-available shadow-lg">
                        <div class="time"><?= $time_val ?></div>
                        <div class="status">AVAILABLE</div>
                    </a>
                <?php endif; ?>

            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>