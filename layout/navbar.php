<?php
// Role navbar ditentukan oleh halaman yang memanggil file ini.
// Jika tidak ditentukan, navbar tetap menggunakan tampilan User.
$navbarRole = $navbarRole ?? 'user';
?>

<?php if (in_array($navbarRole, ['admin', 'akuntan'])): ?>

    <!-- NAVBAR ADMIN -->
    <div class="navbar admin-navbar" data-navbar-role="admin">

        <div class="admin-navbar-brand">
            <span>E-commerce ATK</span>
        </div>



        <button type="button" class="admin-menu-toggle" id="<?= $navbarRole === 'akuntan' ? 'akuntanMenu' : 'adminMenu'; ?>"
            aria-label="Buka menu" aria-expanded="false">
            <i class="ph ph-list"></i>
        </button>

    </div>

<?php else: ?>

    <!-- NAVBAR USER: PERTAHANKAN FUNGSI USER -->
    <div class="navbar user-navbar" data-navbar-role="user">

        <div class="profile">
            <img src="../asset/gambar/bahan/profile.png" alt="#" id="profile" name="profile">
            <label id="status"></label>
        </div>

        <div class="pencarian">
            <input type="search" name="pencarian" id="pencarian" placeholder="cari produk..." data-id="cari produk..."
                data-en="search products...">
            <img src="../asset/gambar/bahan/pencarian.png" alt="cari">
        </div>

        <div class="main">
            <ul id="main">
                <li>
                    <a href="profile.php" id="profileMenu">
                        <i class="ph ph-user-circle"></i>
                        <span data-id="Profile" data-en="Profile">Profile</span>
                    </a>
                </li>

                <li>
                    <a href="#" id="favoritMenu">
                        <i class="ph ph-heart"></i>
                        <span data-id="Favorit" data-en="Favorites">Favorit</span>
                    </a>
                </li>

                <li>
                    <a href="riwayatPesanan.php">
                        <i class="ph ph-receipt"></i>
                        <span data-id="Riwayat Pesanan" data-en="Order History">Riwayat Pesanan</span>
                    </a>
                </li>

                <li>
                    <a href="#" id="languageMenu">
                        <i class="ph ph-globe"></i>
                        <span data-id="Bahasa" data-en="Language">Bahasa</span>
                    </a>
                </li>

                <li>
                    <a href="#" id="helpMenu">
                        <i class="ph ph-question"></i>
                        <span data-id="Bantuan" data-en="Help">Bantuan</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="icon-list" id="menu">
            <i class="ph ph-list"></i>
        </div>

    </div>

<?php endif; ?>