
// ========================================
// 1. DATA DAN ELEMEN KERANJANG
// ========================================

const cart = [];

const buyButtons = document.querySelectorAll(".buy-btn");
const cartBar = document.querySelector(".cart-bar");
const cartPopup = document.getElementById("cartPopup");
const openCart = document.getElementById("openCart");
const closeCart = document.getElementById("closeCart");
const cartItems = document.getElementById("cartItems");
const cartSubtotal = document.getElementById("cartSubtotal");
const cartCount = document.getElementById("cartCount");


// ========================================
// 2. MENAMBAHKAN PRODUK KE KERANJANG
// ========================================

buyButtons.forEach(button => {

    button.addEventListener("click", function () {

        const name = this.dataset.name;
        const category = this.dataset.category;

        // Mengubah harga "3.000" menjadi angka 3000
        const price = Number(this.dataset.price.replace(/\./g, ""));

        const existing = cart.find(item => item.name === name);

        if (existing) {
            existing.quantity++;
        } else {
            cart.push({
                name,
                category,
                price,
                quantity: 1
            });
        }

        renderCart();
        cartBar.classList.add("show");

    });

});


// ========================================
// 3. MENAMPILKAN ISI KERANJANG
// ========================================

function renderCart() {

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
        productPrice.textContent =
            "Rp " + item.price.toLocaleString("id-ID");

        productInfo.append(
            productName,
            productCategory,
            productPrice
        );

        // Kontrol jumlah
        const quantityControl = document.createElement("div");
        quantityControl.className = "quantity-control";

        const minusButton = document.createElement("button");
        minusButton.type = "button";
        minusButton.textContent = "-";
        minusButton.dataset.index = index;
        minusButton.className = "quantity-btn minus-btn";
        minusButton.disabled = item.quantity <= 1;

        const quantityNumber = document.createElement("span");
        quantityNumber.textContent = item.quantity;

        const plusButton = document.createElement("button");
        plusButton.type = "button";
        plusButton.textContent = "+";
        plusButton.dataset.index = index;
        plusButton.className = "quantity-btn plus-btn";

        quantityControl.append(
            minusButton,
            quantityNumber,
            plusButton
        );

        // Tombol hapus
        const deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.textContent = "🗑";
        deleteButton.className = "delete-cart-btn";
        deleteButton.dataset.index = index;
        deleteButton.title = "Hapus produk";

        cartItem.append(
            productInfo,
            quantityControl,
            deleteButton
        );

        cartItems.appendChild(cartItem);

    });

    // Perbarui jumlah produk dan subtotal
    cartCount.textContent = totalItems + " Produk";

    cartSubtotal.textContent =
        "Rp " + subtotal.toLocaleString("id-ID");

}


// ========================================
// 4. TAMBAH, KURANG, DAN HAPUS KERANJANG
// ========================================

cartItems.addEventListener("click", function (event) {

    const button = event.target.closest("button");

    if (!button) return;

    const index = Number(button.dataset.index);

    if (!cart[index]) return;

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

    // Tutup keranjang jika sudah kosong
    if (cart.length === 0) {
        cartBar.classList.remove("show");
        cartPopup.classList.remove("show");
    }

});


// ========================================
// 5. MEMBUKA POPUP KERANJANG
// ========================================

openCart.addEventListener("click", function () {

    renderCart();
    cartPopup.classList.add("show");

});


// ========================================
// 6. MENUTUP POPUP KERANJANG
// ========================================

closeCart.addEventListener("click", function () {

    cartPopup.classList.remove("show");

});


// ========================================
// 7. DATA DAN ELEMEN FAVORIT
// ========================================

const favorites = [];

const favoriteButtons = document.querySelectorAll(".favorite-btn");

const favoriteMenu = document.getElementById("favoriteMenu");
const favoritePage = document.getElementById("favoritePage");
const closeFavorite = document.getElementById("closeFavorite");

const favoriteList = document.getElementById("favoriteList");
const favoriteSearch = document.getElementById("favoriteSearch");
const favoriteInfo = document.getElementById("favoriteInfo");
const favoritePagination = document.getElementById("favoritePagination");

let favoriteCurrentPage = 1;
const favoritePerPage = 8;


// ========================================
// 8. MENAMBAHKAN DAN MENGHAPUS FAVORIT
// ========================================

