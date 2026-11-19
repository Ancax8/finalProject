
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
// 2. FUNGSI TOMBOL BELI
// ========================================

buyButtons.forEach(button => {

    button.addEventListener("click", function () {

        const name = this.dataset.name;
        const category = this.dataset.category;

        const price = Number(
            this.dataset.price.replace(/\./g, "")
        );

        // Periksa apakah produk sudah ada
        const existing = cart.find(item => item.name === name);

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

        // Tampilkan bar keranjang
        cartBar.classList.add("show");

    });

});


// ========================================
// 3. FUNGSI MENAMPILKAN KERANJANG
// ========================================

function renderCart() {

    cartItems.innerHTML = "";

    let subtotal = 0;
    let totalItems = 0;

    cart.forEach((item, index) => {

        subtotal += item.price * item.quantity;
        totalItems += item.quantity;

        // Membuat elemen produk
        const cartItem = document.createElement("div");
        cartItem.className = "cart-item";

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

        // Kontrol jumlah produk
        const quantityControl = document.createElement("div");
        quantityControl.className = "quantity-control";

        const minusButton = document.createElement("button");
        minusButton.type = "button";
        minusButton.textContent = "-";
        minusButton.dataset.index = index;
        minusButton.className = "quantity-btn minus-btn";

        // Tombol minus nonaktif jika jumlah sudah 1
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

        cartItem.append(productInfo, quantityControl);
        cartItems.appendChild(cartItem);

    });

    // Perbarui jumlah seluruh barang
    cartCount.textContent = totalItems + " Produk";

    // Perbarui subtotal
    cartSubtotal.textContent =
        "Rp " + subtotal.toLocaleString("id-ID");

}


// ========================================
// 4. EVENT TOMBOL TAMBAH DAN KURANG
// ========================================

cartItems.addEventListener("click", function (event) {

    const button = event.target.closest(".quantity-btn");

    if (!button) return;

    const index = Number(button.dataset.index);

    // Pastikan produk tersedia
    if (!cart[index]) return;

    if (button.classList.contains("plus-btn")) {

        // Tambah jumlah produk
        cart[index].quantity++;

    } else if (button.classList.contains("minus-btn")) {

        // Kurangi jumlah jika lebih dari 1
        if (cart[index].quantity > 1) {
            cart[index].quantity--;
        }

    }

    // Perbarui tampilan keranjang
    renderCart();

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
