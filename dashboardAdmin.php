<?php
require_once __DIR__ . '/auth.php';
wajibLogin([2]);
?>
<?php

// Mengambil role dari session login.
// Nanti kita sesuaikan dengan nama session pada proses login.
$role = $_SESSION['role_name'] ?? $_SESSION['role'] ?? 'Admin';
$role = strtolower(trim($role));

$isSuperAdmin = in_array($role, ['super admin', 'superadmin', '1']);

$namaAdmin = $_SESSION['nama'] ?? $_SESSION['username'] ?? 'Admin';

// Data sementara untuk tampilan.
// Nantinya akan diganti dengan query database.
$produk = [
    [
        'nama' => 'Pulpen Gel',
        'kategori' => 'Alat Tulis',
        'harga' => 3000,
        'stok' => 500,
        'gambar' => 'asset/gambar/produk/pulpen.jpg'
    ],
    [
        'nama' => 'Buku Tulis',
        'kategori' => 'Buku & Kertas',
        'harga' => 5000,
        'stok' => 7,
        'gambar' => 'asset/gambar/produk/buku.jpg'
    ],
    [
        'nama' => 'Pensil 2B',
        'kategori' => 'Alat Tulis',
        'harga' => 2500,
        'stok' => 0,
        'gambar' => 'asset/gambar/produk/pensil.jpg'
    ],
    [
        'nama' => 'Penggaris 30cm',
        'kategori' => 'Alat Tulis',
        'harga' => 4000,
        'stok' => 100,
        'gambar' => 'asset/gambar/produk/penggaris.jpg'
    ],
    [
        'nama' => 'Kertas HVS A4',
        'kategori' => 'Buku & Kertas',
        'harga' => 45000,
        'stok' => 50,
        'gambar' => 'asset/gambar/produk/kertas.jpg'
    ],
    [
        'nama' => 'Spidol Hitam',
        'kategori' => 'Alat Tulis',
        'harga' => 6000,
        'stok' => 3,
        'gambar' => 'asset/gambar/produk/spidol.jpg'
    ],
    [
        'nama' => 'Map Plastik',
        'kategori' => 'Arsip',
        'harga' => 2000,
        'stok' => 80,
        'gambar' => 'asset/gambar/produk/map.jpg'
    ],
    [
        'nama' => 'Stapler',
        'kategori' => 'Perlengkapan',
        'harga' => 15000,
        'stok' => 25,
        'gambar' => 'asset/gambar/produk/stapler.jpg'
    ]
];

$totalProduk = 100;
$stokMenipis = 7;
$stokHabis = 3;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - E-commerce ATK</title>

    <link rel="stylesheet" href="asset/css/dashboardAdmin.css">
    <link rel="stylesheet" href="asset/css/navbar.css">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>

