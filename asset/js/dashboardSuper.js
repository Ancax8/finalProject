
// ==========================================
// MODAL TAMBAH PRODUK
// ==========================================

function openModal(id) {

    const modal = document.getElementById(id);

    if (modal) {
        modal.classList.add("active");
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

}


// ==========================================
// KLIK AREA LUAR MODAL
// ==========================================

document.querySelectorAll(".modal").forEach(modal => {

    modal.addEventListener("click", function (event) {

        if (event.target === modal) {
            modal.classList.remove("active");
        }

    });

});


// ==========================================
// ESCAPE UNTUK MENUTUP MODAL
// ==========================================

document.addEventListener("keydown", function (event) {

    if (event.key === "Escape") {

        document.querySelectorAll(".modal.active").forEach(modal => {
            modal.classList.remove("active");
        });

    }

});
