<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header("Location: ../akun/login.php");
    exit();
}
include '../config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="wrapper">
        <nav class="navbar navbar-expand-lg navbar-light bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><i class="fas fa-cogs"></i> Admin Panel</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="../manage_products/manage_products.php"><i class="fas fa-box"></i> Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../manage_orders/manage_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../manage_users/manage_users.php"><i class="fas fa-users"></i> Users</a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link admin-info"><i class="fas fa-user"></i> <?= $_SESSION['nama']; ?></span>
                        </li>
                        <li class="nav-item">
                            <a href="../akun/logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container mt-5 content">
            <h1 class="text-center mb-4">Admin Dashboard</h1>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Products</h5>
                            <p class="card-text">
                                <?php
                                $result = $conn->query("SELECT COUNT(*) AS total FROM product");
                                $row = $result->fetch_assoc();
                                echo $row['total'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card text-bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Orders</h5>
                            <p class="card-text">
                                <?php
                                $result = $conn->query("SELECT COUNT(DISTINCT id_transaksi) AS total FROM detail_transaksi");
                                if ($result) {
                                    $row = $result->fetch_assoc();
                                    echo $row['total'];
                                } else {
                                    echo "Error: " . $conn->error;
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card text-bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Users</h5>
                            <p class="card-text">
                                <?php
                                $result = $conn->query("SELECT COUNT(*) AS total FROM pelanggan");
                                $row = $result->fetch_assoc();
                                echo $row['total'];
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="../manage_products/manage_products.php" class="btn btn-primary">Manage Products</a>
                <a href="../manage_orders/manage_orders.php" class="btn btn-success">Manage Orders</a>
                <a href="../manage_users/manage_users.php" class="btn btn-warning">Manage Users</a>
            </div>
        </div>

        <footer class="bg-dark text-light text-center py-3">
            <p>&copy; <?= date('Y'); ?> Admin Panel. All rights reserved.</p>
            <p>
                <a href="#" class="text-light mx-2"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-light mx-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-light mx-2"><i class="fab fa-instagram"></i></a>
            </p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
