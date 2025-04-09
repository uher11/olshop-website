<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../akun/login.php");
    exit;
}
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);

        // Ambil nama produk sebelum dihapus
        $stmt = $conn->prepare("SELECT name FROM product WHERE id_product = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($product_name);
        $stmt->fetch();
        $stmt->close();

        if ($product_name) {
            // Format nama file gambar sesuai aturan
            $file_name = $id . '_' . strtolower(str_replace(' ', '_', $product_name)) . '.webp';
            $file_path = realpath(__DIR__ . "/../../produk/gambar_produk/" . $file_name); // Gunakan realpath

            // Hapus data dari database
            $stmt = $conn->prepare("DELETE FROM product WHERE id_product = ?");
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                // Hapus gambar jika ada
                if ($file_path && file_exists($file_path)) {
                    if (unlink($file_path)) {
                        echo json_encode(["status" => "success", "message" => "Produk dan gambar berhasil dihapus"]);
                    } else {
                        echo json_encode(["status" => "warning", "message" => "Produk dihapus, tetapi gambar gagal dihapus"]);
                    }
                } else {
                    echo json_encode(["status" => "warning", "message" => "Produk dihapus, tetapi gambar tidak ditemukan"]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal menghapus produk"]);
            }

            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Produk tidak ditemukan"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ID tidak ditemukan"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan"]);
}

$conn->close();
?>
