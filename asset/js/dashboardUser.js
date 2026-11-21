// ==================================================
// DASHBOARD USER - MARKETPLACE ATK
// File: dashboardUser.js
// Mengatur keranjang, favorit, dan pagination katalog.
// Menu hamburger/sidebar tetap ditangani oleh navbar.js.
// ==================================================


// ==================================================
// 1. DATA DAN ELEMEN KERANJANG
// ==================================================

const cart = [];

const buyButtons = document.querySelectorAll(".buy-btn");
const cartBar = document.querySelector(".cart-bar");
const cartPopup = document.getElementById("cartPopup");
const openCart = document.getElementById("openCart");
const closeCart = document.getElementById("closeCart");
const cartItems = document.getElementById("cartItems");
const cartSubtotal = document.getElementById("cartSubtotal");
const cartCount = document.getElementById("cartCount");
// MENGAMBIL BAHASA YANG SEDANG DIGUNAKAN
function bahasaAktif() {
    return localStorage.getItem("bahasa") || "id";
}

function teksBahasa(indonesia, english) {
    return bahasaAktif() === "en" ? english : indonesia;
}


// ==================================================
// 2. MENAMBAHKAN PRODUK KE KERANJANG
// ==================================================

buyButtons.forEach((button) => {
    button.addEventListener("click", function () {
        const name = this.dataset.name;
        const category = this.dataset.category;

        // Contoh: "3.000" menjadi angka 3000.
        const price = Number((this.dataset.price || "0").replace(/\./g, ""));

        const existing = cart.find((item) => item.name === name);

        if (existing) {
            existing.quantity++;
        } else {
            cart.push({
                name: name,
                category: category,
                price: price,
                quantity: 1
            });
        }

        renderCart();

        if (cartBar) {
            cartBar.classList.add("show");
        }
    });
});


// ==================================================
// 3. MENAMPILKAN ISI KERANJANG
// ==================================================

function renderCart() {
    if (!cartItems || !cartCount || !cartSubtotal) return;

    cartItems.innerHTML = "";

    let subtotal = 0;
    let totalItems = 0;

    cart.forEach((item, index) => {
        subtotal += item.price * item.quantity;
        totalItems += item.quantity;

        const cartItem = document.createElement("div");
        cartItem.className = "cart-item";

        // Informasi produk
        const productInfo = document.createElement("div");
        productInfo.className = "cart-product-info";

        const productName = document.createElement("strong");
        productName.textContent = item.name;

        const productCategory = document.createElement("p");
        productCategory.textContent = item.category;

        const productPrice = document.createElement("strong");
        productPrice.textContent = "Rp " + item.price.toLocaleString("id-ID");

        productInfo.append(productName, productCategory, productPrice);

        // Kontrol jumlah
        const quantityControl = document.createElement("div");
        quantityControl.className = "quantity-control";

        const minusButton = document.createElement("button");
        minusButton.type = "button";
        minusButton.textContent = "-";
        minusButton.dataset.index = index;
        minusButton.className = "quantity-btn minus-btn";
        minusButton.disabled = item.quantity <= 1;
        minusButton.setAttribute(
            "aria-label",
            teksBahasa("Kurangi jumlah", "Decrease quantity")
        );
        const quantityNumber = document.createElement("span");
        quantityNumber.textContent = item.quantity;

        const plusButton = document.createElement("button");
        plusButton.type = "button";
        plusButton.textContent = "+";
        plusButton.dataset.index = index;
        plusButton.className = "quantity-btn plus-btn";
        plusButton.setAttribute("aria-label", "Tambah jumlah");

        quantityControl.append(minusButton, quantityNumber, plusButton);

        // Tombol hapus
        const deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.textContent = "🗑";
        deleteButton.className = "delete-cart-btn";
        deleteButton.dataset.index = index;
        deleteButton.title = teksBahasa("Hapus produk", "Remove product");
        deleteButton.setAttribute(
            "aria-label",
            teksBahasa("Hapus produk", "Remove product")
        );
        deleteButton.setAttribute("aria-label", "Hapus produk");

        cartItem.append(productInfo, quantityControl, deleteButton);
        cartItems.appendChild(cartItem);
    });

    cartCount.textContent = totalItems + " " +
        teksBahasa("Produk", "Products");
    cartSubtotal.textContent = "Rp " + subtotal.toLocaleString("id-ID");
}


