<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Hapus semua variabel sesi
$_SESSION = [];

// Hancurkan sesi
session_destroy();

// Set header untuk mencegah caching
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');

// Alihkan pengguna ke halaman login
header('Location: ../index.php');
exit();
?>
