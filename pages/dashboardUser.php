<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User | Marketplace ATK</title>

    <!-- CSS Dashboard User -->
    <link rel="stylesheet" href="../asset/css/dashboardUser.css">
</head>

<body>
    <?php include "../layout/navbar.php"; ?>
  

    <!-- KONTEN UTAMA -->
    <main class="dashboard-container">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="profile-menu">
                <h3>Nama Toko</h3>

                <nav>
                    <a href="#">Profil</a>
                    <a href="#">Favorit</a>
                    <a href="#">Riwayat Pesanan</a>
                    <a href="#">Ubah Password</a>
                    <a href="#">Language</a>
                    <a href="#">Help</a>
                </nav>
            </div>

            <div class="about-us">
                <h4>About Us</h4>
                <p>Alamat Utama Kita</p>
                <p>Nomor Telepon</p>
                <p>Alamat Email</p>
            </div>
        </aside>

        <!-- AREA PRODUK -->
        <section class="main-content">

            <!-- BANNER PRODUK -->
            <section class="banner">
                <h2>Banner Produk</h2>
            </section>

            <!-- KATEGORI -->
            <section class="categories">
                <h3>Kategori</h3>

                <div class="category-list">
                    <button class="category-btn active">Semua</button>
                    <button class="category-btn">Kategori</button>
                    <button class="category-btn">Kategori</button>
                    <button class="category-btn">Kategori</button>
                    <button class="category-btn">Kategori</button>
                </div>
            </section>

            <!-- KATALOG PRODUK -->

            <section class="product-catalog">
                <h3>Produk</h3>

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

                            <button class="favorite-btn" type="button">
                                ☆
                            </button>

                            <div class="product-image">
                                Gambar Produk
                            </div>

                            <div class="product-info">
                                <h4><?= htmlspecialchars($item[0]) ?></h4>
                                <p><?= htmlspecialchars($item[1]) ?></p>
                                <span>Rp <?= htmlspecialchars($item[2]) ?></span>
                            </div>

                            <button class="buy-btn" type="button" data-name="<?= htmlspecialchars($item[0]) ?>"
                                data-category="<?= htmlspecialchars($item[1]) ?>"
                                data-price="<?= htmlspecialchars($item[2]) ?>">
                                Beli
                            </button>
                        </div>
                    <?php } ?>
                </div>

                <!-- NAVIGASI HALAMAN -->
                <div class="pagination">
                    <button type="button" disabled>Sebelumnya</button>
                    <button type="button" class="page-number active">1</button>
                    <button type="button" class="page-number">2</button>
                    <button type="button" class="page-number">3</button>
                    <button type="button">Berikutnya</button>
                </div>

            </section>


        </section>

    </main>

    <!-- BAR KERANJANG -->
    <section class="cart-bar">
        <button type="button" class="cart-button" id="openCart">
            🛒
        </button>

        <span id="cartCount">0 Produk</span>

        <p>Pop up Keranjang</p>

        <button class="cart-arrow">➜</button>
    </section>
    <!-- POPUP KERANJANG -->
    <!-- POPUP KERANJANG -->
    <div class="cart-popup" id="cartPopup">

        <div class="cart-popup-header">
            <h3>🛒 Keranjang Belanja</h3>

            <button type="button" id="closeCart">✕</button>
        </div>

        <div class="cart-items" id="cartItems">
        </div>

        <div class="cart-popup-footer">

            <p>
                Subtotal:
                <strong id="cartSubtotal">Rp 0</strong>
            </p>

            <button type="button" class="checkout-btn">
                Checkout
            </button>

        </div>

    </div>
    <script src="../asset/js/dashboardUser.js"></script>
</body>

</html>