<?php
session_start();
include '../koneksi.php';

if (!isset($_GET['id_transaksi'])) {
    echo "<script>alert('Transaksi tidak ditemukan!'); window.location='../index/index.php';</script>";
    exit;
}

$id_transaksi = $_GET['id_transaksi'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['status']) && $_POST['status'] == "ya") {
        $query = "UPDATE transaksi SET status = 'Completed' WHERE id_transaksi = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_transaksi);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        echo "<script>alert('Pembayaran berhasil dikonfirmasi!'); window.location='../index/index.php';</script>";
        exit;
    } else {
        echo "<script>alert('Silakan selesaikan pembayaran terlebih dahulu!'); window.location='../index/index.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Konfirmasi Pembayaran</h2>
        <p>Apakah pembayaran Anda sudah selesai?</p>
        <form method="POST">
            <button type="submit" name="status" value="ya" class="btn btn-success">Ya, sudah selesai</button>
            <button type="submit" name="status" value="tidak" class="btn btn-danger">Belum</button>
        </form>
    </div>
</body>
</html>
