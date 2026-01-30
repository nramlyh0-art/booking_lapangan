<?php
session_start();
require 'config/database.php';

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['id_user'])) {
    header("Location: user/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOIN GLORY | GLORY SPORT CENTER</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-grad: linear-gradient(90deg, #ff416c 0%, #ff4b2b 100%);
            --bg-dark: #0a0a0a;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.85)), 
                        url('assets/images/bg-stadium.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            padding: 40px 0;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px;
            border-radius: 25px;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.6);
        }

        .text-glory {
            background: var(--primary-grad);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 900;
        }

        .logo-img {
            height: 50px;
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: rgba(255,255,255,0.7);
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: white;
            padding: 12px 15px;
            transition: 0.3s;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.12);
            border-color: #ff416c;
            color: white;
            box-shadow: none;
        }

        .btn-register {
            background: var(--primary-grad);
            border: none;
            border-radius: 12px;
            color: white;
            font-weight: 800;
            text-transform: uppercase;
            width: 100%;
            padding: 14px;
            letter-spacing: 1px;
            transition: 0.4s;
            margin-top: 20px;
        }

        .btn-register:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 65, 108, 0.4);
            color: white;
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.6);
        }

        .login-link a {
            color: #ff416c;
            text-decoration: none;
            font-weight: 700;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        /* Styling Ikon di Input */
        .input-group-text {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255,255,255,0.6);
            border-radius: 12px 0 0 12px;
        }
        
        .has-icon .form-control {
            border-radius: 0 12px 12px 0;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="text-center mb-4">
            <img src="assets/images/logo-glory.png" alt="Logo Glory" class="logo-img">
            <h2 class="fw-900 uppercase m-0">JOIN <span class="text-glory">GLORY</span></h2>
            <p class="small text-white-50 mt-2">Daftar sekarang untuk mulai memesan lapangan!</p>
        </div>

        <form action="proses_register.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <div class="input-group has-icon">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="nama" class="form-control" placeholder="Masukkan nama Anda" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group has-icon">
                        <span class="input-group-text"><i class="fas fa-at"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="username123" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">WhatsApp</label>
                    <div class="input-group has-icon">
                        <span class="input-group-text"><i class="fab fa-whatsapp"></i></span>
                        <input type="text" name="whatsapp" class="form-control" placeholder="62812..." required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group has-icon">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="email@contoh.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group has-icon">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" name="register" class="btn-register">Daftar Sekarang</button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="auth.php">Login di sini</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>