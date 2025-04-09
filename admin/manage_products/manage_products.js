document.addEventListener("DOMContentLoaded", function () {
    const inputFile = document.querySelector("input[name='gambar_produk']");
    const previewImage = document.createElement("img"); // Elemen gambar untuk preview
    previewImage.style.maxWidth = "100%";
    previewImage.style.marginTop = "10px";

    if (inputFile) {
        inputFile.addEventListener("change", function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // Masukkan preview ke dalam form
                inputFile.parentNode.appendChild(previewImage);
            }
        });
    }

    document.querySelectorAll(".edit-btn").forEach(button => {
        button.addEventListener("click", function() {
            let id = this.getAttribute("data-id");
            let name = this.getAttribute("data-name");
            let desc = this.getAttribute("data-desc");
            let price = this.getAttribute("data-price");
            let category = this.getAttribute("data-category");
            let stock = this.getAttribute("data-stock");
            let image = this.getAttribute("data-image");

            console.log("ID: ", id);
            console.log("Name: ", name);
            console.log("Description: ", desc);
            console.log("Price: ", price);
            console.log("Category: ", category);
            console.log("Stock: ", stock);
            console.log("Image URL: ", image);

            document.getElementById("edit_id_product").value = id;
            document.getElementById("edit_nama_produk").value = name;
            document.getElementById("edit_deskripsi").value = desc;
            document.getElementById("edit_harga").value = price;
            document.getElementById("edit_kategori").value = category;
            document.getElementById("edit_stok").value = stock;

            let imagePreview = document.getElementById("edit_image_preview");
            if (image && image !== "null") {
                let timestamp = new Date().getTime();
                imagePreview.src = image + "?" + timestamp;
                imagePreview.style.display = "block";
            } else {
                imagePreview.style.display = "none";
            }
        });
    });    
});



// **✅ Tambahkan Event Listener untuk Tombol Delete**
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".delete-btn").forEach(button => {
        button.addEventListener("click", function () {
            let id = this.getAttribute("data-id");

            if (confirm("Apakah Anda yakin ingin menghapus produk ini?")) {
                fetch("delete_product.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id=" + encodeURIComponent(id)
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") {
                        location.reload();
                    }
                })
                .catch(error => console.error("Error:", error));
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search");
    const tableRows = document.querySelectorAll("table tbody tr");

    searchInput.addEventListener("keyup", function () {
        const searchText = searchInput.value.toLowerCase();

        tableRows.forEach(row => {
            const productName = row.cells[1].textContent.toLowerCase(); // Ambil nama produk
            if (productName.includes(searchText)) {
                row.style.display = ""; // Tampilkan baris jika cocok
            } else {
                row.style.display = "none"; // Sembunyikan baris jika tidak cocok
            }
        });
    });
});
