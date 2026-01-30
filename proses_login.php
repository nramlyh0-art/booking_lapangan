<?php
session_start();
require_once 'config/database.php'; 

if (isset($_POST['login'])) {
    $email    = mysqli_real_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($db, "SELECT * FROM user WHERE email = '$email'");

    if (mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);

        // Mencocokkan password input dengan Hash di database
        if (password_verify($password, $row['password'])) {
            
            $_SESSION['id_user']      = $row['id_user'];
            $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
            $_SESSION['role']         = $row['role'];

            // Redirect & Pastikan tidak ada TYPO pada nama folder/file
            if ($row['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: user/dashboard.php");
            }
            exit;

        } else {
            echo "<script>alert('Password Salah!'); window.location='auth.php';</script>";
        }
    } else {
        echo "<script>alert('Email Tidak Ditemukan!'); window.location='auth.php';</script>";
    }
}
?>