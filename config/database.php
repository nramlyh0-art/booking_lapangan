<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "booking_lapangan"; // Pastikan nama database sudah benar

$db = mysqli_connect($host, $user, $pass, $db_name);

if (!$db) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>