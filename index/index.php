<?php
session_start();

// Jika pengguna belum login, arahkan ke halaman login
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Cemillisious</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <!-- Navbar / Menu Navigasi -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="../index/index.php">🛒 Cemillicious</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active btn btn-primary text-white px-3" href="#">🏠 Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../produk/produk.php">📦 Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="../tentang/tentang.php">ℹ️ About Us</a></li>
                    <li class="nav-item"><a class="nav-link" href="../keranjang/keranjang.php">🛍️ Cart</a></li>

                    <?php if (isset($_SESSION['role'])): ?> 
                        <li class="nav-item">
                            <a class="nav-link btn btn-danger text-white px-3 ms-2" href="../admin/akun/logout.php">🚪 Logout</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Bagian Pembuka) -->
    <div class="hero text-center">
        <h1>Welcome to Our Online Store!</h1>
        <p>Enjoy the best shopping experience with high-quality products and the best prices.</p>
        <a href="../produk/produk.php" class="btn btn-primary">Explore Products</a>
    </div>

    <!-- Container untuk menampilkan produk unggulan -->
    <div class="container mt-5">
        <h2 class="text-center">Featured Products</h2>
        
        <!-- Carousel Bootstrap untuk Menampilkan Produk -->
        <div id="carouselProduk" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                <?php
                // Menghubungkan ke database
                include '../koneksi.php';
                
                // Query untuk mengambil produk unggulan berdasarkan id_product tertentu
                $query = "SELECT * FROM product WHERE id_product IN (1,2,6,7,11,12,16,17)";
                $result = mysqli_query($conn, $query);
                
                // Variabel untuk menentukan item pertama sebagai 'active' (Bootstrap memerlukan ini)
                $active = "active";

                // Looping untuk menampilkan setiap produk dalam carousel
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <div class="carousel-item <?= $active ?> text-center">
                        <!-- Gambar Produk dengan nama file sesuai format: id_name.webp -->
                        <img src="../produk/gambar_produk/<?= $row['id_product'] . "_" . strtolower(str_replace(' ', '_', $row['name'])) ?>.webp" 
                        class="d-block w-50 mx-auto" 
                        alt="<?= $row['name'] ?>">
                        
                        <!-- Nama Produk -->
                        <h5><?= $row['name'] ?></h5>

                        <!-- Deskripsi Produk -->
                        <p>
                        <?= $row['description'] ?>
                        </p>

                        <!-- Tombol untuk membeli produk -->
                        <a href="../produk/produk.php" class="btn btn-success">Buy Now</a>
                    </div>
                <?php
                    // Setelah iterasi pertama, ubah $active menjadi string kosong agar tidak ada lagi item 'active'
                    $active = ""; 
                }
                ?>
            </div>

            <!-- Tombol untuk navigasi carousel ke item sebelumnya -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselProduk" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>

            <!-- Tombol untuk navigasi carousel ke item berikutnya -->
            <button class="carousel-control-next" type="button" data-bs-target="#carouselProduk" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-5">
        <p class="mb-0">&copy; 2025 Online Store - All Rights Reserved</p>
    </footer>

    <!-- Script Bootstrap untuk fungsi interaktif seperti carousel -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>