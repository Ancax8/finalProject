
// ==========================================
// BUKA MODAL
// ==========================================

function openModal(id) {

    const modal = document.getElementById(id);

    if (modal) {
        modal.classList.add("active");
        document.body.style.overflow = "hidden";
    }

}


// ==========================================
// TUTUP MODAL
// ==========================================

function closeModal(id) {

    const modal = document.getElementById(id);

    if (modal) {
        modal.classList.remove("active");
    }

    if (!document.querySelector(".modal.active")) {
        document.body.style.overflow = "";
    }

}


// ==========================================
// GANTI KONTEN MODAL PRODUK / USER
// ==========================================

function switchModalContent(type) {

    const kontenProduk = document.getElementById("kontenAddProduk");
    const kontenUser = document.getElementById("kontenAddUser");

    if (!kontenProduk || !kontenUser) {
        return;
    }

    // Tampilkan form produk
    if (type === "produk") {

        kontenProduk.style.display = "block";
        kontenUser.style.display = "none";

    }

    // Tampilkan form user
    else if (type === "user") {

        kontenProduk.style.display = "none";
        kontenUser.style.display = "block";

    }

    // Buka modal yang sama
    openModal("modalForm");

}


// ==========================================
// ALIAS UNTUK TOMBOL BATAL ADD USER
// ==========================================

function tutupModal() {
    closeModal("modalForm");
}


// ==========================================
// KLIK AREA LUAR MODAL
// ==========================================

document.querySelectorAll(".modal").forEach(modal => {

    modal.addEventListener("click", function (event) {

        if (event.target === modal) {
            closeModal(modal.id);
        }

    });

});


// ==========================================
// TOMBOL ESCAPE
// ==========================================

document.addEventListener("keydown", function (event) {

    if (event.key === "Escape") {

        document.querySelectorAll(".modal.active").forEach(modal => {
            closeModal(modal.id);
        });

    }

});
