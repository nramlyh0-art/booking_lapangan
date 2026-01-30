<?php
session_start();

// 1. Cek apakah sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth.php");
    exit;
}

// 2. Cek Folder Akses vs Role
$current_path = $_SERVER['PHP_SELF'];

if (strpos($current_path, '/admin/') !== false && $_SESSION['role'] !== 'admin') {
    // Jika user biasa coba-coba masuk folder admin
    echo "<script>alert('Akses Ilegal! Anda bukan Admin.'); window.location='../user/dashboard.php';</script>";
    exit;
}

if (strpos($current_path, '/user/') !== false && $_SESSION['role'] !== 'user') {
    // Jika admin masuk ke folder user (opsional, tapi bagus untuk konsistensi)
    header("Location: ../admin/dashboard.php");
    exit;
}
?>