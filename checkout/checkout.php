<?php
session_start();

// Jika pengguna belum login, arahkan ke halaman login
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

include '../config.php';

require_once '../Midtrans.php';

// Konfigurasi Midtrans
Midtrans\Config::$serverKey = 'SB-Mid-server-NnhXzDNuad98IZpX3Z5n_6CG';
Midtrans\Config::$isProduction = false; // Ubah ke true jika sudah live
Midtrans\Config::$isSanitized = true;
Midtrans\Config::$is3ds = true;

if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang belanja kosong!'); window.location='../index/index.php';</script>";
    exit;
}

$totalBelanjaUSD = 0;
$kurs = 16000;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $tanggal = date("Y-m-d H:i:s");
    $status = "Pending"; // Tambahkan status default

    // Simpan transaksi dengan status Pending
    $query = "INSERT INTO transaksi (nama_pelanggan, alamat, telepon, tanggal_transaksi, total_harga, status) VALUES (?, ?, ?, ?, 0, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $nama, $alamat, $telepon, $tanggal, $status);
    mysqli_stmt_execute($stmt);
    $id_transaksi = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    $items = [];
    foreach ($_SESSION['keranjang'] as $id_produk => $jumlah) {
        $query = "SELECT name, price, stock FROM product WHERE id_product = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id_produk);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if ($row = mysqli_fetch_assoc($result)) {
            if ($row['stock'] >= $jumlah) { // Pastikan stok mencukupi
                $subTotalUSD = $row['price'] * $jumlah;
                $totalBelanjaUSD += $subTotalUSD;

                // Kurangi stok produk
                $query_update_stock = "UPDATE product SET stock = stock - ? WHERE id_product = ?";
                $stmt3 = mysqli_prepare($conn, $query_update_stock);
                mysqli_stmt_bind_param($stmt3, "ii", $jumlah, $id_produk);
                mysqli_stmt_execute($stmt3);
                mysqli_stmt_close($stmt3);

                // Simpan detail transaksi
                $query = "INSERT INTO detail_transaksi (id_transaksi, id_product, jumlah, harga_total) VALUES (?, ?, ?, ?)";
                $stmt2 = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt2, "iiid", $id_transaksi, $id_produk, $jumlah, $subTotalUSD);
                mysqli_stmt_execute($stmt2);
                mysqli_stmt_close($stmt2);

                // Tambah ke daftar item Midtrans
                $items[] = [
                    'id' => $id_produk,
                    'price' => $row['price'] * $kurs,
                    'quantity' => $jumlah,
                    'name' => $row['name']
                ];
            } else {
                echo json_encode(['error' => 'Stock tidak mencukupi untuk produk: ' . $row['name']]);
                exit;
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Konversi total belanja ke IDR untuk Midtrans
    $totalBelanjaIDR = round($totalBelanjaUSD * $kurs);

    // Update total harga transaksi
    $query = "UPDATE transaksi SET total_harga = ? WHERE id_transaksi = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "di", $totalBelanjaIDR, $id_transaksi);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Hapus keranjang setelah transaksi berhasil
    unset($_SESSION['keranjang']);

    // Data untuk Midtrans
    $transaction_details = [
        'order_id' => "TRANS_" . $id_transaksi,
        'gross_amount' => $totalBelanjaIDR
    ];

    $customer_details = [
        'first_name' => $nama,
        'phone' => $telepon,
        'address' => $alamat
    ];

    $transaction = [
        'transaction_details' => $transaction_details,
        'customer_details' => $customer_details,
        'item_details' => $items
    ];

    $snapToken = Midtrans\Snap::getSnapToken($transaction);
    echo json_encode(['token' => $snapToken, 'id_transaksi' => $id_transaksi]);
    exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Toko Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-2W8glEypcHQFHBjy"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="checkout.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index/index.php">🛒 Online Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index/index.php">🏠 Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../produk/produk.php">📦 Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="../tentang/tentang.php">ℹ️ About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="../keranjang/keranjang.php">🛍️ Cart</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-center">Checkout</h2>
        <form id="checkout-form">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="nama" id="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Shipping Address</label>
                <textarea name="alamat" id="alamat" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" name="telepon" id="telepon" class="form-control" required>
            </div>
            <button type="button" id="pay-button" class="btn btn-success">Confirm & Pay</button>
            <a href="../keranjang/keranjang.php" class="btn btn-secondary">Back to Cart</a>
        </form>
    </div>
    <script src="checkout.js"></script>
</body>
</html>