// ==================================================
// 4. TAMBAH, KURANG, DAN HAPUS PRODUK KERANJANG
// ==================================================

if (cartItems) {
    cartItems.addEventListener("click", function (event) {
        const button = event.target.closest("button");
        if (!button) return;

        const index = Number(button.dataset.index);
        if (!Number.isInteger(index) || !cart[index]) return;

        if (button.classList.contains("plus-btn")) {
            cart[index].quantity++;
        } else if (button.classList.contains("minus-btn")) {
            if (cart[index].quantity > 1) {
                cart[index].quantity--;
            }
        } else if (button.classList.contains("delete-cart-btn")) {
            cart.splice(index, 1);
        } else {
            return;
        }

        renderCart();

        if (cart.length === 0) {
            if (cartBar) cartBar.classList.remove("show");
            if (cartPopup) cartPopup.classList.remove("show");
        }
    });
}


// ==================================================
// 5. MEMBUKA POPUP KERANJANG
// ==================================================

if (openCart && cartPopup) {
    openCart.addEventListener("click", function () {
        renderCart();
        cartPopup.classList.add("show");
    });
}


// ==================================================
// 6. MENUTUP POPUP KERANJANG
// ==================================================

if (closeCart && cartPopup) {
    closeCart.addEventListener("click", function () {
        cartPopup.classList.remove("show");
    });
}


// ==================================================
// 7. DATA DAN ELEMEN FAVORIT
// ==================================================

const favorites = [];

const favoriteButtons = document.querySelectorAll(".favorite-btn");

// ID ini mengikuti navbar.php dan dashboardUser.php milikmu.
const favoriteMenu = document.getElementById("favoritMenu");
const favoritePage = document.getElementById("favoritePage");
const closeFavorite = document.getElementById("closeFavorite");

const favoriteList = document.getElementById("favoriteList");
const favoriteSearch = document.getElementById("favoriteSearch");
const favoriteInfo = document.getElementById("favoriteInfo");
const favoritePagination = document.getElementById("favoritePagination");

let favoriteCurrentPage = 1;
const favoritePerPage = 8;


// ==================================================
// 8. MENAMBAHKAN DAN MENGHAPUS FAVORIT DARI KATALOG
// ==================================================

favoriteButtons.forEach((button) => {
    button.addEventListener("click", function () {
        const name = this.dataset.name;
        const category = this.dataset.category;
        const price = this.dataset.price;

        const index = favorites.findIndex((item) => item.name === name);

        if (index === -1) {
            favorites.push({
                name: name,
                category: category,
                price: price
            });

            this.textContent = "★";
            this.classList.add("active");
        } else {
            favorites.splice(index, 1);

            this.textContent = "☆";
            this.classList.remove("active");
        }

        renderFavorites();
    });
});


// ==================================================
// 9. MENAMPILKAN PRODUK FAVORIT
// ==================================================

