document.addEventListener("DOMContentLoaded", function () {
    // **🔎 Fitur Pencarian Pengguna**
    const searchInput = document.getElementById("search");
    const tableRows = document.querySelectorAll("table tbody tr");

    searchInput?.addEventListener("keyup", function () {
        const searchText = searchInput.value.toLowerCase();
        tableRows.forEach(row => {
            const userName = row.cells[1].textContent.toLowerCase();
            const userEmail = row.cells[2].textContent.toLowerCase();
            row.style.display = (userName.includes(searchText) || userEmail.includes(searchText)) ? "" : "none";
        });
    });

    // **🟢 Modal Tambah Pengguna**
    const addUserBtn = document.getElementById("addUser");
    const modal = document.getElementById("addUserModal");
    const closeBtn = document.querySelector(".close");
    const cancelBtn = document.getElementById("cancelBtn");

    addUserBtn?.addEventListener("click", function () {
        modal.style.display = "flex";
    });

    closeBtn?.addEventListener("click", function () {
        modal.style.display = "none";
    });

    cancelBtn?.addEventListener("click", function () {
        modal.style.display = "none";
    });

    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // **📝 Submit Form Tambah Pengguna (AJAX)**
    document.getElementById("addUserForm")?.addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("proses_tambah_user.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === "success") location.reload();
        })
        .catch(error => console.error("Error:", error));
    });

    // **🗑️ Hapus Data Pengguna (Delegasi Event)**
    document.addEventListener("click", function (event) {
        if (event.target.closest(".delete")) {
            let button = event.target.closest(".delete");
            let userId = button.dataset.id;
            if (confirm("Apakah Anda yakin ingin menghapus pengguna ini?")) {
                fetch("proses_hapus_user.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id_user=" + encodeURIComponent(userId)
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    if (data.status === "success") location.reload();
                })
                .catch(error => console.error("Error:", error));
            }
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        const editButtons = document.querySelectorAll(".edit");
        const editUserModal = new bootstrap.Modal(document.getElementById("editUserModal"));
        const editUserForm = document.getElementById("editUserForm");
    
        editButtons.forEach(button => {
            button.addEventListener("click", function () {
                document.getElementById("edit_id").value = this.dataset.id;
                document.getElementById("edit_nama").value = this.dataset.nama;
                document.getElementById("edit_email").value = this.dataset.email;
                document.getElementById("edit_role").value = this.dataset.role;
                
                editUserModal.show();
            });
        });
    
        editUserForm.addEventListener("submit", function (e) {
            e.preventDefault();
    
            const formData = new FormData(editUserForm);
    
            fetch("proses_edit_user.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert(data.message);
                    location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => console.error("Error:", error));
        });
    });

$(document).ready(function () {
    $(".edit").click(function () {
        // Ambil data dari atribut data-*
        var id = $(this).data("id");
        var nama = $(this).data("nama");
        var email = $(this).data("email");
        var role = $(this).data("role");

        // Isi data ke dalam form modal
        $("#edit_id").val(id);
        $("#edit_nama").val(nama);
        $("#edit_email").val(email);
        $("#edit_role").val(role);
        $("#edit_password").val(""); // Kosongkan password untuk keamanan

        // Tampilkan modal
        $("#editUserModal").modal("show");
    });

    // Tangani form submit dengan AJAX
    $("#editUserForm").submit(function (e) {
        e.preventDefault();

        $.ajax({
            url: "proses_edit_user.php",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    alert(response.message);
                    location.reload(); // Refresh halaman setelah berhasil edit
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert("Terjadi kesalahan saat mengirim data.");
            }
        });
    });
});
});
