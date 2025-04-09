<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../akun/login.php");
    exit;
}
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    if (!empty($order_id) && !empty($status)) {
        $stmt = $conn->prepare("UPDATE transaksi SET status=? WHERE id_transaksi=?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Status updated successfully!";
        } else {
            echo "Error updating status!";
        }

        $stmt->close();
    } else {
        echo "Invalid input!";
    }
}
?>
