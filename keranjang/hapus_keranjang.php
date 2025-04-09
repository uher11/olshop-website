<?php
session_start();

if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];
    
    // Pastikan produk ada di dalam keranjang sebelum dihapus
    if (isset($_SESSION['keranjang'][$id_produk])) {
        unset($_SESSION['keranjang'][$id_produk]);
    }
}

// Redirect kembali ke halaman keranjang
header("Location: keranjang.php");
exit;
?>
