<?php
session_start();
require_once '../config/database.php';

// Proteksi Halaman Admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth.php");
    exit;
}

// Ambil data statistik sederhana (Sesuaikan nama tabel/kolom dengan database kamu)
$query_pendapatan = mysqli_query($db, "SELECT SUM(total_harga) as total FROM booking WHERE status = 'Lunas'");
$pendapatan = mysqli_fetch_assoc($query_pendapatan)['total'] ?? 0;

$query_booking = mysqli_query($db, "SELECT COUNT(*) as total FROM booking");
$total_booking = mysqli_fetch_assoc($query_booking)['total'] ?? 0;
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
            background: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)), 
                        url('../assets/images/bg-stadium.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            min-height: 100vh;
            margin: 0;
        }

        /* Sidebar Glassmorphism */
        .sidebar {
            width: 260px; 
            height: 100vh; 
            position: fixed;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(15px);
            padding: 30px 20px;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .brand-name { 
            font-weight: 800; 
            font-size: 1.4rem; 
            color: #00d2ff; 
            margin-bottom: 40px; 
            line-height: 1.2; 
            text-transform: uppercase;
        }

        .nav-menu { flex-grow: 1; }

        .nav-link {
            color: rgba(255, 255, 255, 0.5); 
            padding: 12px 15px; 
            border-radius: 12px;
            margin-bottom: 8px; 
            font-weight: 600; 
            text-decoration: none; 
            display: flex; 
            align-items: center;
            transition: 0.3s;
        }

        .nav-link i { margin-right: 12px; width: 20px; text-align: center; }

        .nav-link:hover, .nav-link.active { 
            background: rgba(0, 210, 255, 0.15); 
            color: #00d2ff; 
        }

        .main-content { margin-left: 260px; padding: 40px; }

        /* Stat Cards */
        .stat-card {
            border: none; border-radius: 24px; padding: 25px; color: white;
            height: 160px; position: relative; overflow: hidden;
            transition: 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); }
        
        .card-blue { background: linear-gradient(135deg, #00d2ff, #3a7bd5); }
        .card-purple { background: linear-gradient(135deg, #8e2de2, #4a00e0); }
        .card-green { background: linear-gradient(135deg, #11998e, #38ef7d); }

        .stat-card .label { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; opacity: 0.9; }
        .stat-card .value { font-size: 1.85rem; font-weight: 800; margin-top: 5px; }

        /* Chart Section */
        .chart-box {
            background: rgba(255, 255, 255, 0.03); 
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px; padding: 30px; margin-top: 30px; 
            backdrop-filter: blur(10px);
        }

        .logout-btn {
            background: rgba(255, 255, 255, 0.05); 
            color: #ff4d4d; 
            text-align: center;
            padding: 12px; 
            border-radius: 12px; 
            text-decoration: none; 
            font-weight: 700;
            border: 1px solid rgba(255,77,77,0.2);
            transition: 0.3s;
            margin-top: auto;
        }
        .logout-btn:hover { background: rgba(255, 77, 77, 0.1); color: #ff4d4d; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="brand-name">GLORY SPORT<br>ADMIN</div>
    
    <div class="nav-menu">
        <a href="dashboard.php" class="nav-link active">
            <i class="fas fa-chart-pie"></i> Dashboard
        </a>
        <a href="konfirmasi.php" class="nav-link">
            <i class="fas fa-check-double"></i> Konfirmasi
        </a>
        <a href="laporan.php" class="nav-link">
            <i class="fas fa-file-invoice-dollar"></i> Laporan
        </a>
    </div>

    <a href="../logout.php" class="logout-btn">
        <i class="fas fa-power-off me-2"></i> Logout
    </a>
</div>

<div class="main-content">
    <div class="mb-5">
        <h1 class="fw-800">Selamat Datang, <span style="color: #00d2ff;"><?= explode(' ', $_SESSION['nama_lengkap'])[0]; ?></span></h1>
        <p class="text-white-50">Ringkasan performa Glory Sport Center hari ini.</p>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="stat-card card-blue">
                <div class="label">Hari Ini</div>
                <div class="value">Rp <?= number_format($pendapatan, 0, ',', '.'); ?></div>
                <i class="fas fa-wallet" style="position:absolute; right:20px; bottom:20px; font-size:3rem; opacity:0.2;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card card-purple">
                <div class="label">Total Booking</div>
                <div class="value"><?= $total_booking; ?> Pesanan</div>
                <i class="fas fa-calendar-check" style="position:absolute; right:20px; bottom:20px; font-size:3rem; opacity:0.2;"></i>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card card-green">
                <div class="label">Estimasi Pendapatan</div>
                <div class="value">Rp <?= number_format($pendapatan * 1.2, 0, ',', '.'); ?></div>
                <i class="fas fa-chart-line" style="position:absolute; right:20px; bottom:20px; font-size:3rem; opacity:0.2;"></i>
            </div>
        </div>
    </div>

    <div class="chart-box">
        <h5 class="fw-bold mb-4">Tren Pendapatan (7 Hari Terakhir)</h5>
        <canvas id="incomeChart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('incomeChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(0, 210, 255, 0.4)');
    gradient.addColorStop(1, 'rgba(0, 210, 255, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['24 Jan', '25 Jan', '26 Jan', '27 Jan', '28 Jan', '29 Jan', '30 Jan'],
            datasets: [{
                label: 'Pendapatan',
                data: [200000, 450000, 300000, 600000, 400000, 750000, 500000],
                borderColor: '#00d2ff',
                backgroundColor: gradient,
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#00d2ff',
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: 'rgba(255, 255, 255, 0.1)' }, ticks: { color: 'rgba(255, 255, 255, 0.5)' } },
                x: { grid: { display: false }, ticks: { color: 'rgba(255, 255, 255, 0.5)' } }
            }
        }
    });
</script>

</body>
</html>