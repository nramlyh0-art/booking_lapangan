<?php
session_start();
require_once '../config/database.php'; //

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth.php");
    exit;
} //

$nama_user = $_SESSION['nama_lengkap'] ?? 'USER'; //

// Ambil semua data lapangan (Sekarang akan muncul 3 setelah di-insert)
$query_lapangan = mysqli_query($db, "SELECT * FROM lapangan"); //

// Query Jadwal Terisi
$tgl_hari_ini = date('Y-m-d');
$query_jadwal = mysqli_query($db, "SELECT b.tgl_main, b.jam_mulai, b.jam_selesai, l.nama_lapangan 
    FROM booking b 
    JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
    WHERE b.tgl_main >= '$tgl_hari_ini' AND b.status != 'Dibatalkan'
    ORDER BY b.tgl_main ASC"); //
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Booking Arena | GLORY SPORT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), 
                        url('../assets/images/bg-stadium.jpg') no-repeat center center fixed;
            background-size: cover; color: white; min-height: 100vh;
        }
        .navbar { background: rgba(0, 0, 0, 0.9); border-bottom: 2px solid #ff416c; padding: 15px 0; }
        .card-custom { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 20px; padding: 30px; }
        .btn-glory { background: #ff416c; border: none; color: white; font-weight: 900; padding: 15px; text-transform: uppercase; }
        .form-control, .form-select { background: rgba(0,0,0,0.6); border: 1px solid #555; color: white; }
        .img-preview { width: 100%; height: 120px; object-fit: cover; border-radius: 10px; margin-bottom: 10px; border: 1px solid #ff416c; }
        .lapangan-box { transition: 0.3s; padding: 10px; border-radius: 15px; }
        .lapangan-box:hover { background: rgba(255, 65, 108, 0.1); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="dashboard.php">
                GLORY <span style="color: #ff416c;">SPORT</span> | <span style="color: #ff416c;"><?= $nama_user ?></span>
            </a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row g-4">
            <div class="col-lg-12">
                <div class="card-custom mb-4">
                    <h5 class="text-info fw-bold">Jadwal Lapangan Terisi</h5>
                    <table class="table table-dark table-sm">
                        <thead><tr><th>Arena</th><th>Tgl</th><th>Jam</th></tr></thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($query_jadwal)): ?>
                            <tr>
                                <td><?= $row['nama_lapangan'] ?></td>
                                <td><?= date('d/m', strtotime($row['tgl_main'])) ?></td>
                                <td><?= substr($row['jam_mulai'],0,5) ?>-<?= substr($row['jam_selesai'],0,5) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card-custom">
                    <h3 class="text-center fw-900 mb-4">PILIH <span style="color:#ff416c">LAPANGAN</span></h3>
                    <form action="proses_booking.php" method="POST">
                        <div class="row text-center">
                            <?php while($l = mysqli_fetch_assoc($query_lapangan)): ?>
                            <div class="col-md-4 mb-4">
                                <div class="lapangan-box">
                                    <img src="../assets/images/<?= $l['foto_lapangan'] ?>" class="img-preview" alt="Foto">
                                    <div class="form-check">
                                        <input class="form-check-input float-none" type="radio" name="id_lapangan" value="<?= $l['id_lapangan'] ?>" id="lap<?= $l['id_lapangan'] ?>" required>
                                        <label class="form-check-label d-block fw-bold" for="lap<?= $l['id_lapangan'] ?>">
                                            <?= $l['nama_lapangan'] ?> <br>
                                            <span class="text-warning">Rp <?= number_format($l['harga_per_jam'], 0, ',', '.') ?>/Jam</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php endwhile; ?>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 mb-3"><label>Tanggal Main</label><input type="date" name="tgl_main" class="form-control" required min="<?= date('Y-m-d') ?>"></div>
                            <div class="col-md-3 mb-3"><label>Jam Mulai</label><input type="time" name="jam_mulai" class="form-control" required></div>
                            <div class="col-md-3 mb-3"><label>Durasi (Jam)</label><input type="number" name="durasi" class="form-control" value="1" min="1" max="5"></div>
                        </div>
                        <button type="submit" name="pesan" class="btn btn-glory w-100">KONFIRMASI PESANAN</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>