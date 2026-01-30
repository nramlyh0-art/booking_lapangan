<?php
session_start();
require '../config/database.php';

// Proteksi halaman: Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth.php");
    exit;
}

// SOLUSI ERROR: Ganti 'nama' menjadi 'nama_lengkap' sesuai kolom di database Anda
$nama_user = isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'User';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | GLORY SPORT CENTER</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --glory-pink: #ff416c;
            --glory-orange: #ff4b2b;
            --bg-dark: #0a0a0a;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            /* Background utama menggunakan bg-stadium.jpg */
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.9)), 
                        url('../assets/images/bg-stadium.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            min-height: 100vh;
        }

        /* NAVBAR */
        .navbar {
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Logo di kiri atas */
        .navbar-brand { 
            font-weight: 900; 
            display: flex; 
            align-items: center; 
            color: white !important; 
        }
        .logo-nav { height: 40px; margin-right: 15px; }

        /* Menu navigasi ke arah kanan */
        .nav-link { 
            font-weight: 700; 
            font-size: 0.85rem; 
            color: white !important; 
            text-transform: uppercase;
            margin-left: 20px;
        }
        .nav-link:hover, .nav-link.active { color: var(--glory-pink) !important; }

        .btn-logout {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            transition: 0.3s;
        }
        .btn-logout:hover { background: var(--glory-pink); border-color: var(--glory-pink); color: white; }

        /* WELCOME BANNER */
        .welcome-banner {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(5px);
            border-radius: 20px;
            padding: 40px;
            margin-top: 40px;
            border-left: 6px solid var(--glory-pink);
            position: relative;
        }
        .welcome-banner h1 { font-weight: 900; font-size: 3rem; margin: 0; }
        .highlight { color: var(--glory-pink); }
        
        .info-top {
            position: absolute;
            top: 40px;
            right: 40px;
            text-align: right;
        }

        /* CARD MENU */
        .menu-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: 0.3s;
            text-decoration: none;
            color: white;
            display: block;
            height: 100%;
        }
        .menu-card:hover { transform: translateY(-10px); border-color: var(--glory-pink); background: rgba(255, 255, 255, 0.1); }
        
        .icon-circle {
            width: 60px;
            height: 60px;
            background: rgba(255, 65, 108, 0.2);
            color: var(--glory-pink);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 15px;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../assets/images/logo-glory.png" alt="Logo" class="logo-nav">
                GLORY <span style="color: var(--glory-pink); margin-left: 5px;">SPORT</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="../index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="booking.php">BOOKING</a></li>
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">DASHBOARD</a></li>
                    <li class="nav-item ms-lg-3">
                        <a href="../logout.php" class="btn-logout">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-banner">
            <div class="info-top d-none d-md-block">
                <small class="text-white-50">SYSTEM DATE</small>
                <div class="fw-bold" style="color: var(--glory-pink);"><?= date('l, d F Y'); ?></div>
            </div>
            <h1>HALO, <span class="highlight"><?= strtoupper($nama_user); ?>!</span></h1>
            <p class="text-white-50 mt-2">Selamat datang kembali di arena kebanggaan para juara.</p>
        </div>

        <div class="row mt-4 g-4 mb-5">
            <div class="col-md-4">
                <a href="booking.php" class="menu-card">
                    <div class="icon-circle"><i class="fas fa-calendar-alt"></i></div>
                    <h4 class="fw-bold">PESAN ARENA</h4>
                    <p class="text-white-50 small">Cek jadwal kosong dan amankan slot mainmu sekarang juga.</p>
                </a>
            </div>
            <div class="col-md-4">
                <a href="riwayat.php" class="menu-card">
                    <div class="icon-circle" style="color: #00d2ff; background: rgba(0, 210, 255, 0.2);"><i class="fas fa-history"></i></div>
                    <h4 class="fw-bold">RIWAYAT</h4>
                    <p class="text-white-50 small">Pantau status verifikasi admin dan jadwal booking yang telah dibuat.</p>
                </a>
            </div>
            <div class="col-md-4">
                <div class="menu-card">
                    <div class="icon-circle" style="color: #2ecc71; background: rgba(46, 204, 113, 0.2);"><i class="fas fa-check-circle"></i></div>
                    <h4 class="fw-bold">SIAP MAIN!</h4>
                    <p class="text-white-50 small">Semua pesanan Anda aman. Pastikan datang tepat waktu ke arena.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>