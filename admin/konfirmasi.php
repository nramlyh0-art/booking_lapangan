<?php
session_start();
require '../config/database.php';

// 1. Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_admin.php");
    exit();
}

// 2. Logika Pemrosesan Aksi
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    
    if ($_GET['aksi'] == 'setuju') {
        // Mengubah status menjadi Lunas/DIKONFIRMASI
        mysqli_query($db, "UPDATE booking SET status = 'Lunas' WHERE id_booking = '$id'");
    } 
    elseif ($_GET['aksi'] == 'tolak') {
        // Mengubah status menjadi Dibatalkan dan menghapus referensi bukti agar user bisa upload ulang jika perlu
        mysqli_query($db, "UPDATE booking SET status = 'Dibatalkan', bukti_bayar = '' WHERE id_booking = '$id'");
    }
    elseif ($_GET['aksi'] == 'hapus') {
        mysqli_query($db, "DELETE FROM booking WHERE id_booking = '$id'");
    }
    header("Location: konfirmasi.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Pembayaran | GLORY SPORT</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --glory-orange: #e67e22; --dark-side: #000000; }
        body { 
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('../assets/img/bg-stadium.jpg'); 
            background-size: cover; background-attachment: fixed; color: white; 
            font-family: 'Montserrat', sans-serif; min-height: 100vh;
        }
        .sidebar { background: var(--dark-side); min-height: 100vh; padding: 30px 20px; border-right: 1px solid #222; position: fixed; width: 250px; z-index: 1000; }
        .sidebar-brand { color: var(--glory-orange); font-weight: 800; font-size: 24px; text-decoration: none; display: block; margin-bottom: 40px; }
        .nav-link { color: #888; padding: 12px 18px; border-radius: 10px; margin-bottom: 8px; text-decoration: none; display: flex; align-items: center; transition: 0.3s; font-weight: 600; }
        .nav-link:hover, .nav-link.active { background: var(--glory-orange); color: white !important; }
        .main-content { padding: 40px; margin-left: 250px; }
        .card-table { background: rgba(255, 255, 255, 0.98); border-radius: 20px; border: none; overflow: hidden; }
        .table thead { background: #f1f3f5; }
        .table th { color: #444; font-size: 11px; text-transform: uppercase; padding: 20px; letter-spacing: 1px; }
        .text-black-bold { color: #000; font-weight: 700; }
        .arena-box { background: rgba(230, 126, 34, 0.1); padding: 8px 12px; border-radius: 8px; border-left: 4px solid var(--glory-orange); }
        .img-preview { width: 50px; height: 50px; border-radius: 10px; object-fit: cover; border: 2px solid #eee; cursor: pointer; transition: 0.3s; }
        .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .bg-lunas { background: #d1e7dd; color: #0f5132; }
        .bg-pending { background: #fff3cd; color: #856404; }
        .bg-waiting { background: #e2e3e5; color: #41464b; }
        .bg-batal { background: #f8d7da; color: #842029; }
        .btn-approve { background: var(--glory-orange); color: white; border: none; font-weight: 700; font-size: 11px; padding: 8px 15px; border-radius: 6px; }
        .btn-reject { background: white; color: #dc3545; border: 1px solid #dc3545; font-weight: 700; font-size: 11px; padding: 8px 15px; border-radius: 6px; }
    </style>
</head>
<body>

<aside class="sidebar">
    <a href="dashboard.php" class="sidebar-brand">GLORY ADMIN</a>
    <nav>
        <a class="nav-link" href="dashboard.php"><i class="fas fa-th-large me-2"></i> Dashboard</a>
        <a class="nav-link active" href="konfirmasi.php"><i class="fas fa-check-double me-2"></i> Validasi</a>
        <a class="nav-link" href="laporan.php"><i class="fas fa-file-invoice-dollar me-2"></i> Laporan</a>
        <a class="nav-link text-danger" href="../logout.php" style="margin-top: 60px;"><i class="fas fa-power-off me-2"></i> Keluar</a>
    </nav>
</aside>

<main class="main-content">
    <div class="mb-4">
        <h2 class="header-title text-uppercase font-weight-bold">Validasi <span style="color: var(--glory-orange);">Pembayaran</span></h2>
        <p class="text-secondary small">Hanya pesanan dengan bukti bayar yang dapat divalidasi.</p>
    </div>

    <div class="card card-table shadow">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Pelanggan</th>
                        <th>Arena & Jadwal</th>
                        <th>Total</th>
                        <th class="text-center">Bukti</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = mysqli_query($db, "SELECT b.*, u.nama_lengkap, l.nama_lapangan 
                                                FROM booking b
                                                JOIN user u ON b.id_user = u.id_user 
                                                JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
                                                ORDER BY b.id_booking DESC");

                    while ($row = mysqli_fetch_assoc($result)) {
                        $st = $row['status'];
                        $bukti = $row['bukti_bayar'];
                        
                        // Logika Badge
                        if (empty($bukti) && $st != 'Dibatalkan') {
                            $badge_class = 'bg-waiting';
                            $status_text = 'Menunggu Bukti';
                        } else {
                            $badge_class = ($st == 'Lunas') ? 'bg-lunas' : (($st == 'Dibatalkan') ? 'bg-batal' : 'bg-pending');
                            $status_text = $st;
                        }
                    ?>
                    <tr>
                        <td>
                            <div class="text-black-bold"><?= strtoupper($row['nama_lengkap']) ?></div>
                            <small class="text-muted">#<?= $row['id_booking'] ?></small>
                        </td>
                        <td>
                            <div class="arena-box">
                                <span class="text-dark fw-bold" style="font-size:13px;"><?= $row['nama_lapangan'] ?></span><br>
                                <small class="text-muted"><?= date('d/m/y', strtotime($row['tgl_main'])) ?> | <?= substr($row['jam_mulai'],0,5) ?> WIB</small>
                            </div>
                        </td>
                        <td><b class="text-dark">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></b></td>
                        <td class="text-center">
                            <?php if ($bukti): ?>
                                <img src="../assets/bukti_bayar/<?= $bukti ?>" class="img-preview" data-bs-toggle="modal" data-bs-target="#imgModal" data-src="../assets/bukti_bayar/<?= $bukti ?>">
                            <?php else: ?>
                                <span class="text-muted small">No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><span class="status-badge <?= $badge_class ?>"><?= $status_text ?></span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <?php if (!empty($bukti) && $st != 'Lunas' && $st != 'Dibatalkan'): ?>
                                    <a href="?aksi=setuju&id=<?= $row['id_booking'] ?>" class="btn btn-approve" onclick="return confirm('Sahkan pembayaran?')">Terima</a>
                                    <a href="?aksi=tolak&id=<?= $row['id_booking'] ?>" class="btn btn-reject" onclick="return confirm('Tolak & minta upload ulang?')">Tolak</a>
                                <?php elseif ($st == 'Lunas' || $st == 'Dibatalkan'): ?>
                                    <a href="?aksi=hapus&id=<?= $row['id_booking'] ?>" class="text-danger" onclick="return confirm('Hapus permanen?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                <?php else: ?>
                                    <i class="fas fa-hourglass-half text-muted" title="Menunggu user upload bukti"></i>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="imgModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 bg-dark">
            <div class="modal-body p-0 text-center">
                <img src="" id="imgTarget" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const imgModal = document.getElementById('imgModal')
    imgModal.addEventListener('show.bs.modal', event => {
        const btn = event.relatedTarget
        document.getElementById('imgTarget').src = btn.getAttribute('data-src')
    })
</script>
</body>
</html>