<?php
session_start();

// Jika pengguna belum login, arahkan ke halaman login
if (!isset($_SESSION['role'])) {
    header("Location: ../admin/akun/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Cemillicious</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="tentang.css">
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
                    <li class="nav-item"><a class="nav-link" href="../index/index.php">🏠 Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="../produk/produk.php">📦 Products</a></li>
                    <li class="nav-item"><a class="nav-link active btn btn-primary text-white px-3" href="#">ℹ️ About Us</a></li>
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

    <div class="container mt-5 text-center">
        <h2 class="mb-3">About Cemillicious</h2>
    </div>

    <div class="container description mt-4">
        <p class="lead text-center">
            Cemillicious is your go-to destination for delicious snacks and drinks! 
            We offer a variety of foods, from tempting desserts and refreshing beverages to hearty meals and light snacks perfect for any occasion.
        </p>
        <h4 class="text-center">Why Choose Cemillicious?</h4>
        <ul class="list-unstyled text-center">
            <li>✔ <b>Wide Variety</b> – From sweet desserts and cold drinks to satisfying main dishes, we have it all!</li>
            <li>✔ <b>High-Quality Ingredients</b> – We use only the best ingredients to ensure top-notch flavor and quality.</li>
            <li>✔ <b>Affordable Prices</b> – Enjoy delicious food without breaking the bank.</li>
            <li>✔ <b>Easy Ordering</b> – Our online system lets you choose your favorite items and check out effortlessly.</li>
            <li>✔ <b>Fast Delivery</b> – Your food arrives fresh and ready to enjoy.</li>
        </ul>
        <p class="text-center">
            At Cemillicious, every bite is happiness! Enjoy the best snacks and meals only here! 🍪☕🍜
        </p>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        &copy; 2025 Cemillicious - All Rights Reserved
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>