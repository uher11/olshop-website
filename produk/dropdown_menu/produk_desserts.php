<?php
session_start();

// Jika pengguna belum login, arahkan ke halaman login
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}

include '../../config.php';

// Ambil daftar produk dari database
$query = "SELECT * FROM product WHERE category = 'desserts'";
$result = mysqli_query($conn, $query);

// Jika tombol tambah ke keranjang diklik
if (isset($_POST['tambah_keranjang'])) {
    $id_produk = $_POST['id_produk'];
    $harga = $_POST['harga'];
    
    // Jika keranjang belum ada, buat array kosong
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }
    
    // Tambahkan produk ke dalam keranjang
    if (!isset($_SESSION['keranjang'][$id_produk])) {
        $_SESSION['keranjang'][$id_produk] = [
            'id' => $id_produk,
            'harga' => $harga,
            'jumlah' => 1
        ];
    } else {
        $_SESSION['keranjang'][$id_produk]['jumlah']++;
    }
    
    // Hitung total belanja
    $totalBelanja = 0;
    foreach ($_SESSION['keranjang'] as $item) {
        $totalBelanja += $item['harga'] * $item['jumlah'];
    }
    
    // Simpan total belanja ke sesi
    $_SESSION['totalBelanja'] = $totalBelanja;
    
    echo "<script>alert('Produk ditambahkan ke keranjang!'); window.location='../produk/produk.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Online - Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="produk_desserts.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../../index/index.php">🛒 Cemillicious</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="../../index/index.php">🏠 Home</a></li>
                    <li class="nav-item"><a class="nav-link active btn btn-primary text-white px-3" href="#">📦 Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../tentang/tentang.php">ℹ️ About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="../../keranjang/keranjang.php">🛍️ Cart</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="text-center mb-4">Product List</h2>

        <div class="text-center mb-4">
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="productDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Desserts
                </button>
                <ul class="dropdown-menu" aria-labelledby="productDropdown">
                    <li><a class="dropdown-item" href="../produk.php" onclick="updateDropdown('All')">All</a></li>
                    <li><a class="dropdown-item" href="produk_desserts.php" onclick="updateDropdown('Desserts')">Desserts</a></li>
                    <li><a class="dropdown-item" href="produk_drinks.php" onclick="updateDropdown('Drinks')">Drinks</a></li>
                    <li><a class="dropdown-item" href="produk_heavy_meals.php" onclick="updateDropdown('Heavy Meals')">Heavy Meals</a></li>
                    <li><a class="dropdown-item" href="produk_light_bites.php" onclick="updateDropdown('Light Bites')">Light Bites</a></li>
                </ul>
            </div>
            <p></p>
            <input type="text" id="search" placeholder="Cari produk..." class="form-control">
        </div>

        <script>
            function updateDropdown(selectedCategory) {
                document.getElementById('productDropdown').textContent = selectedCategory;
            }

        </script>
            <div class="row">
                <?php 
                // Cek apakah ada produk dalam hasil query
                if ($result->num_rows > 0) { 
                    // Loop untuk menampilkan setiap produk dalam bentuk kartu
                    while ($row = $result->fetch_assoc()) {
                        // Format nama file gambar berdasarkan ID dan nama produk
                        $nama_file_gambar = $row['id_product'] . "_" . strtolower(str_replace(' ', '_', $row['name'])) . ".webp";
                        // Tentukan path gambar produk
                        $path_gambar = "../gambar_produk/" . $nama_file_gambar;
                        // Cek apakah file gambar ada, jika tidak tampilkan placeholder
                        $gambar = file_exists($path_gambar) ? $path_gambar : "https://via.placeholder.com/300x200?text=No+Image";
                ?>

                <div class="col-md-4 mb-4 produk-card"> <!-- Tambahkan class 'produk-card' -->
                    <div class="card">
                        <!-- Menampilkan gambar produk -->
                        <img src="<?= $gambar; ?>" class="card-img-top produk-img" alt="<?= htmlspecialchars($row['name']); ?>">
                        <div class="card-body text-center">
                            <!-- Menampilkan nama produk -->
                            <h5 class="card-title product-name"><?= htmlspecialchars($row['name']); ?></h5>
                            <!-- Menampilkan harga produk dengan format angka -->
                            <p class="card-text">Price: <strong>$ <?= number_format($row['price'], 0, ',', '.'); ?></strong></p>
                            <!-- Tombol untuk menambahkan produk ke keranjang -->
                            <button class="btn btn-success btn-tambah-keranjang" data-id="<?= $row['id_product']; ?>">Add to Cart</button>
                        </div>
                    </div>
                </div>

                <?php } 
                } else { ?>
                    <!-- Jika tidak ada produk yang ditemukan -->
                    <p class="text-center">No products available.</p>
                <?php } ?>
            </div>
        </div>

        <!-- Modal untuk memilih jumlah produk yang ingin ditambahkan ke keranjang -->
        <div class="modal fade" id="modalTambahKeranjang" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- Judul modal -->
                        <h5 class="modal-title">Enter Product Quantity</h5>
                        <!-- Tombol untuk menutup modal -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Input untuk menentukan jumlah produk yang akan dibeli -->
                        <input type="number" id="inputJumlah" class="form-control" value="1" min="1">
                    </div>
                    <div class="modal-footer">
                        <!-- Tombol batal untuk menutup modal -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <!-- Tombol konfirmasi untuk menambahkan produk ke keranjang -->
                        <button type="button" class="btn btn-primary" id="btnKonfirmasi">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="produk_desserts.js"></script>
</body>
</html>