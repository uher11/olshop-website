<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_produk'], $_POST['jumlah'])) {
        $id_produk = $_POST['id_produk'];
        $jumlah = (int) $_POST['jumlah'];
        
        // Pastikan jumlah minimal 1
        if ($jumlah > 0) {
            $_SESSION['keranjang'][$id_produk] = $jumlah;
        } else {
            unset($_SESSION['keranjang'][$id_produk]);
        }
    }
}

// Redirect kembali ke halaman keranjang
header("Location: keranjang.php");
exit;
?>
