<?php
session_start();
ob_start(); // Hindari output sebelum header()
require '../config.php'; // Pastikan koneksi database benar

// Jika user sudah login, cegah mereka kembali ke halaman login
if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
    if (basename($_SERVER['PHP_SELF']) !== 'index.php') {
        header("Location: ../../index/index.php"); // Cegah redirect loop
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id_user, nama, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            // Redirect berdasarkan role
            if ($_SESSION['role'] == 'Admin') {
                header("Location: ../index/index.php");
                exit();
            } else {
                header("Location: ../../index/index.php");
                exit();
            }
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cemillisious Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            position: relative;
            background-color: #000;
        }

        .slideshow {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            overflow: hidden;
        }

        .slideshow img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .slideshow img.active {
            opacity: 1;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.3);
            padding: 50px;
            border-radius: 20px;
            backdrop-filter: blur(15px);
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
            width: 400px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 15px;
            margin: 12px 0;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            text-align: center;
            outline: none;
        }

        button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 30px;
            background: linear-gradient(135deg, #1E90FF, #00BFFF);
            color: white;
            font-size: 18px;
            cursor: pointer;
            margin-top: 20px;
            text-transform: uppercase;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .judul {
            background: rgba(255, 255, 255, 0.4);
            padding: 20px;
            border-radius: 15px;
            backdrop-filter: blur(20px);
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            display: inline-block;
            color: #333;
            font-size: 22px;
        }

        @media (max-width: 500px) {
            .login-container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="slideshow">
        <img src="../../produk/gambar_produk/1_heavenly_choco_lava.webp" class="slide active">
        <img src="../../produk/gambar_produk/5_cookies_&_cream_delight.webp" class="slide">
        <img src="../../produk/gambar_produk/11_the_mighty_beef_bowl.webp" class="slide">
        <img src="../../produk/gambar_produk/16_golden_crispy_fries.webp" class="slide">
    </div>

    <div class="login-container">
        <h2 class="judul">Cemillicious Login</h2>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
        <form method="post" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>

    <script>
        let slides = document.querySelectorAll('.slide');
        let currentSlide = 0;

        function showNextSlide() {
            slides.forEach((slide, index) => {
                slide.classList.remove('active');
            });
            slides[currentSlide].classList.add('active');
            currentSlide = (currentSlide + 1) % slides.length;
        }
        setInterval(showNextSlide, 3000);
    </script>
</body>
</html>