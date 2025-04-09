<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../akun/login.php");
    exit;
}
include '../config.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index/index.php"><i class="fas fa-cogs"></i> Admin Panel</a>
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

    <div class="container mt-4">
        <h2 class="text-center">Product Management</h2>
        <div class="d-flex justify-content-between mb-3">
            <input type="text" id="search" class="form-control w-25" placeholder="Search product...">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="fas fa-plus"></i> Add Product</button>
        </div>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
                <?php
                $query = "SELECT * FROM product ORDER BY id_product";
                $result = mysqli_query($conn, $query);
                while ($row = mysqli_fetch_assoc($result)) {
                    // Buat nama gambar sesuai format
                    $image_name = $row['id_product'] . '_' . strtolower(str_replace(' ', '_', $row['name'])) . ".webp";
                    $image_url = "../../produk/gambar_produk/" . $image_name;

                    // Debugging untuk memastikan nilai yang dikirim
                    // echo "<script>console.log('Debug: $image_url');</script>";

                    // Cetak tabel
                    echo "<tr>
                            <td>{$row['id_product']}</td>
                            <td>{$row['name']}</td>
                            <td>$ " . number_format($row['price'], 0, ',', '.') . "</td>
                            <td>{$row['stock']}</td>
                            <td>
                                <button class='btn btn-warning btn-sm edit-btn'
                                data-bs-toggle='modal' 
                                data-bs-target='#editProductModal' 
                                data-id='" . htmlspecialchars($row['id_product'], ENT_QUOTES) . "' 
                                data-name='" . htmlspecialchars($row['name'], ENT_QUOTES) . "' 
                                data-desc='" . htmlspecialchars($row['description'], ENT_QUOTES) . "' 
                                data-price='" . htmlspecialchars($row['price'], ENT_QUOTES) . "' 
                                data-category='" . htmlspecialchars($row['category'], ENT_QUOTES) . "' 
                                data-stock='" . htmlspecialchars($row['stock'], ENT_QUOTES) . "' 
                                data-image='" . htmlspecialchars($image_url, ENT_QUOTES) . "'>
                                <i class='fas fa-edit'></i> Edit</button>
                                <button class='btn btn-danger btn-sm delete-btn' 
                                data-id='" . htmlspecialchars($row['id_product'], ENT_QUOTES) . "'>
                                <i class='fas fa-trash'></i> Delete</button>
                            </td>
                        </tr>";
                }
                ?>
        </table>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Edit Product -->
                    <form id="editProductForm" action="edit_product.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_product" id="edit_id_product">
                        
                        <div class="mb-3">
                            <label>Product Name</label>
                            <input type="text" class="form-control" name="nama_produk" id="edit_nama_produk" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea class="form-control" name="deskripsi" id="edit_deskripsi" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label>Price</label>
                            <input type="number" class="form-control" name="harga" id="edit_harga" required>
                        </div>
                        
                        <div class="mb-3">
                            <label>Category</label>
                            <select class="form-control" name="kategori" id="edit_kategori" required>
                                <option value="desserts">Desserts</option>
                                <option value="drinks">Drinks</option>
                                <option value="heavy_meals">Heavy Meals</option>
                                <option value="light_bites">Light Bites</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label>Stock</label>
                            <input type="number" class="form-control" name="stok" id="edit_stok" required>
                        </div>

                        <div class="mb-3">
                            <label>Current Image</label>
                            <br>
                            <img id="edit_image_preview" 
                                src="<?php echo 'produk/gambar_produk/' . $id_product . '_' . strtolower(str_replace(' ', '_', $nama_produk)) . '.webp?' . time(); ?>" 
                                alt="Product Image" 
                                width="100" 
                                style="display: block;">
                        </div>

                        <div class="mb-3">
                            <label>Upload New Image (Optional)</label>
                            <input type="file" class="form-control" name="gambar_produk" id="edit_gambar_produk">
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" action="add_product.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>Product Name</label>
                            <input type="text" class="form-control" name="nama_produk" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea class="form-control" name="deskripsi" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Price</label>
                            <input type="number" class="form-control" name="harga" required>
                        </div>
                        <div class="mb-3">
                            <label>Category</label>
                            <select class="form-control" name="kategori" required>
                                <option value="desserts">Desserts</option>
                                <option value="drinks">Drinks</option>
                                <option value="heavy_meals">Heavy Meals</option>
                                <option value="light_bites">Light Bites</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Stock</label>
                            <input type="number" class="form-control" name="stok" required>
                        </div>
                        <div class="mb-3">
                            <label>Upload Product Image</label>
                            <input type="file" class="form-control" name="gambar_produk" id="add_gambar_produk" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="manage_products.js"></script>
</body>
</html>