function renderFavorites() {
    if (!favoriteList || !favoriteSearch || !favoriteInfo || !favoritePagination) {
        return;
    }

    favoriteList.innerHTML = "";

    const keyword = favoriteSearch.value.trim().toLowerCase();

    const filteredFavorites = favorites.filter((item) =>
        item.name.toLowerCase().includes(keyword)
    );

    const totalPages = Math.ceil(filteredFavorites.length / favoritePerPage);

    // Pastikan nomor halaman tetap valid.
    if (totalPages === 0) {
        favoriteCurrentPage = 1;
    } else if (favoriteCurrentPage > totalPages) {
        favoriteCurrentPage = totalPages;
    }

    const startIndex = (favoriteCurrentPage - 1) * favoritePerPage;

    const displayedFavorites = filteredFavorites.slice(
        startIndex,
        startIndex + favoritePerPage
    );

    // EMPTY STATE
    if (filteredFavorites.length === 0) {
        const emptyState = document.createElement("div");
        emptyState.className = "favorite-empty";

        const emptyIcon = document.createElement("div");
        emptyIcon.className = "favorite-empty-icon";
        emptyIcon.textContent = favorites.length === 0 ? "♡" : "⌕";

        const emptyTitle = document.createElement("h3");
        emptyTitle.textContent = favorites.length === 0
            ? teksBahasa("Belum Ada Produk Favorit", "No Favorite Products Yet")
            : teksBahasa("Produk Tidak Ditemukan", "Product Not Found");

        const emptyDescription = document.createElement("p");
        emptyDescription.textContent = favorites.length === 0
            ? teksBahasa(
                "Simpan produk yang kamu sukai dengan menekan ikon bintang pada produk.",
                "Save products you like by clicking the star icon."
            )
            : teksBahasa(
                "Coba gunakan kata kunci pencarian yang berbeda.",
                "Try using a different search keyword."
            );

        // Tombol katalog hanya muncul jika favorit benar-benar kosong.
        if (favorites.length === 0) {
            const catalogButton = document.createElement("button");
            catalogButton.type = "button";
            catalogButton.className = "empty-favorite-btn";
            catalogButton.textContent = teksBahasa(
                "Jelajahi Produk",
                "Explore Products"
            );

            catalogButton.addEventListener("click", function () {
                if (favoritePage) favoritePage.classList.remove("show");
            });

            emptyState.appendChild(catalogButton);
        }

        favoriteList.appendChild(emptyState);
    }

    // KARTU PRODUK FAVORIT
    displayedFavorites.forEach((item) => {
        const card = document.createElement("div");
        card.className = "favorite-card";

        const image = document.createElement("div");
        image.className = "favorite-image";
        image.textContent = teksBahasa("Gambar Produk", "Product Image");

        const starButton = document.createElement("button");
        starButton.type = "button";
        starButton.className = "favorite-star";
        starButton.textContent = "★";
        starButton.dataset.name = item.name;
        starButton.title = teksBahasa("Hapus dari favorit", "Remove from favorites");
        starButton.setAttribute("aria-label", "Hapus " + item.name + " dari favorit");

        image.appendChild(starButton);

        const name = document.createElement("p");
        name.textContent = item.name;

        const category = document.createElement("small");
        category.textContent = item.category;

        const price = document.createElement("strong");
        price.textContent = "Rp " + item.price;

        const buyButton = document.createElement("button");
        buyButton.type = "button";
        buyButton.className = "favorite-buy";
        buyButton.textContent = teksBahasa("Beli", "Buy");
        buyButton.dataset.name = item.name;

        card.append(image, name, category, price, buyButton);
        favoriteList.appendChild(card);
    });

    // INFORMASI JUMLAH DATA
    favoriteInfo.textContent = filteredFavorites.length === 0
        ? teksBahasa("Menampilkan 0 data", "Showing 0 items")
        : bahasaAktif() === "en"
            ? `Showing ${startIndex + 1}–${Math.min(
                startIndex + favoritePerPage,
                filteredFavorites.length
            )} of ${filteredFavorites.length} items`
            : `Menampilkan ${startIndex + 1}–${Math.min(
                startIndex + favoritePerPage,
                filteredFavorites.length
            )} dari ${filteredFavorites.length} data`;

    renderFavoritePagination(totalPages);
}


// ==================================================
// 10. PAGINATION FAVORIT
// ==================================================

function renderFavoritePagination(totalPages) {
    if (!favoritePagination) return;

    favoritePagination.innerHTML = "";

    if (totalPages <= 1) return;

    // Tombol sebelumnya
    const previousButton = document.createElement("button");
    previousButton.type = "button";
    previousButton.textContent = "‹";
    previousButton.disabled = favoriteCurrentPage === 1;

    previousButton.addEventListener("click", function () {
        if (favoriteCurrentPage > 1) {
            favoriteCurrentPage--;
            renderFavorites();
        }
    });

    favoritePagination.appendChild(previousButton);

    // Nomor halaman
    for (let page = 1; page <= totalPages; page++) {
        const pageButton = document.createElement("button");
        pageButton.type = "button";
        pageButton.textContent = page;

        if (page === favoriteCurrentPage) {
            pageButton.classList.add("active");
        }

        pageButton.addEventListener("click", function () {
            favoriteCurrentPage = page;
            renderFavorites();
        });

        favoritePagination.appendChild(pageButton);
    }

    // Tombol berikutnya
    const nextButton = document.createElement("button");
    nextButton.type = "button";
    nextButton.textContent = "›";
    nextButton.disabled = favoriteCurrentPage === totalPages;

    nextButton.addEventListener("click", function () {
        if (favoriteCurrentPage < totalPages) {
            favoriteCurrentPage++;
            renderFavorites();
        }
    });

    favoritePagination.appendChild(nextButton);
}


