<?php
session_start();

// Menghapus semua data session
session_unset();

// Menghancurkan session yang ada
session_destroy();

// Mengarahkan kembali ke halaman login atau landing page
header("Location: index.php");
exit;
?>