<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../akun/login.php");
    exit;
}
include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_POST['id_user'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Validasi input
    if (empty($id_user) || empty($nama) || empty($email) || empty($role)) {
        echo json_encode(["status" => "error", "message" => "Semua kolom kecuali password harus diisi!"]);
        exit;
    }

    // Update data pengguna
    if (!empty($password)) {
        // Jika password diisi, update juga password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET nama=?, email=?, role=?, password=? WHERE id_user=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nama, $email, $role, $hashed_password, $id_user);
    } else {
        // Jika password kosong, update tanpa mengubah password
        $sql = "UPDATE users SET nama=?, email=?, role=? WHERE id_user=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nama, $email, $role, $id_user);
    }

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Data pengguna berhasil diperbarui!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal memperbarui data pengguna!"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan!"]);
}
