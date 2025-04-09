$(document).ready(function () {
    $("#pay-button").click(function () {
        let nama = $("#nama").val();
        let alamat = $("#alamat").val();
        let telepon = $("#telepon").val();

        if (!nama || !alamat || !telepon) {
            alert("Please fill in all fields!");
            return;
        }

        $.ajax({
            url: "checkout.php",
            type: "POST",
            data: {
                nama: nama,
                alamat: alamat,
                telepon: telepon
            },
            success: function (data) {
                var response = JSON.parse(data);
                var token = response.token;
                var id_transaksi = response.id_transaksi;

                console.log("Token received:", token);

                snap.pay(token, {
                    onSuccess: function (result) {
                        console.log("Payment successful:", result);
                        alert("Payment successful!");
                        window.location.href = "payment_status.php?id_transaksi=" + id_transaksi;
                    },
                    onPending: function (result) {
                        console.log("Payment pending:", result);
                        alert("Waiting for payment...");
                        window.location.href = "payment_status.php?id_transaksi=" + id_transaksi;
                    },
                    onError: function (result) {
                        console.log("Payment failed:", result);
                        alert("Payment failed!");
                    },
                    onClose: function () {
                        console.log("Payment window closed (onClose triggered)");
                    
                        setTimeout(function () {
                            let url = "payment_status.php";
                            if (id_transaksi) {
                                url += "?id_transaksi=" + id_transaksi;
                            }
                            
                            // Membuka pop-up baru dengan ukuran yang diatur
                            let popup = window.open(url, "PaymentStatus", "width=600,height=400,scrollbars=yes");
                    
                            // Jika pop-up diblokir, tampilkan pesan ke pengguna
                            if (!popup) {
                                alert("Popup blocked! Please allow pop-ups for this site.");
                            }
                        }, 500);
                    }                    
                });
                
            },
            error: function (xhr, status, error) {
                console.log("Error:", error);
            }
        });
    });
});
