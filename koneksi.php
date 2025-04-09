<?php
$host = "localhost"; 
$user = "root"; // Username MySQL (default di XAMPP: root)
$pass = ""; // Password MySQL (kosongkan jika belum diatur)
$db   = "olshop"; // Nama database

// Koneksi ke MySQL
$conn = mysqli_connect($host, $user, $pass, $db);

// Cek apakah koneksi berhasil
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
