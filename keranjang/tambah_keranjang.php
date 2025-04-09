<?php
session_start();
if (isset($_POST['id_produk']) && isset($_POST['jumlah'])) {
    $id_produk = $_POST['id_produk'];
    $jumlah = $_POST['jumlah'];

    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Tambahkan produk ke keranjang atau update jumlahnya
    if (isset($_SESSION['keranjang'][$id_produk])) {
        $_SESSION['keranjang'][$id_produk] += $jumlah;
    } else {
        $_SESSION['keranjang'][$id_produk] = $jumlah;
    }

    echo json_encode(["status" => "success", "message" => "Produk ditambahkan ke keranjang"]);
    exit;
}
echo json_encode(["status" => "error", "message" => "Data tidak valid"]);
?>