favoriteButtons.forEach(button => {

    button.addEventListener("click", function () {

        const name = this.dataset.name;
        const category = this.dataset.category;
        const price = this.dataset.price;

        const index = favorites.findIndex(
            item => item.name === name
        );

        if (index === -1) {

            // Tambahkan produk ke favorit
            favorites.push({
                name,
                category,
                price
            });

            this.textContent = "★";
            this.classList.add("active");

        } else {

            // Hapus produk dari favorit
            favorites.splice(index, 1);

            this.textContent = "☆";
            this.classList.remove("active");

        }

        renderFavorites();

    });

});


// ========================================
// 9. MENAMPILKAN PRODUK FAVORIT
// ========================================

function renderFavorites() {

    favoriteList.innerHTML = "";

    const keyword = favoriteSearch.value.toLowerCase();

    // Pencarian nama produk
    const filteredFavorites = favorites.filter(item =>
        item.name.toLowerCase().includes(keyword)
    );

    const totalPages = Math.ceil(
        filteredFavorites.length / favoritePerPage
    );

    // Menjaga nomor halaman tetap valid
    if (totalPages === 0) {
        favoriteCurrentPage = 1;
    } else if (favoriteCurrentPage > totalPages) {
        favoriteCurrentPage = totalPages;
    }

    const startIndex =
        (favoriteCurrentPage - 1) * favoritePerPage;

    const displayedFavorites = filteredFavorites.slice(
        startIndex,
        startIndex + favoritePerPage
    );

    // ========================================
    // EMPTY STATE FAVORIT
    // ========================================

    if (filteredFavorites.length === 0) {

        const emptyState = document.createElement("div");
        emptyState.className = "favorite-empty";

        const emptyIcon = document.createElement("div");
        emptyIcon.className = "favorite-empty-icon";
        emptyIcon.textContent = favorites.length === 0 ? "♡" : "⌕";

        const emptyTitle = document.createElement("h3");
        emptyTitle.textContent = favorites.length === 0
            ? "Belum Ada Produk Favorit"
            : "Produk Tidak Ditemukan";

        const emptyDescription = document.createElement("p");
        emptyDescription.textContent = favorites.length === 0
            ? "Simpan produk yang kamu sukai dengan menekan ikon bintang pada produk."
            : "Coba gunakan kata kunci pencarian yang berbeda.";

        emptyState.append(
            emptyIcon,
            emptyTitle,
            emptyDescription
        );

        // Tombol kembali ke katalog hanya muncul
        // ketika daftar favorit benar-benar kosong
        if (favorites.length === 0) {

            const catalogButton = document.createElement("button");
            catalogButton.type = "button";
            catalogButton.className = "empty-favorite-btn";
            catalogButton.textContent = "Jelajahi Produk";

            catalogButton.addEventListener("click", function () {
                favoritePage.classList.remove("show");
            });

            emptyState.appendChild(catalogButton);

        }

        favoriteList.appendChild(emptyState);

    }

    // Membuat kartu produk
    displayedFavorites.forEach(item => {

        const card = document.createElement("div");
        card.className = "favorite-card";

        const image = document.createElement("div");
        image.className = "favorite-image";
        image.textContent = "Gambar Produk";

        // Tombol bintang
        const starButton = document.createElement("button");
        starButton.type = "button";
        starButton.className = "favorite-star";
        starButton.textContent = "★";
        starButton.dataset.name = item.name;
        starButton.title = "Hapus dari favorit";

        image.appendChild(starButton);

        // Nama produk
        const name = document.createElement("p");
        name.textContent = item.name;

        // Kategori produk
        const category = document.createElement("small");
        category.textContent = item.category;

        // Harga produk
        const price = document.createElement("strong");
        price.textContent = "Rp " + item.price;

        // Tombol beli
        const buyButton = document.createElement("button");
        buyButton.type = "button";
        buyButton.className = "favorite-buy";
        buyButton.textContent = "Beli";
        buyButton.dataset.name = item.name;

        card.append(
            image,
            name,
            category,
            price,
            buyButton
        );

        favoriteList.appendChild(card);

    });

    // Informasi jumlah data
    favoriteInfo.textContent =
        filteredFavorites.length === 0
            ? "Menampilkan 0 data"
            : `Menampilkan ${startIndex + 1}–${Math.min(
                startIndex + favoritePerPage,
                filteredFavorites.length
            )} dari ${filteredFavorites.length} data`;

    renderFavoritePagination(totalPages);

}


// ========================================
// 10. PAGINATION FAVORIT
// ========================================

