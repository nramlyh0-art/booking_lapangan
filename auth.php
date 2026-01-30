<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register | GLORY SPORT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --glory-red: #ff416c;
            --glory-dark: #000;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--glory-dark);
            background-image: radial-gradient(circle at top right, rgba(255, 65, 108, 0.15), transparent),
                              url('https://images.unsplash.com/photo-1574629810360-7efbbe195018?auto=format&fit=crop&q=80&w=1920');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .auth-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }

        .brand-logo { font-weight: 900; font-size: 1.8rem; letter-spacing: -1px; margin-bottom: 30px; text-align: center; }
        .brand-logo span { color: var(--glory-red); }

        .nav-pills {
            background: rgba(255,255,255,0.05);
            border-radius: 15px;
            padding: 5px;
            margin-bottom: 30px;
        }

        .nav-link {
            color: white !important;
            font-weight: 700;
            border-radius: 12px !important;
            transition: 0.3s;
        }

        .nav-link.active { background: var(--glory-red) !important; box-shadow: 0 5px 15px rgba(255, 65, 108, 0.4); }

        .form-label { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: rgba(255,255,255,0.5); }
        
        .form-control {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: white !important;
            border-radius: 12px;
            padding: 12px 15px;
            margin-bottom: 15px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 3px rgba(255, 65, 108, 0.25);
            border-color: var(--glory-red) !important;
        }

        .btn-auth {
            background: var(--glory-red);
            border: none;
            color: white;
            font-weight: 900;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-auth:hover {
            background: #e0355d;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 65, 108, 0.3);
        }
    </style>
</head>
<body>

<div class="auth-container">
    <div class="brand-logo">GLORY <span>SPORT</span></div>

    <ul class="nav nav-pills nav-fill mb-4" id="pills-tab" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#pills-login" type="button">LOGIN</button>
        </li>
        <li class="nav-item">
            <button class="nav-link" id="pills-reg-tab" data-bs-toggle="pill" data-bs-target="#pills-reg" type="button">REGISTER</button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        
        <div class="tab-pane fade show active" id="pills-login">
            <form action="proses_login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Email Arena</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <button type="submit" name="login" class="btn-auth">MASUK SEKARANG</button>
            </form>
        </div>

        <div class="tab-pane fade" id="pills-reg">
            <form action="proses_register.php" method="POST">
                <div class="mb-2">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: John Doe" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Buat Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                </div>
                
                <p style="font-size: 0.7rem; color: rgba(255,255,255,0.4); text-align: center; margin-top: 10px;">
                    Dengan mendaftar, Anda otomatis bergabung sebagai member Glory Sport.
                </p>

                <button type="submit" name="register" class="btn-auth">DAFTAR AKUN</button>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>