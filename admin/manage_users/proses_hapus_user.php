<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../akun/login.php");
    exit;
}
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = isset($_POST['id_user']) ? intval($_POST['id_user']) : 0;

    if ($id_user <= 0) {
        echo json_encode(["status" => "error", "message" => "ID pengguna tidak valid!"]);
        exit;
    }

    // Cek apakah ID pengguna ada dalam database
    $checkUser = $conn->prepare("SELECT COUNT(*) FROM users WHERE id_user = ?");
    $checkUser->bind_param("i", $id_user);
    $checkUser->execute();
    $checkUser->bind_result($count);
    $checkUser->fetch();
    $checkUser->close();

    if ($count === 0) {
        echo json_encode(["status" => "error", "message" => "Pengguna tidak ditemukan!"]);
        exit;
    }

    // Hapus pengguna berdasarkan ID
    $stmt = $conn->prepare("DELETE FROM users WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Pengguna berhasil dihapus!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menghapus pengguna!"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan!"]);
}
?>
