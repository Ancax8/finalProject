
// =================================
// SIDEBAR ADMIN
// =================================

const adminSidebar = document.getElementById("adminSidebar");
const sidebarClose = document.getElementById("sidebarClose");
const sidebarOverlay = document.getElementById("sidebarOverlay");

// Membuka sidebar
function openAdminSidebar() {
    adminSidebar?.classList.add("show");
    sidebarOverlay?.classList.add("show");
}

// Menutup sidebar
function closeAdminSidebar() {
    adminSidebar?.classList.remove("show");
    sidebarOverlay?.classList.remove("show");
}

sidebarClose?.addEventListener("click", closeAdminSidebar);
sidebarOverlay?.addEventListener("click", closeAdminSidebar);

// Menghubungkan tombol menu dari navbar jika tersedia.
// Selector disesuaikan dengan tombol menu navbar yang sudah ada.
const menuButtons = document.querySelectorAll(
    ".icon-list, #menuToggle, .menu-toggle"
);

menuButtons.forEach(button => {
    button.addEventListener("click", function () {
        if (window.innerWidth <= 850) {
            openAdminSidebar();
        }
    });
});


// =================================
// FILTER KATEGORI PRODUK
// =================================

const filterButtons = document.querySelectorAll(".filter-btn");
const productCards = document.querySelectorAll(".admin-product-card");
const productSearch = document.getElementById("productSearch");
const productEmpty = document.getElementById("productEmpty");

let activeCategory = "Semua";

function filterProducts() {
    const keyword = productSearch.value.toLowerCase().trim();
    let visibleCount = 0;

    productCards.forEach(card => {
        const productName = card.dataset.name;
        const productCategory = card.dataset.category;

        const matchCategory =
            activeCategory === "Semua" ||
            productCategory === activeCategory;

        const matchSearch = productName.includes(keyword);

        const isVisible = matchCategory && matchSearch;

        card.style.display = isVisible ? "" : "none";

        if (isVisible) {
            visibleCount++;
        }
    });

    productEmpty.style.display =
        visibleCount === 0 ? "block" : "none";
}

// Klik filter kategori
filterButtons.forEach(button => {
    button.addEventListener("click", function () {
        filterButtons.forEach(item => {
            item.classList.remove("active");
        });

        this.classList.add("active");

        activeCategory = this.dataset.category;

        filterProducts();
    });
});

// Pencarian produk
productSearch?.addEventListener("input", filterProducts);


// =================================
// TOMBOL TAMBAH PRODUK
// =================================

const addProductBtn = document.getElementById("addProductBtn");

addProductBtn?.addEventListener("click", function () {
    alert("Form tambah produk akan kita buat pada tahap berikutnya.");
});


// =================================
// TOMBOL EDIT DAN NONAKTIFKAN
// =================================

document.querySelectorAll(".edit-product-btn").forEach(button => {
    button.addEventListener("click", function () {
        alert("Fitur edit produk akan kita integrasikan dengan database.");
    });
});

document.querySelectorAll(".delete-product-btn").forEach(button => {
    button.addEventListener("click", function () {
        alert("Fitur nonaktifkan produk akan kita integrasikan dengan database.");
    });
});
