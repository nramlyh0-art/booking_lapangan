<?php
session_start();
require 'config/database.php';

// Cek status login untuk navigasi
$is_logged_in = isset($_SESSION['id_user']);
// Menggunakan null coalescing ?? untuk mencegah error Undefined index
$nama_user = $_SESSION['nama'] ?? $_SESSION['nama_lengkap'] ?? '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GLORY SPORT CENTER</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            /* Gradasi Pink Fanta */
            --primary-grad: linear-gradient(90deg, #ff00ff 0%, #ff007f 100%);
            --bg-dark: #0a0a0a;
            --card-bg: rgba(255, 255, 255, 0.05);
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-dark);
            color: white;
            overflow-x: hidden;
        }

        /* Utility untuk Gradasi Font */
        .text-gradasi {
            background: var(--primary-grad);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
            font-weight: 900;
        }

        /* HEADER / NAVBAR STYLING */
        .navbar {
            padding: 15px 0;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #ff007f;
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 900;
            display: flex;
            align-items: center;
            text-transform: uppercase;
            font-size: 1.2rem;
        }

        .logo-img {
            height: 45px;
            margin-right: 12px;
        }

        .nav-link {
            font-weight: 700;
            font-size: 0.85rem;
            color: white !important;
            text-transform: uppercase;
            margin: 0 10px;
            transition: 0.3s;
        }

        .nav-link:hover { 
            color: #ff007f !important;
        }

        /* HERO SECTION */
        .hero-section {
            height: 85vh;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.9)), 
                        url('assets/images/bg-stadium.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            text-align: center;
        }

        .hero-title {
            font-size: 4.5rem;
            font-weight: 900;
            text-transform: uppercase;
        }

        /* LAPANGAN CARD */
        .lapangan-card {
            background: var(--card-bg);
            border-radius: 20px;
            border: 1px solid rgba(255, 0, 127, 0.2);
            transition: 0.3s;
            overflow: hidden;
            height: 100%;
        }
        .lapangan-card:hover { 
            transform: translateY(-10px); 
            border-color: #ff007f; 
            box-shadow: 0 0 20px rgba(255, 0, 127, 0.3);
        }

        .btn-fanta {
            background: var(--primary-grad);
            color: white !important;
            border: none;
            font-weight: 700;
            border-radius: 50px;
            padding: 8px 25px;
            transition: 0.3s;
        }

        .btn-fanta:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(255, 0, 127, 0.5);
        }

        /* FOOTER */
        footer {
            background: linear-gradient(rgba(0,0,0,0.9), rgba(0,0,0,1)), 
                        url('assets/images/bg-stadium.jpg');
            background-size: cover;
            padding: 80px 0 30px;
            border-top: 3px solid #ff007f;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/images/logo-glory.png" alt="Logo" class="logo-img">
                <span class="text-gradasi">GLORY SPORT CENTER</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">HOME</a></li>
                    <li class="nav-item"><a class="nav-link" href="user/booking.php">BOOKING</a></li>
                    <li class="nav-item"><a class="nav-link" href="user/dashboard.php">DASHBOARD</a></li>
                    
                    <?php if($is_logged_in): ?>
                        <li class="nav-item dropdown ms-lg-3">
                            <a class="nav-link dropdown-toggle btn-fanta" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> <?= strtoupper($nama_user) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end">
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>LOGOUT</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-lg-3">
                            <a href="auth.php" class="btn btn-fanta btn-sm px-4">LOGIN</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container">
            <h1 class="hero-title text-gradasi">GLORY SPORT CENTER</h1>
            <p class="lead text-white-50 mb-4 fw-bold">Premium Courts for Champions. Your game, our priority.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="user/booking.php" class="btn btn-fanta btn-lg px-5 py-3 fw-bold rounded-pill">BOOK NOW</a>
            </div>
        </div>
    </header>

    <section id="lapangan" class="py-5">
        <div class="container py-5 text-center">
            <h2 class="fw-900 mb-5 text-uppercase">PILIHAN <span class="text-gradasi">LAPANGAN</span></h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="lapangan-card shadow">
                        <img src="assets/images/futsal-vinyl.jpg" class="w-100" style="height:220px; object-fit:cover;" alt="Futsal Vinyl">
                        <div class="p-4">
                            <h4 class="fw-bold">Futsal Vinyl</h4>
                            <h5 class="text-gradasi fw-bold">Rp 150.000 / Jam</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="lapangan-card shadow">
                        <img src="assets/images/futsal-interlock.jpg" class="w-100" style="height:220px; object-fit:cover;" alt="Futsal Interlock">
                        <div class="p-4">
                            <h4 class="fw-bold">Futsal Interlock</h4>
                            <h5 class="text-gradasi fw-bold">Rp 135.000 / Jam</h5>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="lapangan-card shadow">
                        <img src="assets/images/badminton-synthesis.jpg" class="w-100" style="height:220px; object-fit:cover;" alt="Badminton Synthesis">
                        <div class="p-4">
                            <h4 class="fw-bold">Badminton Synthesis</h4>
                            <h5 class="text-gradasi fw-bold">Rp 50.000 / Jam</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row g-5">
                <div class="col-md-4">
                    <h4 class="text-gradasi fw-900">GLORY SPORT</h4>
                    <p class="text-white-50 small mb-4">Penyedia sarana olahraga terbaik dengan fasilitas modern dan standar kualitas tinggi.</p>
                    <p class="small mb-1"><i class="fas fa-envelope text-gradasi me-2"></i> glorysport@gmail.com</p>
                    <p class="small"><i class="fab fa-whatsapp text-gradasi me-2"></i> +62 895-0993-5256</p>
                </div>
                <div class="col-md-2">
                    <h5 class="fw-bold mb-4 text-gradasi">NAVIGASI</h5>
                    <a href="index.php" class="text-white-50 text-decoration-none d-block mb-2">Home</a>
                    <a href="user/booking.php" class="text-white-50 text-decoration-none d-block mb-2">Booking</a>
                    <a href="user/dashboard.php" class="text-white-50 text-decoration-none d-block mb-2">Dashboard</a>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-bold mb-4 text-gradasi">LOKASI KAMI</h5>
                    <div style="border-radius:15px; overflow:hidden; border:1px solid #ff007f;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56347862248!2d107.57311640000001!3d-6.9034443!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146a50b61d473489!2sBandung%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1700000000000!5m2!1sen!2sid" width="100%" height="220" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5 pt-4 border-top border-secondary">
                <p class="text-white-50 small m-0">© 2026 <span class="text-gradasi fw-bold">GLORY SPORT CENTER</span>. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>