<body>

    <!-- NAVBAR BERSAMA -->
    <?php
    $navbarRole = 'admin';
    include_once __DIR__ . '/layout/navbar.php';
    ?>

    <div class="admin-layout">

        <!-- SIDEBAR -->
        <aside class="admin-sidebar" id="adminSidebar">

            <div class="sidebar-header">
                <div class="sidebar-store">
                    <i class="ph ph-storefront"></i>
                    <span>Nama Toko</span>
                </div>

                <button type="button" class="sidebar-close" id="sidebarClose">
                    <i class="ph ph-x"></i>
                </button>
            </div>

            <nav class="sidebar-menu">

                <a href="dashboardAdmin.php" class="sidebar-link active">
                    <i class="ph ph-squares-four"></i>
                    <span>Dashboard</span>
                </a>

                <a href="#" class="sidebar-link">
                    <i class="ph ph-folders"></i>
                    <span>Kategori Produk</span>
                </a>

                <a href="#" class="sidebar-link">
                    <i class="ph ph-package"></i>
                    <span>Stok Barang</span>
                </a>

                <a href="#" class="sidebar-link">
                    <i class="ph ph-package-plus"></i>
                    <span>Barang Masuk</span>
                </a>

                <a href="#" class="sidebar-link">
                    <i class="ph ph-package-minus"></i>
                    <span>Barang Keluar</span>
                </a>

                <?php if ($isSuperAdmin): ?>
                    <div class="sidebar-divider"></div>

                    <a href="#" class="sidebar-link">
                        <i class="ph ph-users"></i>
                        <span>Kelola Akun Admin</span>
                    </a>

                    <a href="#" class="sidebar-link">
                        <i class="ph ph-chart-line-up"></i>
                        <span>Performa Penjualan</span>
                    </a>
                <?php endif; ?>

            </nav>

            <div class="sidebar-footer">
                <h4>About Us</h4>
                <p>Alamat Utama Kita</p>
                <p>Nomor Telepon</p>
                <p>Alamat Email Kita</p>
            </div>

        </aside>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- KONTEN UTAMA -->
        <main class="admin-content">

            <div class="admin-heading">
                <div>
                    <h1>Dashboard Admin</h1>
                    <p>Selamat datang kembali, <?= htmlspecialchars($namaAdmin) ?>!</p>
                </div>
            </div>

            <!-- STATISTIK -->
            <section class="admin-statistics">

                <div class="stat-card">
                    <div class="stat-heading">
                        <span>Total Produk</span>
                        <i class="ph ph-package"></i>
                    </div>
                    <h2><?= number_format($totalProduk, 0, ',', '.') ?></h2>
                    <p>Produk terdaftar</p>
                </div>

                <div class="stat-card warning">
                    <div class="stat-heading">
                        <span>Stok Menipis</span>
                        <i class="ph ph-warning-circle"></i>
                    </div>
                    <h2><?= number_format($stokMenipis, 0, ',', '.') ?></h2>
                    <p>Produk dengan stok ≤ 10</p>
                </div>

                <div class="stat-card danger">
                    <div class="stat-heading">
                        <span>Stok Habis</span>
                        <i class="ph ph-package"></i>
                    </div>
                    <h2><?= number_format($stokHabis, 0, ',', '.') ?></h2>
                    <p>Produk dengan stok 0</p>
                </div>

            </section>

            <!-- KATALOG PRODUK -->
            <section class="admin-products">

                <div class="product-section-heading">
                    <div>
                        <h2>Daftar Produk</h2>
                        <p>Kelola produk yang tersedia di toko.</p>
                    </div>

                    <button class="add-product-btn" type="button" id="addProductBtn">
                        <i class="ph ph-plus"></i>
                        Tambah Produk Baru
                    </button>
                </div>

                <!-- FILTER KATEGORI -->
                <div class="product-filter" id="productFilter">
                    <button class="filter-btn active" data-category="Semua">
                        Semua
                    </button>

                    <button class="filter-btn" data-category="Buku & Kertas">
                        Buku & Kertas
                    </button>

                    <button class="filter-btn" data-category="Alat Tulis">
                        Alat Tulis
                    </button>

                    <button class="filter-btn" data-category="Arsip">
                        Arsip
                    </button>

                    <button class="filter-btn" data-category="Perlengkapan">
                        Perlengkapan
                    </button>
                </div>

                <!-- PENCARIAN -->
                <div class="product-search">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="search" id="productSearch" placeholder="Cari nama produk...">
                </div>

                <!-- DAFTAR PRODUK -->
                <div class="product-grid" id="productGrid">

                    <?php foreach ($produk as $item): ?>

                        <article class="admin-product-card" data-name="<?= htmlspecialchars(strtolower($item['nama'])) ?>"
                            data-category="<?= htmlspecialchars($item['kategori']) ?>">

                            <div class="product-image">
                                <img src="<?= htmlspecialchars($item['gambar']) ?>"
                                    alt="<?= htmlspecialchars($item['nama']) ?>"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                                <div class="image-placeholder">
                                    <i class="ph ph-image"></i>
                                    <span>Gambar Produk</span>
                                </div>
                            </div>

                            <div class="product-info">
                                <span class="product-category">
                                    <?= htmlspecialchars($item['kategori']) ?>
                                </span>

                                <h3><?= htmlspecialchars($item['nama']) ?></h3>

                                <p class="product-price">
                                    Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                                </p>

                                <p
                                    class="product-stock <?= $item['stok'] == 0 ? 'stock-empty' : ($item['stok'] <= 10 ? 'stock-low' : '') ?>">
                                    Stok: <?= number_format($item['stok'], 0, ',', '.') ?>
                                </p>
                            </div>

                            <div class="product-actions">
                                <button type="button" class="edit-product-btn">
                                    <i class="ph ph-pencil-simple"></i>
                                    Edit
                                </button>

                                <button type="button" class="delete-product-btn">
                                    <i class="ph ph-eye-slash"></i>
                                    Nonaktifkan
                                </button>
                            </div>

                        </article>

                    <?php endforeach; ?>

                </div>

                <div class="product-empty" id="productEmpty">
                    Produk tidak ditemukan.
                </div>

            </section>

        </main>

    </div>

    <script src="asset/js/navbar.js"></script>
    <script src="asset/js/dashboardAdmin.js"></script>

</body>

</html>