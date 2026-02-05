<?php
session_start();

// 1. Koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "booking_lapangan");

// 2. Proteksi Halaman Admin: Jika bukan admin, tendang balik ke login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login_admin.php");
    exit;
}

// 3. Ambil data statistik (Sesuaikan nama tabel Anda)
// Mengambil total pendapatan dari booking yang statusnya 'Lunas'
$query_pendapatan = mysqli_query($conn, "SELECT SUM(total_harga) as total FROM booking WHERE status = 'Lunas'");
$res_pendapatan = mysqli_fetch_assoc($query_pendapatan);
$pendapatan = $res_pendapatan['total'] ?? 0;

// Mengambil total semua booking
$query_booking = mysqli_query($conn, "SELECT COUNT(*) as total FROM booking");
$res_booking = mysqli_fetch_assoc($query_booking);
$total_booking = $res_booking['total'] ?? 0;

// Ambil username untuk sapaan (fallback ke 'Admin' jika kosong)
$nama_admin = isset($_SESSION['admin_email']) ? explode('@', $_SESSION['admin_email'])[0] : 'Admin';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GLORY ADMIN - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background: #121212; /* Warna dasar gelap jika gambar gagal load */
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), 
                        url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&q=80') no-repeat center center fixed;
            background-size: cover;
            color: white;
            min-height: 100vh;
            margin: 0;
        }

        .sidebar {
            width: 260px; height: 100vh; position: fixed;
            background: rgba(0, 0, 0, 0.9); backdrop-filter: blur(15px);
            padding: 30px 20px; border-right: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000; display: flex; flex-direction: column;
        }

        .brand-name { font-weight: 800; font-size: 1.2rem; color: #e67e22; margin-bottom: 40px; text-transform: uppercase; }
        .nav-link {
            color: rgba(255, 255, 255, 0.6); padding: 12px 15px; border-radius: 12px;
            margin-bottom: 8px; font-weight: 600; text-decoration: none; display: flex; align-items: center; transition: 0.3s;
        }
        .nav-link:hover, .nav-link.active { background: rgba(230, 126, 34, 0.2); color: #e67e22; }
        .nav-link i { margin-right: 12px; }

        .main-content { margin-left: 260px; padding: 40px; }

        .stat-card {
            border: none; border-radius: 20px; padding: 25px; color: white;
            height: 150px; position: relative; transition: 0.3s;
        }
        .card-orange { background: linear-gradient(135deg, #e67e22, #d35400); }
        .card-blue { background: linear-gradient(135deg, #3498db, #2980b9); }
        .card-green { background: linear-gradient(135deg, #27ae60, #2ecc71); }

        .chart-box {
            background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px; padding: 25px; margin-top: 30px; backdrop-filter: blur(10px);
        }

        .logout-btn {
            margin-top: auto; padding: 12px; border-radius: 10px;
            background: #c0392b; color: white; text-decoration: none;
            text-align: center; font-weight: bold; transition: 0.3s;
        }
        .logout-btn:hover { background: #e74c3c; color: white; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="brand-name">GLORY SPORT<br>ADMIN CENTER</div>
    
    <div class="nav-menu">
        <a href="dashboard.php" class="nav-link active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="konfirmasi.php" class="nav-link"><i class="fas fa-check-circle"></i> Konfirmasi</a>
        <a href="laporan.php" class="nav-link"><i class="fas fa-file-alt"></i> Laporan</a>
    </div>

    <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="main-content">
    <div class="mb-5">
        <h1 class="fw-bold">Halo, <span style="color: #e67e22;"><?= ucwords($nama_admin); ?></span>!</h1>
        <p class="text-white-50">Panel kontrol admin untuk manajemen lapangan.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="stat-card card-orange">
                <div class="small fw-bold text-uppercase opacity-75">Pendapatan Lunas</div>
                <div class="h2 fw-bold mt-2">Rp <?= number_format($pendapatan, 0, ',', '.'); ?></div>
                <i class="fas fa-wallet" style="position:absolute; right:20px; bottom:20px; font-size:3rem; opacity:0.2;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card card-blue">
                <div class="small fw-bold text-uppercase opacity-75">Total Pesanan</div>
                <div class="h2 fw-bold mt-2"><?= $total_booking; ?> Booking</div>
                <i class="fas fa-shopping-cart" style="position:absolute; right:20px; bottom:20px; font-size:3rem; opacity:0.2;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card card-green">
                <div class="small fw-bold text-uppercase opacity-75">Status Server</div>
                <div class="h2 fw-bold mt-2">ONLINE</div>
                <i class="fas fa-server" style="position:absolute; right:20px; bottom:20px; font-size:3rem; opacity:0.2;"></i>
            </div>
        </div>
    </div>

    <div class="chart-box">
        <h5 class="fw-bold mb-4"><i class="fas fa-chart-line me-2"></i>Statistik Mingguan</h5>
        <canvas id="incomeChart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('incomeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Aktivitas Booking',
                data: [12, 19, 13, 15, 22, 30, 25],
                borderColor: '#e67e22',
                backgroundColor: 'rgba(230, 126, 34, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { labels: { color: 'white' } } },
            scales: {
                y: { ticks: { color: 'white' }, grid: { color: 'rgba(255,255,255,0.1)' } },
                x: { ticks: { color: 'white' }, grid: { display: false } }
            }
        }
    });
</script>
</body>
</html>