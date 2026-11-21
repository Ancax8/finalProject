<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User | Marketplace ATK</title>

    <!-- CSS Dashboard User -->
    <link rel="stylesheet" href="../asset/css/navbar.css?v=3">
    <link rel="stylesheet" href="../asset/css/dashboardUser.css">
</head>

<body>
    <?php include "../layout/navbar.php"; ?>


    <!-- KONTEN UTAMA -->
    <main class="dashboard-container">
        <!-- AREA PRODUK -->
        <section class="main-content">

            <!-- BANNER PRODUK -->
            <section class="banner">
                <h2 data-id="Banner Produk" data-en="Product Banner">
                    Banner Produk
                </h2>
            </section>

            <!-- KATEGORI -->
            <section class="categories">
                <h3 data-id="Kategori" data-en="Categories">
                    Kategori
                </h3>

                <div class="category-list">

                    <button class="category-btn active" data-category="Semua" data-id="Semua" data-en="All">
                        Semua
                    </button>

                    <button class="category-btn" data-category="Alat Tulis" data-id="Alat Tulis" data-en="Stationery">
                        Alat Tulis
                    </button>

                    <button class="category-btn" data-category="Buku" data-id="Buku" data-en="Books">
                        Buku
                    </button>

                    <button class="category-btn" data-category="Surat-Menyurat" data-id="Surat-Menyurat"
                        data-en="Correspondence">
                        Surat-Menyurat
                    </button>

                    <button class="category-btn" data-category="Perlengkapan Kantor" data-id="Perlengkapan Kantor"
                        data-en="Office Supplies">
                        Perlengkapan Kantor
                    </button>

                </div>
            </section>

            <!-- KATALOG PRODUK -->

            <section class="product-catalog">
                <h3 data-id="Produk" data-en="Products">
                    Produk
                </h3>

                <div class="product-list">

                    <?php
                    // Data produk sementara untuk pengujian tampilan
                    $produk = [
                        ["Pulpen Gel 0.5 mm", "Alat Tulis", "3.000"],
                        ["Buku Tulis B5", "Buku", "7.000"],
                        ["Amplop Besar", "Surat-Menyurat", "1.000"],
                        ["Pensil 2B", "Alat Tulis", "2.500"],
                        ["Penghapus", "Alat Tulis", "2.000"],
                        ["Buku Gambar", "Buku", "8.000"],
                        ["Spidol Hitam", "Alat Tulis", "5.000"],
                        ["Map Plastik", "Perlengkapan Kantor", "3.500"],
                        ["Penggaris 30 cm", "Alat Tulis", "4.000"],
                        ["Kertas HVS A4", "Perlengkapan Kantor", "45.000"],
                        ["Sticky Notes", "Perlengkapan Kantor", "6.000"],
                        ["Buku Agenda", "Buku", "15.000"]
                    ];

                    foreach ($produk as $item) {
                        ?>

                        <div class="product-card">

                            <button class="favorite-btn" type="button" data-name="<?= htmlspecialchars($item[0]) ?>"
                                data-category="<?= htmlspecialchars($item[1]) ?>"
                                data-price="<?= htmlspecialchars($item[2]) ?>">
                                ☆
                            </button>

                            <div class="product-image" data-id="Gambar Produk" data-en="Product Image">
                                Gambar Produk
                            </div>

                            <div class="product-info">
                                <h4><?= htmlspecialchars($item[0]) ?></h4>
                                <p><?= htmlspecialchars($item[1]) ?></p>
                                <span>Rp <?= htmlspecialchars($item[2]) ?></span>
                            </div>

                            <button class="buy-btn" type="button" data-name="<?= htmlspecialchars($item[0]) ?>"
                                data-category="<?= htmlspecialchars($item[1]) ?>"
                                data-price="<?= htmlspecialchars($item[2]) ?>" data-id="Beli" data-en="Buy">
                                Beli
                            </button>
                        </div>
                    <?php } ?>
                </div>

                <!-- NAVIGASI HALAMAN -->
                <div class="pagination" id="productPagination">
                </div>

            </section>


            <!-- POPUP FAVORIT SAYA -->
            <div class="favorite-overlay" id="favoritePage">

                <div class="favorite-modal">

                    <!-- HEADER -->
                    <div class="favorite-header">
                        <h2 data-id="FAVORIT SAYA" data-en="MY FAVORITES">
                            FAVORIT SAYA
                        </h2>

                        <button type="button" id="closeFavorite">X</button>
                    </div>

                    <!-- PENCARIAN -->
                    <div class="favorite-search">
                        <input type="text" id="favoriteSearch" placeholder="⌕ Cari nama produk..."
                            data-placeholder-id="⌕ Cari nama produk..." data-placeholder-en="⌕ Search product name...">
                    </div>

                    <!-- DAFTAR PRODUK FAVORIT -->
                    <div class="favorite-list" id="favoriteList">
                    </div>

                    <!-- FOOTER DAN PAGINATION -->
                    <div class="favorite-footer">

                        <p id="favoriteInfo" data-id="Menampilkan 0 data" data-en="Showing 0 items">
                            Menampilkan 0 data
                        </p>

                        <div class="favorite-pagination" id="favoritePagination">
                        </div>

                    </div>

                </div>

            </div>


            <!-- POPUP PUSAT BANTUAN -->
            <div class="help-overlay" id="helpPage">

                <div class="help-modal">

                    <!-- HEADER -->
                    <div class="help-header">
                        <div>
                            <h2 data-id="PUSAT BANTUAN" data-en="HELP CENTER">
                                PUSAT BANTUAN
                            </h2>

                            <p data-id="Panduan Penggunaan E-commerce ATK" data-en="E-commerce ATK User Guide">
                                Panduan Penggunaan E-commerce ATK
                            </p>
                        </div>

                        <button type="button" id="closeHelp">X</button>
                    </div>

                    <!-- KONTEN PANDUAN -->
                    <div class="help-content">

                        <!-- 1. MEMULAI -->
                        <details class="help-item">
                            <summary data-id="01. Memulai Menggunakan E-commerce ATK" data-en="01. Getting Started">
                                01. Memulai Menggunakan E-commerce ATK
                            </summary>

                            <div class="help-description">
                                <p data-id="E-commerce ATK merupakan platform untuk melihat dan membeli berbagai perlengkapan alat tulis kantor."
                                    data-en="E-commerce ATK is a platform for browsing and purchasing various stationery and office supplies.">
                                    E-commerce ATK merupakan platform untuk melihat dan membeli berbagai perlengkapan
                                    alat tulis kantor.
                                </p>

                                <ol>
                                    <li data-id="Buka halaman utama untuk melihat katalog produk."
                                        data-en="Open the homepage to browse the product catalog.">
                                        Buka halaman utama untuk melihat katalog produk.
                                    </li>
                                    <li data-id="Jelajahi kategori untuk menemukan produk."
                                        data-en="Browse categories to find products.">
                                        Jelajahi kategori untuk menemukan produk.
                                    </li>
                                    <li data-id="Gunakan kolom pencarian untuk mencari produk tertentu."
                                        data-en="Use the search field to find specific products.">
                                        Gunakan kolom pencarian untuk mencari produk tertentu.
                                    </li>
                                </ol>
                            </div>
                        </details>

                        <!-- 2. AKUN -->
                        <details class="help-item">
                            <summary data-id="02. Registrasi dan Login" data-en="02. Registration and Login">
                                02. Registrasi dan Login
                            </summary>

                            <div class="help-description">
                                <ol>
                                    <li data-id="Pilih menu registrasi untuk membuat akun baru."
                                        data-en="Select the registration menu to create a new account.">
                                        Pilih menu registrasi untuk membuat akun baru.
                                    </li>
                                    <li data-id="Isi nama, nomor telepon, dan email sesuai data."
                                        data-en="Enter your name, phone number, and email address.">
                                        Isi nama, nomor telepon, dan email sesuai data.
                                    </li>
                                    <li data-id="Lakukan verifikasi email jika diminta."
                                        data-en="Verify your email if requested.">
                                        Lakukan verifikasi email jika diminta.
                                    </li>
                                    <li data-id="Login menggunakan akun yang telah dibuat."
                                        data-en="Log in using your registered account.">
                                        Login menggunakan akun yang telah dibuat.
                                    </li>
                                </ol>
                            </div>
                        </details>

                        <!-- 3. PROFIL -->
                        <details class="help-item">
                            <summary data-id="03. Mengelola Profil" data-en="03. Managing Your Profile">
                                03. Mengelola Profil
                            </summary>

                            <div class="help-description">
                                <ol>
                                    <li data-id="Buka menu Profile pada sidebar."
                                        data-en="Open the Profile menu in the sidebar.">
                                        Buka menu Profile pada sidebar.
                                    </li>
                                    <li data-id="Periksa informasi nama, email, nomor telepon, dan alamat."
                                        data-en="Review your name, email, phone number, and address.">
                                        Periksa informasi nama, email, nomor telepon, dan alamat.
                                    </li>
                                    <li data-id="Gunakan Edit Profile untuk mengubah informasi jika fitur tersebut tersedia."
                                        data-en="Use Edit Profile to update your information if the feature is available.">
                                        Gunakan Edit Profile untuk mengubah informasi jika fitur tersebut tersedia.
                                    </li>
                                </ol>
                            </div>
                        </details>

                        <!-- 4. PRODUK -->
                        <details class="help-item">
                            <summary data-id="04. Mencari dan Melihat Produk"
                                data-en="04. Searching and Viewing Products">
                                04. Mencari dan Melihat Produk
                            </summary>

                            <div class="help-description">
                                <ol>
                                    <li data-id="Gunakan kolom pencarian untuk menemukan nama produk."
                                        data-en="Use the search field to find product names.">
                                        Gunakan kolom pencarian untuk menemukan nama produk.
                                    </li>
                                    <li data-id="Pilih kategori untuk melihat kelompok produk tertentu."
                                        data-en="Select a category to browse specific product groups.">
                                        Pilih kategori untuk melihat kelompok produk tertentu.
                                    </li>
                                    <li data-id="Perhatikan nama, kategori, harga, dan ketersediaan stok."
                                        data-en="Check the product name, category, price, and stock availability.">
                                        Perhatikan nama, kategori, harga, dan ketersediaan stok.
                                    </li>
                                </ol>
                            </div>
                        </details>

                        <!-- 5. FAVORIT -->
                        <details class="help-item">
                            <summary data-id="05. Menggunakan Favorit" data-en="05. Using Favorites">
                                05. Menggunakan Favorit
                            </summary>

                            <div class="help-description">
                                <ol>
                                    <li data-id="Tekan ikon bintang pada produk untuk menambahkannya ke Favorit."
                                        data-en="Click the star icon on a product to add it to Favorites.">
                                        Tekan ikon bintang pada produk untuk menambahkannya ke Favorit.
                                    </li>
                                    <li data-id="Buka menu Favorit pada sidebar untuk melihat produk tersimpan."
                                        data-en="Open Favorites in the sidebar to view saved products.">
                                        Buka menu Favorit pada sidebar untuk melihat produk tersimpan.
                                    </li>
                                    <li data-id="Tekan kembali ikon bintang untuk menghapus produk dari Favorit."
                                        data-en="Click the star icon again to remove a product from Favorites.">
                                        Tekan kembali ikon bintang untuk menghapus produk dari Favorit.
                                    </li>
                                </ol>
                            </div>
                        </details>

                        <!-- 6. KERANJANG -->
                        <details class="help-item">
                            <summary data-id="06. Menggunakan Keranjang Belanja" data-en="06. Using the Shopping Cart">
                                06. Menggunakan Keranjang Belanja
                            </summary>

                            <div class="help-description">
                                <ol>
                                    <li data-id="Tekan tombol Beli pada produk untuk memasukkannya ke keranjang."
                                        data-en="Click Buy on a product to add it to your cart.">
                                        Tekan tombol Beli pada produk untuk memasukkannya ke keranjang.
                                    </li>
                                    <li data-id="Buka ikon keranjang untuk melihat barang yang dipilih."
                                        data-en="Open the cart icon to view selected items.">
                                        Buka ikon keranjang untuk melihat barang yang dipilih.
                                    </li>
                                    <li data-id="Gunakan tombol tambah dan kurang untuk mengatur jumlah produk."
                                        data-en="Use the plus and minus buttons to adjust product quantities.">
                                        Gunakan tombol tambah dan kurang untuk mengatur jumlah produk.
                                    </li>
                                    <li data-id="Periksa subtotal sebelum melanjutkan checkout."
                                        data-en="Review the subtotal before proceeding to checkout.">
                                        Periksa subtotal sebelum melanjutkan checkout.
                                    </li>
                                </ol>
                            </div>
                        </details>

                        <!-- 7. CHECKOUT -->
                        <details class="help-item">
                            <summary data-id="07. Checkout dan Pembayaran" data-en="07. Checkout and Payment">
                                07. Checkout and Payment
                            </summary>

                            <div class="help-description">
                                <ol>
                                    <li data-id="Pastikan sudah login sebelum melakukan checkout."
                                        data-en="Make sure you are logged in before checkout.">
                                        Pastikan sudah login sebelum melakukan checkout.
                                    </li>
                                    <li data-id="Periksa kembali produk dan jumlah yang akan dibeli."
                                        data-en="Review the products and quantities you want to purchase.">
                                        Periksa kembali produk dan jumlah yang akan dibeli.
                                    </li>
                                    <li data-id="Ikuti instruksi pembayaran manual yang tersedia."
                                        data-en="Follow the available manual payment instructions.">
                                        Ikuti instruksi pembayaran manual yang tersedia.
                                    </li>
                                    <li data-id="Kirim bukti pembayaran jika diminta."
                                        data-en="Submit proof of payment if requested.">
                                        Kirim bukti pembayaran jika diminta.
                                    </li>
                                    <li data-id="Tunggu Admin memverifikasi pembayaran."
                                        data-en="Wait for the Admin to verify your payment.">
                                        Tunggu Admin memverifikasi pembayaran.
                                    </li>
                                </ol>
                            </div>
                        </details>

                        <!-- 8. RIWAYAT -->
                        <details class="help-item">
                            <summary data-id="08. Riwayat Pesanan" data-en="08. Order History">
                                08. Riwayat Pesanan
                            </summary>

                            <div class="help-description">
                                <ol>
                                    <li data-id="Buka menu Riwayat Pesanan pada sidebar."
                                        data-en="Open Order History in the sidebar.">
                                        Buka menu Riwayat Pesanan pada sidebar.
                                    </li>
                                    <li data-id="Periksa daftar transaksi yang pernah dilakukan."
                                        data-en="Review your previous transactions.">
                                        Periksa daftar transaksi yang pernah dilakukan.
                                    </li>
                                    <li data-id="Perhatikan status pesanan dan pembayaran."
                                        data-en="Check your order and payment status.">
                                        Perhatikan status pesanan dan pembayaran.
                                    </li>
                                </ol>
                            </div>
                        </details>

                        <!-- 9. BAHASA -->
                        <details class="help-item">
                            <summary data-id="09. Mengubah Bahasa" data-en="09. Changing Language">
                                09. Mengubah Bahasa
                            </summary>

                            <div class="help-description">
                                <p data-id="Pilih menu Bahasa pada sidebar untuk beralih antara Bahasa Indonesia dan English."
                                    data-en="Select Language in the sidebar to switch between Indonesian and English.">
                                    Pilih menu Bahasa pada sidebar untuk beralih antara Bahasa Indonesia dan English.
                                </p>
                            </div>
                        </details>

                        <!-- 10. BANTUAN -->
                        <details class="help-item">
                            <summary data-id="10. Menghubungi Admin" data-en="10. Contacting the Admin">
                                10. Menghubungi Admin
                            </summary>

                            <div class="help-description">
                                <p data-id="Jika mengalami kendala saat menggunakan E-commerce ATK, silakan hubungi Admin melalui saluran kontak resmi yang disediakan."
                                    data-en="If you experience issues using E-commerce ATK, please contact the Admin through the official contact channel provided.">
                                    Jika mengalami kendala saat menggunakan E-commerce ATK, silakan hubungi Admin
                                    melalui saluran kontak resmi yang disediakan.
                                </p>
                            </div>
                        </details>

                    </div>

                    <!-- FOOTER -->
                    <div class="help-footer">
                        <p data-id="Terima kasih telah menggunakan E-commerce ATK."
                            data-en="Thank you for using E-commerce ATK.">
                            Terima kasih telah menggunakan E-commerce ATK.
                        </p>
                    </div>

                </div>
            </div>

        </section>

    </main>

    <!-- BAR KERANJANG -->
    <section class="cart-bar">
        <button type="button" class="cart-button" id="openCart">
            🛒
        </button>

        <span id="cartCount">0 Produk</span>

        <p data-id="Pop up Keranjang" data-en="Shopping Cart Popup">
            Pop up Keranjang
        </p>

        <button class="cart-arrow">➜</button>
    </section>
    <div class="cart-popup" id="cartPopup">

        <div class="cart-popup-header">
            <h3>
                🛒 <span data-id="Keranjang Belanja" data-en="Shopping Cart">Keranjang Belanja</span>
            </h3>

            <button type="button" id="closeCart">✕</button>
        </div>

        <div class="cart-items" id="cartItems">
        </div>

        <div class="cart-popup-footer">

            <p>
                <span data-id="Subtotal:" data-en="Subtotal:">Subtotal:</span>
                <strong id="cartSubtotal">Rp 0</strong>
            </p>

            <button type="button" class="checkout-btn" data-id="Checkout" data-en="Checkout">
                Checkout
            </button>

        </div>

    </div>
    <script src="../asset/js/dashboardUser.js"></script>
    <script src="../asset/js/navbar.js"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</body>

</html>