<?php
session_start();
require '../config/database.php';

// 1. Proteksi Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_admin.php");
    exit();
}

// 2. Filter Tanggal
$tgl_mulai = isset($_POST['tgl_mulai']) ? $_POST['tgl_mulai'] : date('Y-m-01');
$tgl_sampai = isset($_POST['tgl_sampai']) ? $_POST['tgl_sampai'] : date('Y-m-d');

// 3. Query Ringkasan Statistik
$query_stats = mysqli_query($db, "SELECT 
    SUM(total_harga) as total_pendapatan, 
    COUNT(*) as total_transaksi 
    FROM booking 
    WHERE status = 'Lunas' 
    AND tgl_main BETWEEN '$tgl_mulai' AND '$tgl_sampai'");
$stats = mysqli_fetch_assoc($query_stats);
$pendapatan = $stats['total_pendapatan'] ?? 0;
$transaksi = $stats['total_transaksi'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan | GLORY SPORT</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { 
            --glory-orange: #e67e22; 
            --dark-side: #000000;
        }
        
        body { 
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('../assets/img/bg-stadium.jpg'); 
            background-size: cover;
            background-attachment: fixed;
            background-position: center;
            color: white; 
            font-family: 'Montserrat', sans-serif; 
            min-height: 100vh;
            margin: 0;
        }
        
        /* Sidebar */
        .sidebar { 
            background: var(--dark-side); 
            min-height: 100vh; 
            padding: 30px 20px; 
            border-right: 1px solid #222; 
            position: fixed; 
            width: 250px; 
            z-index: 1000;
        }
        
        .sidebar-brand {
            color: var(--glory-orange);
            font-weight: 800;
            font-size: 22px;
            letter-spacing: 1px;
            margin-bottom: 40px;
            display: block;
            text-decoration: none;
            text-align: center;
        }

        .nav-link { 
            color: #888; 
            padding: 12px 18px; 
            border-radius: 10px; 
            margin-bottom: 8px; 
            text-decoration: none; 
            display: flex;
            align-items: center;
            transition: 0.3s; 
            font-weight: 600;
        }
        
        .nav-link i { margin-right: 12px; width: 20px; }
        
        .nav-link:hover, .nav-link.active { 
            background: var(--glory-orange); 
            color: white !important; 
        }
        
        /* Main Content */
        .main-content { padding: 40px; margin-left: 250px; }
        
        .header-title { font-weight: 800; font-size: 28px; }
        .text-orange { color: var(--glory-orange) !important; }

        /* Stats Cards */
        .stat-card {
            background: linear-gradient(135deg, var(--glory-orange), #d35400);
            border-radius: 15px;
            padding: 20px;
            border: none;
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        }

        /* Filter Section */
        .filter-box {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            border: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 30px;
        }

        .form-control-dark {
            background: rgba(0,0,0,0.5);
            border: 1px solid #444;
            color: white;
            border-radius: 8px;
        }

        .form-control-dark:focus {
            background: rgba(0,0,0,0.7);
            border-color: var(--glory-orange);
            color: white;
            box-shadow: none;
        }

        /* Table Card - Putih agar Teks Hitam Tajam */
        .card-table { 
            background: rgba(255, 255, 255, 0.95); 
            border-radius: 20px; 
            border: none; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.4);
            overflow: hidden; 
            color: #000;
        }
        
        .table { margin-bottom: 0; vertical-align: middle; color: #000; }
        .table thead { background: #f1f1f1; }
        .table th { 
            color: #555; 
            font-size: 11px; 
            text-transform: uppercase; 
            padding: 15px 20px; 
            border: none;
            letter-spacing: 1px;
        }
        
        /* Nama & Nominal Hitam sesuai permintaan */
        .text-black-bold { color: #000000 !important; font-weight: 700; }
        
        .table td { padding: 15px 20px; border-top: 1px solid #eee; }

        .btn-export {
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            transition: 0.3s;
        }

        @media (max-width: 992px) {
            .sidebar { width: 80px; }
            .sidebar-brand, .nav-text { display: none; }
            .main-content { margin-left: 80px; }
        }
    </style>
</head>
<body>

<aside class="sidebar shadow">
    <a href="dashboard.php" class="sidebar-brand">GLORY</a>
    <nav>
        <a class="nav-link" href="dashboard.php"><i class="fas fa-th-large"></i> <span class="nav-text">Dashboard</span></a>
        <a class="nav-link" href="konfirmasi.php"><i class="fas fa-receipt"></i> <span class="nav-text">Konfirmasi</span></a>
        <a class="nav-link active" href="laporan.php"><i class="fas fa-chart-bar"></i> <span class="nav-text">Laporan</span></a>
        <div style="margin-top: 50px;">
            <a class="nav-link text-danger" href="../logout.php"><i class="fas fa-power-off"></i> <span class="nav-text">Logout</span></a>
        </div>
    </nav>
</aside>

<main class="main-content">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="header-title">LAPORAN <span class="text-orange">KEUANGAN</span></h2>
            <p class="text-secondary mb-0">Periode: <?= date('d M Y', strtotime($tgl_mulai)) ?> — <?= date('d M Y', strtotime($tgl_sampai)) ?></p>
        </div>
        <div class="d-flex gap-2">
            <a href="export_pdf.php?mulai=<?= $tgl_mulai ?>&sampai=<?= $tgl_sampai ?>" class="btn btn-outline-light btn-export"><i class="fas fa-file-pdf me-2"></i>PDF</a>
            <a href="export_excel.php?mulai=<?= $tgl_mulai ?>&sampai=<?= $tgl_sampai ?>" class="btn btn-success btn-export"><i class="fas fa-file-excel me-2"></i>EXCEL</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="stat-card">
                <small class="text-uppercase fw-bold opacity-75">Total Pendapatan (Lunas)</small>
                <h1 class="fw-800 mb-0">Rp <?= number_format($pendapatan, 0, ',', '.') ?></h1>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);">
                <small class="text-uppercase fw-bold opacity-75">Total Transaksi</small>
                <h1 class="fw-800 mb-0"><?= $transaksi ?> <small style="font-size: 15px;">Pesanan</small></h1>
            </div>
        </div>
    </div>

    <div class="filter-box">
        <form method="POST" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="small fw-bold mb-2 text-secondary">DARI TANGGAL</label>
                <input type="date" name="tgl_mulai" class="form-control form-control-dark" value="<?= $tgl_mulai ?>">
            </div>
            <div class="col-md-4">
                <label class="small fw-bold mb-2 text-secondary">SAMPAI TANGGAL</label>
                <input type="date" name="tgl_sampai" class="form-control form-control-dark" value="<?= $tgl_sampai ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-orange w-100 fw-bold" style="background: var(--glory-orange); color: white; padding: 10px; border-radius: 8px;">
                    <i class="fas fa-filter me-2"></i>TERAPKAN FILTER
                </button>
            </div>
        </form>
    </div>

    <div class="card card-table shadow-lg">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tgl Main</th>
                        <th>Kode</th>
                        <th>Nama Pelanggan</th>
                        <th>Arena</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT b.*, u.nama_lengkap, l.nama_lapangan 
                            FROM booking b
                            JOIN user u ON b.id_user = u.id_user 
                            JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
                            WHERE b.status = 'Lunas' 
                            AND b.tgl_main BETWEEN '$tgl_mulai' AND '$tgl_sampai'
                            ORDER BY b.tgl_main DESC";
                    $result = mysqli_query($db, $sql);

                    if(mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td class="small fw-bold text-secondary"><?= date('d/m/Y', strtotime($row['tgl_main'])) ?></td>
                        <td class="small text-secondary fw-bold">#<?= $row['id_booking'] ?></td>
                        <td class="text-black-bold text-uppercase"><?= $row['nama_lengkap'] ?></td>
                        <td class="small fw-bold" style="color: var(--glory-orange);"><?= $row['nama_lapangan'] ?></td>
                        <td class="text-end text-black-bold">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                    </tr>
                    <?php 
                        } 
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-5 text-secondary'>Belum ada data pada periode ini.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>