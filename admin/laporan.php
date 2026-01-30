<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth.php");
    exit;
}

// 1. Filter Tanggal (Default: Bulan Berjalan)
$tgl_mulai = $_GET['tgl_mulai'] ?? date('Y-m-01'); 
$tgl_selesai = $_GET['tgl_selesai'] ?? date('Y-m-d');

// 2. Query Data Lunas
$query_str = "SELECT booking.*, user.nama_lengkap 
              FROM booking 
              JOIN user ON booking.id_user = user.id_user 
              WHERE booking.status = 'Lunas' 
              AND booking.tgl_booking BETWEEN '$tgl_mulai 00:00:00' AND '$tgl_selesai 23:59:59'
              ORDER BY booking.tgl_booking DESC";

$sql = mysqli_query($db, $query_str);

$total_pendapatan = 0;
$total_pesanan = mysqli_num_rows($sql);
$data_laporan = [];

while($row = mysqli_fetch_assoc($sql)) {
    $total_pendapatan += $row['total_harga'];
    $data_laporan[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan | GLORY ADMIN</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { 
            --neon-blue: #00d2ff; 
            --dark-grey: #1a1a1a;
            --pure-black: #000000;
            --bg-black: #0a0a0a;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg-black);
            color: #ffffff;
            min-height: 100vh;
            margin: 0;
        }

        /* SIDEBAR & LAYOUT */
        .sidebar { width: 260px; height: 100vh; position: fixed; background: #000; padding: 30px 20px; border-right: 1px solid rgba(0,210,255,0.1); z-index: 100; transition: 0.3s; }
        .main-content { margin-left: 260px; padding: 40px; transition: 0.3s; }
        
        .nav-link { color: rgba(255,255,255,0.5); padding: 12px; display: block; text-decoration: none; font-weight: 600; transition: 0.3s; }
        .nav-link.active { color: var(--neon-blue); background: rgba(0,210,255,0.1); border-radius: 10px; }

        /* COMPONENTS */
        .stat-box {
            background: linear-gradient(135deg, #0056b3, #00d2ff);
            border-radius: 15px; padding: 25px; border: none;
            box-shadow: 0 8px 20px rgba(0, 210, 255, 0.2);
        }
        .filter-box { background: var(--dark-grey); border-radius: 15px; padding: 20px; border: 1px solid #333; }

        /* TABLE STYLES */
        .report-card { background: #ffffff; border-radius: 15px; overflow: hidden; border: none; }
        .table { margin-bottom: 0; background: #ffffff !important; width: 100%; }
        
        /* Table Header */
        .table thead { background: var(--pure-black) !important; }
        .table thead th { 
            background: var(--pure-black) !important;
            color: var(--neon-blue); 
            text-transform: uppercase; 
            font-size: 11px; 
            letter-spacing: 1px;
            font-weight: 800;
            padding: 18px 15px;
            border: none;
        }

        /* Table Body */
        .table tbody tr { border-bottom: 1px solid #eee; }
        .table td { padding: 15px; vertical-align: middle; color: #444; font-size: 14px; border: none; }
        
        /* NAMA PELANGGAN HITAM PEKAT */
        .text-pelanggan-hitam {
            color: #000000 !important;
            font-weight: 800;
            font-size: 15px;
        }

        .kode-badge { background: #000; color: #fff; padding: 5px 8px; border-radius: 4px; font-weight: 700; font-family: monospace; font-size: 12px; }
        .form-control { background: #1a1a1a; border: 1px solid #444; color: white; border-radius: 8px; }

        /* RESPONSIVE (MOBILE) */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); width: 0; padding: 0; }
            .main-content { margin-left: 0; padding: 20px; }
            .stat-box h2 { font-size: 1.4rem; }
            .table-responsive { border-radius: 10px; }
            .table td, .table th { padding: 12px !important; font-size: 13px !important; }
        }

        /* PDF / PRINT OPTIMIZATION */
        @media print {
            @page { size: portrait; margin: 1cm; }
            .sidebar, .btn-action, .filter-box, .nav-link, button { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; }
            body { background: white !important; color: black !important; }
            .report-card { border: 1px solid #eee; border-radius: 0; }
            .stat-box { 
                background: #f8f9fa !important; 
                border: 1px solid #ddd !important; 
                color: black !important; 
                box-shadow: none !important;
            }
            .stat-box p, .stat-box h2 { color: black !important; }
            .table thead th { 
                background: #000 !important; 
                color: #fff !important; 
                -webkit-print-color-adjust: exact; 
            }
            .text-pelanggan-hitam { color: #000 !important; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h4 class="fw-800 mb-5 text-center" style="color: var(--neon-blue); letter-spacing: 2px;">GLORY ADMIN</h4>
    <a href="dashboard.php" class="nav-link">DASHBOARD</a>
    <a href="konfirmasi.php" class="nav-link">KONFIRMASI</a>
    <a href="laporan.php" class="nav-link active">LAPORAN</a>
    <a href="../logout.php" class="nav-link text-danger mt-5"><i class="fas fa-power-off me-2"></i> LOGOUT</a>
</div>

<div class="main-content">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h1 class="fw-800 text-uppercase mb-1 fs-3">LAPORAN <span style="color: var(--neon-blue);">KEUANGAN</span></h1>
            <p class="text-secondary fw-600 mb-0 small">Periode: <?= date('d M Y', strtotime($tgl_mulai)) ?> — <?= date('d M Y', strtotime($tgl_selesai)) ?></p>
        </div>
        <div class="d-flex gap-2 btn-action">
            <button onclick="window.print()" class="btn btn-outline-info px-4 fw-700"><i class="fas fa-file-pdf me-2"></i>PDF</button>
            <button onclick="exportExcel()" class="btn btn-success px-4 fw-700"><i class="fas fa-file-excel me-2"></i>EXCEL</button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="stat-box">
                <p class="small text-uppercase fw-800 mb-1" style="color: rgba(255,255,255,0.8);">Total Pendapatan</p>
                <h2 class="fw-800 mb-0">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h2>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="filter-box h-100 d-flex flex-column justify-content-center">
                <p class="small text-uppercase fw-800 mb-1 text-secondary">Total Transaksi</p>
                <h2 class="fw-800 mb-0" style="color: var(--neon-blue);"><?= $total_pesanan ?> <span class="fs-6">Pesanan Lunas</span></h2>
            </div>
        </div>
    </div>

    <div class="filter-box mb-4 btn-action">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-6 col-md-4">
                <label class="small fw-700 mb-2 text-secondary">DARI TANGGAL</label>
                <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
            </div>
            <div class="col-6 col-md-4">
                <label class="small fw-700 mb-2 text-secondary">SAMPAI TANGGAL</label>
                <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
            </div>
            <div class="col-12 col-md-4">
                <button type="submit" class="btn btn-info w-100 py-2 fw-800 text-uppercase" style="background: var(--neon-blue); color: #000; border: none;">Terapkan Filter</button>
            </div>
        </form>
    </div>

    <div class="report-card shadow-sm">
        <div class="table-responsive">
            <table class="table" id="tableLaporan">
                <thead>
                    <tr>
                        <th>TGL</th>
                        <th>KODE</th>
                        <th>NAMA PELANGGAN</th>
                        <th>WAKTU</th>
                        <th class="text-end">NOMINAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($total_pesanan > 0): ?>
                        <?php foreach($data_laporan as $row): ?>
                        <tr>
                            <td class="fw-600 text-secondary"><?= date('d/m/y', strtotime($row['tgl_booking'])) ?></td>
                            <td><span class="kode-badge">#<?= $row['id_booking'] ?></span></td>
                            <td class="text-pelanggan-hitam"><?= strtoupper($row['nama_lengkap']) ?></td>
                            <td>
                                <span class="badge bg-dark">
                                    <?= substr($row['jam_mulai'],0,5) ?> - <?= substr($row['jam_selesai'],0,5) ?>
                                </span>
                            </td>
                            <td class="text-end fw-800" style="color: #000;">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada data pada periode ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    function exportExcel() {
        var table = document.getElementById("tableLaporan");
        var wb = XLSX.utils.table_to_book(table, {sheet: "Laporan_Keuangan"});
        XLSX.writeFile(wb, "Laporan_Glory_<?= date('dmY') ?>.xlsx");
    }
</script>

</body>
</html>