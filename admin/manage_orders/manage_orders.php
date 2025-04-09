<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../akun/login.php");
    exit;
}
include '../config.php';

// Mencegah cache browser
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// Inisialisasi variabel agar tidak undefined
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_status = isset($_GET['status']) ? trim($_GET['status']) : '';

// Update status order
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'] ?? '';
    $status = $_POST['status'] ?? '';
    
    if (!empty($order_id) && !empty($status)) {
        $stmt = $conn->prepare("UPDATE transaksi SET status=? WHERE id_transaksi=?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: manage_orders.php");
    exit;
}

// Delete order
if (isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'] ?? '';
    
    if (!empty($order_id)) {
        $stmt = $conn->prepare("DELETE FROM transaksi WHERE id_transaksi=?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: manage_orders.php");
    exit;
}

// Get orders
$orders = template_fetch_orders();

function template_fetch_orders() {
    global $conn, $filter_status, $search_query;

    // Tambahkan wildcard % hanya jika ada inputan
    $filter_status = $filter_status !== '' ? "%$filter_status%" : "%";
    $search_query = $search_query !== '' ? "%$search_query%" : "%";

    $sql = "SELECT id_transaksi, nama_pelanggan AS pelanggan, tanggal_transaksi AS tanggal, total_harga, status 
            FROM transaksi 
            WHERE (status LIKE ?) 
            AND (id_transaksi LIKE ? OR nama_pelanggan LIKE ?) 
            ORDER BY tanggal_transaksi DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $filter_status, $search_query, $search_query);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();

    return $result;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../index/index.css">
    <style>
        #search {
            padding: 8px 12px;
            border: 1px solid #ccc;
            border-radius: 8px; /* Membuat pojok membulat */
            font-size: 14px;
            outline: none;
            transition: 0.3s;
        }

        #search:focus {
            border-color: #007bff; /* Warna biru saat input aktif */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

    </style>
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
                            <a class="nav-link active" href="manage_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
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

        <div class="container mt-5">
            <h1 class="text-center mb-4">Manage Orders</h1>
            
            <form method="GET" class="row mb-4">
                <div class="col-md-4">
                    <input type="text" id="search" placeholder="Cari pesanan..." />
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="Pending" <?= ($filter_status == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="Processed" <?= ($filter_status == 'Processed') ? 'selected' : ''; ?>>Processed</option>
                        <option value="Shipped" <?= ($filter_status == 'Shipped') ? 'selected' : ''; ?>>Shipped</option>
                        <option value="Completed" <?= ($filter_status == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $orders->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id_transaksi']; ?></td>
                            <td><?php echo $row['pelanggan']; ?></td>
                            <td><?php echo $row['tanggal']; ?></td>
                            <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                            <td>
                                <select class="form-select update-status" data-id="<?php echo $row['id_transaksi']; ?>">
                                    <option value="Pending" <?php if ($row['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                    <option value="Processed" <?php if ($row['status'] == 'Processed') echo 'selected'; ?>>Processed</option>
                                    <option value="Shipped" <?php if ($row['status'] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                                    <option value="Completed" <?php if ($row['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                </select>
                            </td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="order_id" value="<?php echo $row['id_transaksi']; ?>">
                                    <button type="submit" name="delete_order" class="btn btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $(".update-status").change(function () {
                let order_id = $(this).data("id");
                let status = $(this).val();

                $.post("update_status.php", { order_id: order_id, status: status }, function (response) {
                    alert(response); // Menampilkan pesan sukses atau error
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("search");
            const tableRows = document.querySelectorAll("table tbody tr");

            searchInput.addEventListener("keyup", function () {
                const searchText = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const orderId = row.cells[0].textContent.toLowerCase(); // Ambil ID pesanan
                    const customerName = row.cells[1].textContent.toLowerCase(); // Ambil nama pelanggan
                    const status = row.cells[4].textContent.toLowerCase(); // Ambil status

                    if (orderId.includes(searchText) || customerName.includes(searchText) || status.includes(searchText)) {
                        row.style.display = ""; // Tampilkan baris jika cocok
                    } else {
                        row.style.display = "none"; // Sembunyikan baris jika tidak cocok
                    }
                });
            });
        });
    </script>
</body>
</html>