// ==================================================
// 11. AKSI DI DALAM POPUP FAVORIT
// ==================================================

if (favoriteList) {
    favoriteList.addEventListener("click", function (event) {
        // Hapus produk dari favorit melalui bintang.
        const starButton = event.target.closest(".favorite-star");

        if (starButton) {
            const name = starButton.dataset.name;

            const index = favorites.findIndex((item) => item.name === name);

            if (index !== -1) {
                favorites.splice(index, 1);
            }

            // Sinkronkan kembali bintang pada katalog.
            favoriteButtons.forEach((button) => {
                if (button.dataset.name === name) {
                    button.textContent = "☆";
                    button.classList.remove("active");
                }
            });

            renderFavorites();
            return;
        }

        // Beli produk dari popup favorit.
        const buyButton = event.target.closest(".favorite-buy");

        if (buyButton) {
            const name = buyButton.dataset.name;

            const originalButton = Array.from(buyButtons).find(
                (button) => button.dataset.name === name
            );

            if (originalButton) {
                originalButton.click();
            }
        }
    });
}


// ==================================================
// 12. PENCARIAN FAVORIT
// ==================================================

if (favoriteSearch) {
    favoriteSearch.addEventListener("input", function () {
        favoriteCurrentPage = 1;
        renderFavorites();
    });
}


// ==================================================
// 13. MEMBUKA POPUP FAVORIT DARI MENU NAVBAR
// ==================================================

if (favoriteMenu && favoritePage) {
    favoriteMenu.addEventListener("click", function (event) {
        event.preventDefault();

        // Buka popup Favorite.
        favoritePage.classList.add("show");

        // Reset pencarian dan halaman setiap popup dibuka.
        favoriteCurrentPage = 1;

        if (favoriteSearch) {
            favoriteSearch.value = "";
        }

        renderFavorites();

        // Tutup sidebar jika navbar.js menyediakan fungsi penutup.
        // Event ini tidak mengubah atau menghapus event hamburger.
        document.dispatchEvent(new CustomEvent("favoriteMenuSelected"));
    });
}


// ==================================================
// 14. MENUTUP POPUP FAVORIT
// ==================================================

if (closeFavorite && favoritePage) {
    closeFavorite.addEventListener("click", function () {
        favoritePage.classList.remove("show");
    });
}


// ==================================================
// 15. PENCARIAN DAN PAGINATION KATALOG PRODUK
// ==================================================

const productList = document.querySelector(".product-list");
const productSearch = document.getElementById("pencarian");

const productCards = Array.from(
    document.querySelectorAll(".product-list .product-card")
);

const productPagination = document.getElementById("productPagination");

let productCurrentPage = 1;
const productPerPage = 8;

// Menyimpan hasil pencarian
let filteredProductCards = [...productCards];

// Pesan ketika produk tidak ditemukan
const productEmptyState = document.createElement("div");

productEmptyState.className = "product-empty-state";
productEmptyState.style.gridColumn = "1 / -1";
productEmptyState.style.textAlign = "center";
productEmptyState.style.padding = "30px 15px";
productEmptyState.style.color = "#777";
productEmptyState.style.display = "none";

productEmptyState.textContent = teksBahasa(
    "Produk tidak ditemukan",
    "Product not found"
);

if (productList) {
    productList.appendChild(productEmptyState);
}


// ==================================================
// FUNGSI PENCARIAN PRODUK
// ==================================================

function cariProduk(keyword = "") {

    const kataKunci = keyword.trim().toLowerCase();

    filteredProductCards = productCards.filter((card) => {

        const namaProduk = card.querySelector("h4")
            ?.textContent.toLowerCase() || "";

        const kategoriProduk = card.querySelector(".product-info p")
            ?.textContent.toLowerCase() || "";

        return namaProduk.includes(kataKunci) ||
            kategoriProduk.includes(kataKunci);
    });

    // Kembali ke halaman pertama setelah pencarian
    productCurrentPage = 1;

    renderProductPagination();
}


