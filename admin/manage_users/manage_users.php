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

// Inisialisasi variabel
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter_role = isset($_GET['role']) ? trim($_GET['role']) : '';

// Tambahkan wildcard % hanya jika ada inputan
$filter_role = $filter_role !== '' ? "%$filter_role%" : "%";
$search_query = $search_query !== '' ? "%$search_query%" : "%";

// Ambil data pengguna
$sql = "SELECT id_user, nama, email, role FROM users 
        WHERE (role LIKE ?) AND (nama LIKE ? OR email LIKE ?) 
        ORDER BY nama ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $filter_role, $search_query, $search_query);
$stmt->execute();
$users = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        html, body {
            height: 100%;
        }
        .wrapper {
            min-height: 100%;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
        }
        footer {
            margin-top: auto;
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

        <div class="container">
            <h2 class="text-center">Manajemen Pengguna</h2>

            <div class="card p-3 mt-3">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="search" class="form-control" placeholder="Cari pengguna...">
                    </div>
                    <div class="col-md-3">
                        <select id="roleFilter" class="form-control">
                            <option value="">Semua Peran</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button id="addUser" class="btn btn-success"><i class="fas fa-user-plus"></i> Tambah Pengguna</button>
                    </div>
                </div>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['id_user']) ?></td>
                            <td><?= htmlspecialchars($user['nama']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($user['role']) ?></span></td>
                            <td>
                            <button class="btn btn-warning btn-sm edit" 
                                data-id="<?= htmlspecialchars($user['id_user']) ?>" 
                                data-nama="<?= htmlspecialchars($user['nama']) ?>" 
                                data-email="<?= htmlspecialchars($user['email']) ?>" 
                                data-role="<?= htmlspecialchars($user['role']) ?>">
                                <i class="fas fa-edit"></i>
                            </button>

                                <button class="btn btn-danger btn-sm delete" data-id="<?= $user['id_user']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Modal Edit Pengguna -->
                <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editUserModalLabel">Edit Pengguna</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editUserForm">
                                    <input type="hidden" id="edit_id" name="id_user">

                                    <div class="mb-3">
                                        <label for="edit_nama" class="form-label">Nama:</label>
                                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_email" class="form-label">Email:</label>
                                        <input type="email" class="form-control" id="edit_email" name="email" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_role" class="form-label">Peran:</label>
                                        <select class="form-control" id="edit_role" name="role">
                                            <option value="Admin">Admin</option>
                                            <option value="User">User</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="edit_password" class="form-label">Password Baru (Kosongkan jika tidak ingin mengubah):</label>
                                        <input type="password" class="form-control" id="edit_password" name="password">
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Popup Form Tambah Pengguna -->
                <div id="addUserModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Tambah Pengguna</h2>
                        <form id="addUserForm">
                            <label for="nama">Nama:</label>
                            <input type="text" id="nama" name="nama" required>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>

                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required>

                            <label for="role">Peran:</label>
                            <select id="role" name="role">
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>

                            <button type="submit">Simpan</button>
                            <button type="button" id="cancelBtn">Batal</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="manage_users.js"></script>
</body>
</html>