function renderFavoritePagination(totalPages) {

    favoritePagination.innerHTML = "";

    if (totalPages <= 1) return;

    // Tombol sebelumnya
    const previousButton = document.createElement("button");
    previousButton.textContent = "‹";
    previousButton.disabled = favoriteCurrentPage === 1;

    previousButton.addEventListener("click", function () {

        favoriteCurrentPage--;
        renderFavorites();

    });

    favoritePagination.appendChild(previousButton);

    // Nomor halaman
    for (let page = 1; page <= totalPages; page++) {

        const pageButton = document.createElement("button");
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
    nextButton.textContent = "›";
    nextButton.disabled = favoriteCurrentPage === totalPages;

    nextButton.addEventListener("click", function () {

        favoriteCurrentPage++;
        renderFavorites();

    });

    favoritePagination.appendChild(nextButton);

}


// ========================================
// 11. AKSI DI DALAM POPUP FAVORIT
// ========================================

favoriteList.addEventListener("click", function (event) {

    // Hapus produk dari favorit
    const starButton = event.target.closest(".favorite-star");

    if (starButton) {

        const name = starButton.dataset.name;

        const index = favorites.findIndex(
            item => item.name === name
        );

        if (index !== -1) {
            favorites.splice(index, 1);
        }

        // Kembalikan bintang pada katalog
        favoriteButtons.forEach(button => {

            if (button.dataset.name === name) {
                button.textContent = "☆";
                button.classList.remove("active");
            }

        });

        renderFavorites();
        return;

    }

    // Beli produk dari popup favorit
    const buyButton = event.target.closest(".favorite-buy");

    if (buyButton) {

        const name = buyButton.dataset.name;

        const originalButton = [...buyButtons].find(
            button => button.dataset.name === name
        );

        if (originalButton) {
            originalButton.click();
        }

    }

});


// ========================================
// 12. PENCARIAN FAVORIT
// ========================================

favoriteSearch.addEventListener("input", function () {

    favoriteCurrentPage = 1;
    renderFavorites();

});


// ========================================
// 13. MEMBUKA POPUP FAVORIT
// ========================================

favoriteMenu.addEventListener("click", function (event) {

    event.preventDefault();

    console.log("1. Tombol Favorit diklik");

    favoritePage.classList.add("show");

    console.log("2. Class popup:", favoritePage.className);

    console.log(
        "3. Display popup:",
        window.getComputedStyle(favoritePage).display
    );

    favoriteCurrentPage = 1;
    favoriteSearch.value = "";

    renderFavorites();

});

// ========================================
// 14. MENUTUP POPUP FAVORIT
// ========================================

closeFavorite.addEventListener("click", function () {

    favoritePage.classList.remove("show");

});

// ========================================
// 15. PAGINATION KATALOG PRODUK
// ========================================

const productCards = document.querySelectorAll(".product-list .product-card");
const productPagination = document.getElementById("productPagination");

let productCurrentPage = 1;
const productPerPage = 8;

// Menghitung jumlah halaman berdasarkan jumlah produk
function renderProductPagination() {

    const totalPages = Math.ceil(
        productCards.length / productPerPage
    );

    // Pastikan halaman tetap valid
    if (totalPages === 0) {
        productCurrentPage = 1;
    } else if (productCurrentPage > totalPages) {
        productCurrentPage = totalPages;
    }

    // Menampilkan produk sesuai halaman
    productCards.forEach((card, index) => {

        const startIndex =
            (productCurrentPage - 1) * productPerPage;

        const endIndex =
            startIndex + productPerPage;

        card.style.display =
            index >= startIndex && index < endIndex
                ? ""
                : "none";

    });

    // Membuat tombol pagination
    productPagination.innerHTML = "";

    if (totalPages <= 1) return;

    // Tombol sebelumnya
    const previousButton = document.createElement("button");
    previousButton.type = "button";
    previousButton.textContent = "Sebelumnya";
    previousButton.disabled = productCurrentPage === 1;

    previousButton.addEventListener("click", function () {

        if (productCurrentPage > 1) {
            productCurrentPage--;
            renderProductPagination();
        }

    });

    productPagination.appendChild(previousButton);

    // Nomor halaman
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

    // Tombol berikutnya
    const nextButton = document.createElement("button");
    nextButton.type = "button";
    nextButton.textContent = "Berikutnya";
    nextButton.disabled = productCurrentPage === totalPages;

    nextButton.addEventListener("click", function () {

        if (productCurrentPage < totalPages) {
            productCurrentPage++;
            renderProductPagination();
        }

    });

    productPagination.appendChild(nextButton);

}

// Jalankan pagination saat halaman dibuka
renderProductPagination();
