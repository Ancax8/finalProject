
function openModal(modalId) {
    const modal = document.getElementById(modalId);

    if (modal) {
        modal.classList.add("active");
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);

    if (modal) {
        modal.classList.remove("active");
    }
}

// Klik area luar modal untuk menutup
document.querySelectorAll(".modal").forEach(modal => {

    modal.addEventListener("click", function(event) {

        if (event.target === modal) {
            modal.classList.remove("active");
        }

    });

});

// Tombol Escape untuk menutup semua modal
document.addEventListener("keydown", function(event) {

    if (event.key === "Escape") {
        document.querySelectorAll(".modal.active").forEach(modal => {
            modal.classList.remove("active");
        });
    }

});
