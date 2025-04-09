<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../akun/login.php");
    exit;
}
include '../config.php';

// Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "olshop";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ID dari parameter URL
$id = $_GET["id"];

// Query untuk mengambil data produk
$sql = "SELECT * FROM product WHERE id = '$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "Produk tidak ditemukan"]);
}

$conn->close();
?>
