<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../akun/login.php");
    exit;
}
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role'] ?? 'User');

    // Validasi input tidak boleh kosong
    if (empty($nama) || empty($email) || empty($password) || empty($role)) {
        echo json_encode(["status" => "error", "message" => "Semua kolom wajib diisi!"]);
        exit;
    }

    // Pastikan email dalam format yang benar
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Format email tidak valid!"]);
        exit;
    }

    // Cek apakah email sudah digunakan
    $checkEmail = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->bind_result($count);
    $checkEmail->fetch();
    $checkEmail->close();

    if ($count > 0) {
        echo json_encode(["status" => "error", "message" => "Email sudah terdaftar!"]);
        exit;
    }

    // Enkripsi password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Simpan data ke database
    $stmt = $conn->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $email, $hashedPassword, $role);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Pengguna berhasil ditambahkan!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Gagal menambahkan pengguna!"]);
    }

    $stmt->close();
    $conn->close(); // Tutup koneksi database
} else {
    echo json_encode(["status" => "error", "message" => "Metode tidak diizinkan!"]);
}
?>
