<?php
$host = "localhost";
$user = "root";  // Ganti jika ada password
$pass = "";
$db = "olshop";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>

<?php
define('MIDTRANS_SERVER_KEY', 'SB-Mid-server-NnhXzDNuad98IZpX3Z5n_6CG');
