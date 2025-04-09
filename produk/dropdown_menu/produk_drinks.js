document.addEventListener("DOMContentLoaded", function () {
    let idProdukTerpilih = null; // Variabel untuk menyimpan ID produk yang dipilih
    let modalElement = document.getElementById("modalTambahKeranjang"); // Elemen modal
    let modal = new bootstrap.Modal(modalElement); // Inisialisasi modal Bootstrap
    let inputJumlah = document.getElementById("inputJumlah"); // Input jumlah produk

    // Event listener untuk tombol "Add to Cart"
    document.querySelectorAll(".btn-tambah-keranjang").forEach(button => {
        button.addEventListener("click", function () {
            idProdukTerpilih = this.dataset.id; // Simpan ID produk dari atribut data-id
            inputJumlah.value = 1; // Reset jumlah produk ke 1 setiap kali modal dibuka
            modal.show(); // Tampilkan modal

            // Tunggu modal selesai loading sebelum mengaktifkan input
            setTimeout(() => {
                inputJumlah.focus();
                inputJumlah.select();
            }, 500); // Delay 500ms untuk memastikan modal sudah tampil
        });
    });

    // Pastikan input tetap fokus saat modal sudah terbuka
    modalElement.addEventListener("shown.bs.modal", function () {
        inputJumlah.focus();
        inputJumlah.select();
    });

    // Fungsi untuk menambahkan produk ke keranjang
    function tambahKeKeranjang() {
        let jumlah = inputJumlah.value; // Ambil jumlah produk dari input
        if (jumlah > 0) {
            // Kirim permintaan ke server untuk menambahkan produk ke keranjang
            fetch("../../keranjang/tambah_keranjang.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id_produk=" + idProdukTerpilih + "&jumlah=" + jumlah
            })
            .then(response => response.json()) // Parse response sebagai JSON
            .then(data => {
                if (data.status === "success") {
                    alert("Product successfully added to cart!"); // Notifikasi sukses
                    modal.hide(); // Tutup modal setelah berhasil menambahkan
                } else {
                    alert("Gagal menambahkan produk ke keranjang!"); // Notifikasi gagal
                }
            });
        } else {
            alert("Jumlah produk harus lebih dari 0!"); // Validasi input jumlah
        }
    }

    // Event listener untuk tombol "Add to Cart" dalam modal
    document.getElementById("btnKonfirmasi").addEventListener("click", tambahKeKeranjang);

    // Event listener agar pengguna bisa menekan ENTER untuk langsung menambahkan produk ke keranjang
    inputJumlah.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Mencegah form submit default
            tambahKeKeranjang(); // Panggil fungsi tambah ke keranjang
        }
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search");

    if (!searchInput) {
        console.error("Elemen input pencarian tidak ditemukan.");
        return;
    }

    const productCards = document.querySelectorAll(".produk-card");

    searchInput.addEventListener("keyup", function () {
        const searchText = searchInput.value.trim().toLowerCase();

        productCards.forEach(card => {
            const productNameElement = card.querySelector(".product-name");
            if (productNameElement) {
                const productName = productNameElement.textContent.trim().toLowerCase();
                
                if (productName.includes(searchText)) {
                    card.style.display = "block"; // ✅ Tampilkan jika cocok
                } else {
                    card.style.display = "none"; // ❌ Sembunyikan jika tidak cocok
                }
            }
        });
    });
});