// ==================================================
// FUNGSI PAGINATION DAN TAMPILAN PRODUK
// ==================================================

function renderProductPagination() {

    if (!productList || !productPagination) return;

    const totalPages = Math.ceil(
        filteredProductCards.length / productPerPage
    );

    // Sembunyikan semua produk terlebih dahulu
    productCards.forEach((card) => {
        card.style.display = "none";
    });

    // Jika produk tidak ditemukan
    if (filteredProductCards.length === 0) {

        productEmptyState.style.display = "block";

    } else {

        productEmptyState.style.display = "none";

        const startIndex =
            (productCurrentPage - 1) * productPerPage;

        const endIndex = startIndex + productPerPage;

        const displayedProducts = filteredProductCards.slice(
            startIndex,
            endIndex
        );

        displayedProducts.forEach((card) => {
            card.style.display = "";
        });
    }

    // Reset pagination
    productPagination.innerHTML = "";

    if (totalPages <= 1) return;

    // TOMBOL SEBELUMNYA
    const previousButton = document.createElement("button");

    previousButton.type = "button";
    previousButton.textContent = teksBahasa(
        "Sebelumnya",
        "Previous"
    );

    previousButton.disabled = productCurrentPage === 1;

    previousButton.addEventListener("click", function () {

        if (productCurrentPage > 1) {
            productCurrentPage--;
            renderProductPagination();
        }

    });

    productPagination.appendChild(previousButton);


    // NOMOR HALAMAN
    for (let page = 1; page <= totalPages; page++) {

        const pageButton = document.createElement("button");

        pageButton.type = "button";
        pageButton.textContent = page;
        pageButton.className = "page-number";

        if (page === productCurrentPage) {
            pageButton.classList.add("active");
        }

        pageButton.addEventListener("click", function () {

            productCurrentPage = page;
            renderProductPagination();

        });

        productPagination.appendChild(pageButton);
    }


    // TOMBOL BERIKUTNYA
    const nextButton = document.createElement("button");

    nextButton.type = "button";
    nextButton.textContent = teksBahasa(
        "Berikutnya",
        "Next"
    );

    nextButton.disabled = productCurrentPage === totalPages;

    nextButton.addEventListener("click", function () {

        if (productCurrentPage < totalPages) {
            productCurrentPage++;
            renderProductPagination();
        }

    });

    productPagination.appendChild(nextButton);
}


// ==================================================
// EVENT INPUT PENCARIAN
// ==================================================

if (productSearch) {

    productSearch.addEventListener("input", function () {

        cariProduk(this.value);

    });

}


// Jalankan ketika halaman pertama kali dibuka
renderProductPagination();
// ==================================================
// MEMPERBARUI TEKS KETIKA BAHASA BERUBAH
// ==================================================

document.addEventListener("bahasaDiubah", function () {

    // Perbarui teks keranjang
    renderCart();

    // Perbarui teks favorit
    renderFavorites();

    // Perbarui pesan pencarian produk
    productEmptyState.textContent = teksBahasa(
        "Produk tidak ditemukan",
        "Product not found"
    );

    // Perbarui pagination katalog
    renderProductPagination();

});
// ==================================================
// 16. POPUP HELP
// ==================================================

const helpPage = document.getElementById("helpPage");
const closeHelp = document.getElementById("closeHelp");
const helpMenuButton = document.getElementById("helpMenu");

// MEMBUKA POPUP HELP
document.addEventListener("bukaHelp", function () {
    if (helpPage) {
        helpPage.classList.add("show");
    }
});

// MENUTUP POPUP HELP
if (closeHelp && helpPage) {
    closeHelp.addEventListener("click", function () {
        helpPage.classList.remove("show");
    });
}

// MENUTUP KETIKA KLIK AREA LUAR POPUP
if (helpPage) {
    helpPage.addEventListener("click", function (e) {
        if (e.target === helpPage) {
            helpPage.classList.remove("show");
        }
    });
}