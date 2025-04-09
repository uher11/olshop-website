<?php
require 'vendor/autoload.php'; // Pastikan composer diinstall

\Midtrans\Config::$serverKey = 'SB-Mid-server-NnhXzDNuad98IZpX3Z5n_6CG';
\Midtrans\Config::$isProduction = false; // Ubah ke true jika sudah live
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

define('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-2W8glEypcHQFHBjy');
?>
