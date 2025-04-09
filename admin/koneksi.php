<?php
$host = "localhost";  // Ganti dengan host database kamu (misalnya, "127.0.0.1" atau IP server)
$user = "root";       // Ganti dengan username database
$pass = "";           // Ganti dengan password database
$db   = "olshop"; // Ganti dengan nama database kamu

// Buat koneksi ke MySQL
$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Atur charset ke UTF-8 untuk mencegah masalah karakter
$conn->set_charset("utf8");
?>
