<?php
session_start();
require_once '../config/database.php';

// Proteksi Admin (Sesuaikan dengan sistem login admin Anda)
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { header("Location: ../login.php"); exit; }

// Logika Update Status oleh Admin
if (isset($_POST['update_status'])) {
    $id_b = $_POST['id_booking'];
    $status_baru = $_POST['status'];
    
    $update = mysqli_query($db, "UPDATE booking SET status = '$status_baru' WHERE id_booking = '$id_b'");
    if ($update) {
        echo "<script>alert('Status Booking Berhasil Diperbarui!'); window.location='verifikasi_booking.php';</script>";
    }
}

// Ambil data booking yang perlu diverifikasi (Menunggu Verifikasi & Berhasil)
$query_admin = mysqli_query($db, "SELECT b.*, u.nama_lengkap, l.nama_lapangan 
                                 FROM booking b 
                                 JOIN user u ON b.id_user = u.id_user 
                                 JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
                                 ORDER BY b.tgl_booking DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel | Verifikasi Glory Sport</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-admin { background: #212529; color: white; padding: 15px; }
        .card-admin { background: white; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: none; }
        .bukti-img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; cursor: pointer; transition: 0.3s; }
        .bukti-img:hover { transform: scale(1.1); }
        .status-badge { font-size: 0.75rem; font-weight: 700; padding: 5px 12px; border-radius: 20px; text-transform: uppercase; }
        .bg-waiting { background: #e3f2fd; color: #0d6efd; }
        .bg-success { background: #d1e7dd; color: #0f5132; }
    </style>
</head>
<body>

<div class="navbar-admin shadow-sm mb-4">
    <div class="container d-flex justify-content-between align-items-center">
        <h4 class="m-0 fw-bold"><i class="fas fa-user-shield me-2"></i> GLORY <span class="text-danger">ADMIN</span></h4>
        <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</div>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold">Verifikasi Pembayaran</h2>
            <p class="text-muted">Cek bukti transfer user dan konfirmasi pesanan di sini.</p>
        </div>
    </div>

    <div class="card-admin p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Pelanggan</th>
                        <th>Kode Booking</th>
                        <th>Jadwal & Arena</th>
                        <th>Total</th>
                        <th>Bukti Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($query_admin)): ?>
                    <tr>
                        <td>
                            <div class="fw-bold"><?= $row['nama_lengkap'] ?></div>
                            <small class="text-muted">ID: #<?= $row['id_user'] ?></small>
                        </td>
                        <td><span class="badge bg-dark"><?= $row['kode_booking'] ?></span></td>
                        <td>
                            <div class="small fw-bold"><?= $row['nama_lapangan'] ?></div>
                            <div class="small text-danger"><?= date('d M Y', strtotime($row['tgl_main'])) ?> | <?= substr($row['jam_mulai'],0,5) ?> WIB</div>
                        </td>
                        <td class="fw-bold text-primary">Rp <?= number_format($row['total_harga']) ?></td>
                        <td>
                            <?php if($row['bukti_bayar']): ?>
                                <a href="../assets/bukti_bayar/<?= $row['bukti_bayar'] ?>" target="_blank">
                                    <img src="../assets/bukti_bayar/<?= $row['bukti_bayar'] ?>" class="bukti-img border shadow-sm" title="Klik untuk memperbesar">
                                </a>
                            <?php else: ?>
                                <span class="text-muted small italic">Kosong</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status-badge <?= ($row['status'] == 'Berhasil') ? 'bg-success' : 'bg-waiting' ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td>
                            <form action="" method="POST" class="d-flex gap-2">
                                <input type="hidden" name="id_booking" value="<?= $row['id_booking'] ?>">
                                <select name="status" class="form-select form-select-sm" style="width: 130px;">
                                    <option value="Berhasil" <?= $row['status'] == 'Berhasil' ? 'selected' : '' ?>>Konfirmasi</option>
                                    <option value="Dibatalkan" <?= $row['status'] == 'Dibatalkan' ? 'selected' : '' ?>>Tolak</option>
                                    <option value="Menunggu Verifikasi" <?= $row['status'] == 'Menunggu Verifikasi' ? 'selected' : '' ?>>Pending</option>
                                </select>
                                <button type="submit" name="update_status" class="btn btn-dark btn-sm">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>