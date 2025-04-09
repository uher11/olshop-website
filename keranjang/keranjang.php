<?php
session_start();
include '../koneksi.php';
$totalBelanjaUSD = 0;
$kurs = 16000;?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Cemillicious</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="keranjang.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index/index.php">🛒 Cemillicious</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../index/index.php">🏠 Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../produk/produk.php">📦 Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="../tentang/tentang.php">ℹ️ About Us</a></li>
                    <li class="nav-item"><a class="nav-link active btn btn-primary text-white px-3" href="#">🛍️ Cart</a></li>

                    <?php if (isset($_SESSION['role'])): ?> 
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger text-white px-3 ms-2" href="../admin/akun/logout.php">🚪 Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <h2 class="text-center mb-4">Shopping Cart</h2>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($_SESSION['keranjang'])) {
                        $totalBelanjaUSD = 0; // Initialize total shopping amount in USD
                        $kurs = 16000; // Exchange rate

                        foreach ($_SESSION['keranjang'] as $id_produk => $jumlah) {
                            $query = "SELECT * FROM product WHERE id_product = ?";
                            $stmt = mysqli_prepare($conn, $query);
                            mysqli_stmt_bind_param($stmt, "i", $id_produk);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            if ($row = mysqli_fetch_assoc($result)) {
                                $subTotalUSD = $row['price'] * $jumlah;
                                $totalBelanjaUSD += $subTotalUSD;
                                $subTotalIDR = $subTotalUSD * $kurs;

                                $gambar = "../produk/gambar_produk/" . $row['id_product'] . "_" . strtolower(str_replace(' ', '_', $row['name'])) . ".webp";
                                if (!file_exists($gambar)) {
                                    $gambar = "gambar_produk/default.webp";
                                }
                    ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($gambar) ?>" alt="<?= htmlspecialchars($row['name']) ?>"></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>$<?= number_format($row['price'], 2, '.', ',') ?></td>
                        <td>
                            <form action="update_keranjang.php" method="post" class="d-flex align-items-center">
                                <input type="hidden" name="id_produk" value="<?= $id_produk ?>">
                                <input type="number" name="jumlah" value="<?= $jumlah ?>" min="1" class="form-control me-2" style="width: 70px;">
                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                            </form>
                        </td>
                        <td>$<?= number_format($subTotalUSD, 2, '.', ',') ?></td>
                        <td>
                            <a href="hapus_keranjang.php?id=<?= $id_produk ?>" class="btn btn-remove btn-sm">Remove</a>
                        </td>
                    </tr>
                    <?php
                            }
                            mysqli_stmt_close($stmt);
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center">Your cart is empty</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            <div class="text-end">
                <h4>Total: $<?= number_format($totalBelanjaUSD, 2, '.', ',') ?></h4>
                <h6>Estimated in Rupiah: Rp <?= number_format($totalBelanjaUSD * $kurs, 0, ',', '.') ?></h6>
                <?php if ($totalBelanjaUSD > 0): ?>
                    <a href="../checkout/checkout.php" class="btn btn-success">Checkout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>


    <footer>
        <p>&copy; 2025 Toko Online - All Rights Reserved</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>