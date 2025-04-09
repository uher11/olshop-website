document.addEventListener("DOMContentLoaded", function () {
    let idProdukTerpilih = null;
    let modalElement = document.getElementById("modalTambahKeranjang");
    let modal = new bootstrap.Modal(modalElement);
    let inputJumlah = document.getElementById("inputJumlah");
    let jumlahKeranjangElement = document.getElementById("jumlahKeranjang");
    let btnKonfirmasi = document.getElementById("btnKonfirmasi");

    document.querySelectorAll(".btn-tambah-keranjang").forEach(button => {
        button.addEventListener("click", function () {
            idProdukTerpilih = this.dataset.id;
            inputJumlah.value = 1;
            modal.show();

            setTimeout(() => {
                inputJumlah.focus();
                inputJumlah.select();
            }, 500);
        });
    });

    modalElement.addEventListener("shown.bs.modal", function () {
        inputJumlah.focus();
        inputJumlah.select();
    });

    async function tambahKeKeranjang() {
        let jumlah = parseInt(inputJumlah.value);
        if (jumlah > 0) {
            btnKonfirmasi.disabled = true; // Mencegah klik berulang

            try {
                let response = await fetch("../keranjang/tambah_keranjang.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `id_produk=${idProdukTerpilih}&jumlah=${jumlah}&ajax=1`
                });

                let data = await response.json();
                if (data.status === "success") {
                    alert("Produk berhasil ditambahkan ke keranjang!");
                    modal.hide();
                    updateJumlahKeranjang();
                } else {
                    alert("Gagal menambahkan produk ke keranjang!");
                }
            } catch (error) {
                console.error("Terjadi kesalahan:", error);
            } finally {
                btnKonfirmasi.disabled = false;
            }
        } else {
            alert("Jumlah produk harus lebih dari 0!");
        }
    }

    btnKonfirmasi.addEventListener("click", tambahKeKeranjang);

    inputJumlah.addEventListener("keypress", function (event) {
        if (event.key === "Enter") {
            event.preventDefault();
            tambahKeKeranjang();
        }
    });

    async function updateJumlahKeranjang() {
        try {
            let response = await fetch("../keranjang/get_jumlah_keranjang.php");
            let data = await response.json();
            jumlahKeranjangElement.innerText = data.jumlah;
        } catch (error) {
            console.error("Error updating cart count:", error);
        }
    }

    async function updateGambarProduk() {
        document.querySelectorAll(".produk-gambar").forEach(async (img) => {
            let idProduk = img.dataset.id;
    
            try {
                let response = await fetch(`produk.php?id_produk=${idProduk}&t=${Date.now()}`); // Pastikan URL selalu unik
                let data = await response.json();
    
                if (data.gambar) {
                    let newImgUrl = `produk/gambar_produk/${idProduk}_${data.gambar}.webp?t=${Date.now()}`;
    
                    // **Trik: Buat elemen gambar baru, lalu ganti gambar lama**
                    let newImg = document.createElement("img");
                    newImg.src = newImgUrl;
                    newImg.width = img.width;
                    newImg.className = img.className;
                    newImg.dataset.id = idProduk;
                    newImg.alt = img.alt;
    
                    newImg.onload = () => {
                        console.log(`Gambar diperbarui: ${newImgUrl}`);
                        img.replaceWith(newImg); // Ganti gambar lama dengan yang baru
                    };
    
                    newImg.onerror = () => console.error(`Gagal memuat gambar: ${newImgUrl}`);
                }
            } catch (error) {
                console.error("Error updating image:", error);
            }
        });
    }
    
    // Jalankan update gambar setiap 5 detik
    setInterval(updateGambarProduk, 5000);
    
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
