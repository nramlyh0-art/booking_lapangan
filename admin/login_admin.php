<?php
session_start();
require '../config/database.php';

/**
 * Catatan: Bagian auto-redirect (jika sudah login langsung ke dashboard) 
 * sengaja saya matikan agar Anda bisa melihat form login ini terus.
 */

$error = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_input = $_POST['password']; // Ambil password murni untuk verifikasi hash

    // 1. Cari admin berdasarkan email
    $query = mysqli_query($db, "SELECT * FROM admin WHERE email = '$email'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);
        
        // 2. Verifikasi Password Hash (Penting!)
        if (password_verify($password_input, $data['password'])) {
            // Jika benar, buat session
            $_SESSION['role'] = 'admin';
            $_SESSION['username'] = $data['username'];
            $_SESSION['id_admin'] = $data['id_admin'];
            
            // Pindah ke dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password yang Anda masukkan salah!";
        }
    } else {
        $error = "Akun email admin tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Admin Login | Glory Sport</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --glory-orange: #e67e22; }
        body { 
            font-family: 'Montserrat', sans-serif; 
            background: radial-gradient(circle at center, #2c3e50, #000); 
            height: 100vh; display: flex; align-items: center; justify-content: center; margin: 0; 
        }
        .login-card { 
            background: rgba(255, 255, 255, 0.05); 
            backdrop-filter: blur(15px); 
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 40px; padding: 50px 40px; width: 100%; max-width: 400px; text-align: center;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }
        .logo-container { 
            width: 80px; height: 80px; background: var(--glory-orange); 
            border-radius: 20px; margin: 0 auto 30px; display: flex; 
            align-items: center; justify-content: center; font-size: 40px; 
            color: white; font-weight: 900; transform: rotate(-8deg);
            box-shadow: 0 10px 25px rgba(230, 126, 34, 0.4);
        }
        .error-msg { 
            background: rgba(231, 76, 60, 0.2); color: #ff7675; 
            padding: 12px; border-radius: 15px; font-size: 13px; 
            margin-bottom: 20px; border: 1px solid rgba(231, 76, 60, 0.3); 
        }
        .input-group { 
            background: rgba(255, 255, 255, 0.08); border-radius: 15px; 
            display: flex; align-items: center; margin-bottom: 15px; overflow: hidden;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .input-group i { padding: 18px; color: var(--glory-orange); width: 55px; border-right: 1px solid rgba(255,255,255,0.1); }
        input { background: transparent; border: none; padding: 18px; color: white; width: 100%; outline: none; font-size: 14px; }
        .btn-login { 
            background: var(--glory-orange); color: white; border: none; 
            width: 100%; padding: 16px; border-radius: 15px; font-weight: 700; 
            cursor: pointer; transition: 0.3s; margin-top: 10px; font-size: 16px;
        }
        .btn-login:hover { background: #d35400; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(230, 126, 34, 0.3); }
        .footer-link { margin-top: 25px; }
        .footer-link a { color:#666; font-size:12px; text-decoration:none; transition: 0.3s; }
        .footer-link a:hover { color: #888; }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-container">G</div>
        <h2 style="color:white; font-weight:800; margin:0; letter-spacing: 2px;">ADMIN <span style="color:var(--glory-orange)">LOGIN</span></h2>
        <p style="color:#888; font-size:13px; margin-bottom:30px;">Secure Access Hashing Protected</p>

        <?php if($error): ?>
            <div class="error-msg"><i class="fas fa-exclamation-circle me-2"></i> <?= $error ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email Admin" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn-login">LOGIN KE SISTEM <i class="fas fa-arrow-right ms-2"></i></button>
        </form>
        
        <div class="footer-link">
            <a href="../index.php"><i class="fas fa-chevron-left me-1"></i> Kembali ke Beranda</a>
        </div>
    </div>
</body>
</html>