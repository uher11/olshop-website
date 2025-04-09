<?php
// 1. Koneksi ke database
$host = "localhost";
$user = "root";
$password = "";
$database = "olshop";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 2. Cek apakah form dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_product = $conn->real_escape_string($_POST["id_product"]);
    $nama_produk = $conn->real_escape_string($_POST["nama_produk"]);
    $deskripsi = $conn->real_escape_string($_POST["deskripsi"]);
    $harga = $conn->real_escape_string($_POST["harga"]);
    $kategori = $conn->real_escape_string($_POST["kategori"]);
    $stok = $conn->real_escape_string($_POST["stok"]);

    // 3. Proses unggah gambar (jika ada)
    $uploadDir = "D:/Penyimpanan Utama/Program/Xampp/htdocs/toko_online/update_16_mar/produk/gambar_produk/";
    $nama_produk_lower = strtolower(str_replace(" ", "_", $nama_produk)); // Nama produk jadi lowercase dan spasi diganti "_"
    $newImageName = "{$id_product}_{$nama_produk_lower}.webp";
    $targetFilePath = $uploadDir . $newImageName;

    if (!empty($_FILES["gambar_produk"]["name"])) {
        $fileType = strtolower(pathinfo($_FILES["gambar_produk"]["name"], PATHINFO_EXTENSION));

        // Validasi format file
        $allowedTypes = ["jpg", "jpeg", "png", "gif", "webp"];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["gambar_produk"]["tmp_name"], $targetFilePath)) {
                // Gambar berhasil diunggah, tidak perlu menyimpan di database karena formatnya tetap
            } else {
                die("Gagal mengunggah gambar.");
            }
        } else {
            die("Format file tidak diizinkan.");
        }
    }

    // 4. Update data di database (tanpa menyimpan nama gambar)
    $sql = "UPDATE product SET 
                name = ?, 
                description = ?, 
                price = ?, 
                category = ?, 
                stock = ? 
            WHERE id_product = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $nama_produk, $deskripsi, $harga, $kategori, $stok, $id_product);

    if ($stmt->execute()) {
        header("Location: manage_products.php");
        exit();
    } else {
        die("Error saat memperbarui data: " . $stmt->error);
    }
}

// Tutup koneksi database
$conn->close();
?>
