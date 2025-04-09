<?php
// 1. Koneksi ke database
$host = "localhost"; 
$user = "root"; 
$password = ""; 
$database = "olshop"; 

$conn = new mysqli($host, $user, $password, $database);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 2. Cek apakah form dikirimkan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_produk = $_POST["nama_produk"];
    $deskripsi = $_POST["deskripsi"];
    $harga = $_POST["harga"];
    $kategori = $_POST["kategori"];
    $stok = $_POST["stok"];

    // 3. Simpan produk ke database dulu agar dapat ID produk baru
    $sql = "INSERT INTO product (name, description, price, category, stock) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $nama_produk, $deskripsi, $harga, $kategori, $stok);

    if ($stmt->execute()) {
        // Ambil ID produk yang baru dibuat
        $id_product = $stmt->insert_id;
        $stmt->close();

        // 4. Proses unggah gambar
        $uploadDir = "../../produk/gambar_produk/";

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES["gambar_produk"]["name"]);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Nama file baru dengan ID produk di depannya
        $newFileName = $id_product . "_" . strtolower(str_replace(" ", "_", pathinfo($fileName, PATHINFO_FILENAME))) . "." . $fileType;
        $targetFilePath = $uploadDir . $newFileName;

        // 5. Validasi format file
        $allowedTypes = ["jpg", "jpeg", "png", "gif", "webp"];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["gambar_produk"]["tmp_name"], $targetFilePath)) {
                echo "Gambar berhasil diunggah dengan nama: " . $newFileName;
                header("Location: manage_products.php");
                exit();
            } else {
                echo "Gagal mengunggah gambar.";
            }
        } else {
            echo "Format file tidak diizinkan.";
        }
    } else {
        echo "Error saat menyimpan produk: " . $conn->error;
    }
}

// Tutup koneksi database
$conn->close();
?>
