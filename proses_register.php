<?php
session_start();
require_once 'config/database.php'; 

if (isset($_POST['register'])) {
    $nama_lengkap = mysqli_real_escape_string($db, $_POST['nama_lengkap']);
    $email        = mysqli_real_escape_string($db, $_POST['email']);
    $password     = $_POST['password'];
    $role         = $_POST['role'] ?? 'user';

    // OTOMATISASI USERNAME: Mengambil teks sebelum '@' dari email
    // Contoh: john@gmail.com menjadi username 'john'
    $username     = explode('@', $email)[0];

    // 1. Cek apakah email sudah ada
    $cek_user = mysqli_query($db, "SELECT email FROM user WHERE email = '$email'");
    if (mysqli_num_rows($cek_user) > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.location='auth.php';</script>";
        exit;
    }

    // 2. Hash Password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 3. Insert ke Database (Menambahkan kolom 'username' agar tidak error duplikat '')
    $query = "INSERT INTO user (nama_lengkap, email, username, password, role) 
              VALUES ('$nama_lengkap', '$email', '$username', '$password_hash', '$role')";

    if (mysqli_query($db, $query)) {
        echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='auth.php';</script>";
    } else {
        // Jika masih error, tampilkan pesan spesifik
        echo "Error: " . mysqli_error($db);
    }
}
?>