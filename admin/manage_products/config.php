<?php
$servername = "localhost";  // Sesuaikan jika berbeda
$username = "root";         // Ganti dengan username MySQL Anda
$password = "";             // Jika ada password, isi di sini
$dbname = "olshop";    // Nama database Anda

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
