<?php
session_start();
require '../config/database.php';

// 1. Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth.php");
    exit();
}

// 2. Logika Pemrosesan Aksi (Setuju, Tolak, Hapus)
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($db, $_GET['id']);
    
    if ($_GET['aksi'] == 'setuju') {
        // Update status menjadi Lunas
        mysqli_query($db, "UPDATE booking SET status = 'Lunas' WHERE id_booking = '$id'");
    } 
    elseif ($_GET['aksi'] == 'tolak') {
        // Kembalikan status ke Dibatalkan dan kosongkan kolom bukti agar user bisa upload ulang
        mysqli_query($db, "UPDATE booking SET status = 'Dibatalkan', bukti_bayar = '' WHERE id_booking = '$id'");
    }
    elseif ($_GET['aksi'] == 'hapus') {
        // Hapus data booking secara permanen
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
    <title>Validasi Pembayaran | GLORY ADMIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --neon-blue: #00d2ff; --dark-bg: #0a1118; --neon-pink: #ff2d55; }
        body { background: var(--dark-bg); color: white; font-family: 'Montserrat', sans-serif; }
        
        .sidebar { background: #000; min-height: 100vh; padding: 30px 20px; border-right: 1px solid #1a252f; position: fixed; width: inherit; }
        .nav-link { color: #8a99a7; padding: 12px 15px; border-radius: 8px; margin-bottom: 5px; text-decoration: none; display: block; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { background: rgba(0, 210, 255, 0.1); color: var(--neon-blue); }
        
        .main-content { padding: 40px; margin-left: 16.666667%; }
        .card-table { background: #151f28; border-radius: 15px; border: none; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
        
        .table { color: #e1e1e1; vertical-align: middle; margin-bottom: 0; }
        .table thead { background: #000; }
        .table th { color: var(--neon-blue); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; padding: 20px; border: none; }
        .table td { padding: 20px; border-top: 1px solid #1f2d3a; background: transparent; }
        
        .img-bukti-container { width: 60px; height: 60px; cursor: pointer; border-radius: 10px; overflow: hidden; border: 2px solid #333; transition: 0.3s; }
        .img-bukti-container:hover { border-color: var(--neon-blue); transform: scale(1.05); }
        .img-bukti { width: 100%; height: 100%; object-fit: cover; }
        
        .status-badge { padding: 6px 14px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; }
        .bg-lunas { background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid #28a745; }
        .bg-pending { background: rgba(255, 193, 7, 0.15); color: #ffc107; border: 1px solid #ffc107; }
        .bg-batal { background: rgba(255, 45, 85, 0.15); color: var(--neon-pink); border: 1px solid var(--neon-pink); }

        @media (max-width: 768px) {
            .sidebar { position: relative; min-height: auto; width: 100%; }
            .main-content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar">
            <h4 class="fw-800 mb-5 text-info">GLORY ADMIN</h4>
            <a class="nav-link" href="dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
            <a class="nav-link active" href="konfirmasi.php"><i class="fas fa-check-circle me-2"></i> Konfirmasi</a>
            <a class="nav-link" href="laporan.php"><i class="fas fa-file-alt me-2"></i> Laporan</a>
            <a class="nav-link text-danger mt-5" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </div>

        <div class="col-md-10 main-content">
            <h2 class="fw-800 mb-1">VALIDASI <span class="text-info">PEMBAYARAN</span></h2>
            <p class="text-secondary mb-4 small">Kelola persetujuan, penolakan, atau penghapusan riwayat pembayaran.</p>

            <div class="card card-table shadow">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Arena / Jadwal</th>
                                <th>Total</th>
                                <th class="text-center">Bukti</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT b.*, u.nama_lengkap, l.nama_lapangan 
                                    FROM booking b
                                    JOIN user u ON b.id_user = u.id_user 
                                    JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
                                    ORDER BY b.id_booking DESC";
                            $result = mysqli_query($db, $sql);

                            while ($row = mysqli_fetch_assoc($result)) {
                                if($row['status'] == 'Lunas') $badge = 'bg-lunas';
                                elseif($row['status'] == 'Dibatalkan') $badge = 'bg-batal';
                                else $badge = 'bg-pending';
                            ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-white"><?= strtoupper($row['nama_lengkap']) ?></div>
                                    <small class="text-secondary">#<?= $row['kode_booking'] ?></small>
                                </td>
                                <td>
                                    <div class="text-info small fw-bold"><?= $row['nama_lapangan'] ?></div>
                                    <div class="text-secondary small"><?= date('d M Y', strtotime($row['tgl_main'])) ?> | <?= substr($row['jam_mulai'],0,5) ?> WIB</div>
                                </td>
                                <td class="text-white fw-bold">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <?php if (!empty($row['bukti_bayar'])): ?>
                                        <div class="img-bukti-container mx-auto" 
                                             data-bs-toggle="modal" 
                                             data-bs-target="#modalGambar" 
                                             data-src="../assets/bukti_bayar/<?= $row['bukti_bayar'] ?>"
                                             data-name="<?= $row['nama_lengkap'] ?>">
                                            <img src="../assets/bukti_bayar/<?= $row['bukti_bayar'] ?>" class="img-bukti" alt="Bukti">
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small italic">No Image</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="status-badge <?= $badge ?>"><?= $row['status'] ?></span></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <?php if ($row['status'] != 'Lunas' && $row['status'] != 'Dibatalkan'): ?>
                                            <a href="?aksi=setuju&id=<?= $row['id_booking'] ?>" class="btn btn-sm btn-info text-dark fw-800 rounded-pill px-3" onclick="return confirm('Setujui pembayaran ini?')">SETUJU</a>
                                            <a href="?aksi=tolak&id=<?= $row['id_booking'] ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Tolak pembayaran? Bukti akan dihapus.')">TOLAK</a>
                                        <?php else: ?>
                                            <span class="text-white-50 small me-2 mt-1"><?= $row['status'] == 'Lunas' ? 'Verified' : 'Rejected' ?></span>
                                            <a href="?aksi=hapus&id=<?= $row['id_booking'] ?>" class="btn btn-sm btn-dark text-danger border-danger rounded-circle" onclick="return confirm('Hapus data booking ini secara permanen?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGambar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-header border-secondary">
                <h6 class="modal-title text-info fw-bold">DETAIL BUKTI BAYAR</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img src="" id="imgShow" class="img-fluid w-100 shadow-lg">
            </div>
            <div class="modal-footer border-secondary">
                <small id="imgName" class="text-secondary mx-auto"></small>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const modalGambar = document.getElementById('modalGambar')
    modalGambar.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const src = button.getAttribute('data-src')
        const name = button.getAttribute('data-name')
        document.getElementById('imgShow').src = src
        document.getElementById('imgName').innerText = "Pelanggan: " + name
    })
</script>

</body>
</html>