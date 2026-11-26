<?php
require_once __DIR__ . '/auth.php';
wajibLogin([3]);
?>
<?php

// Proteksi halaman: sementara pastikan hanya role Akuntan yang masuk.
// Sesuaikan nilai role dengan isi session login di proyekmu.
$role = strtolower(trim($_SESSION['role_name'] ?? $_SESSION['role'] ?? ''));

if ($role !== 'akuntan') {
    // Saat integrasi login, arahkan ke halaman login atau dashboard sesuai role.
    // Untuk sementara, jangan aktifkan redirect sebelum nilai session dipastikan.
}

$namaAkuntan = $_SESSION['nama'] ?? $_SESSION['username'] ?? 'Akuntan';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Akuntan - E-commerce ATK</title>

    <link rel="stylesheet" href="asset/css/dashboardAkuntan.css">
    <link rel="stylesheet" href="asset/css/navbar.css">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

    <?php
    $navbarRole = 'akuntan';
    include_once __DIR__ . '/layout/navbar.php';
    ?>

    <div class="akuntan-layout">

        <!-- SIDEBAR -->
        <aside class="akuntan-sidebar" id="akuntanSidebar">

            <div class="akuntan-sidebar-header">
                <div class="akuntan-store">
                    <i class="ph ph-storefront"></i>
                    <span>Nama Toko</span>
                </div>

                <button type="button" id="akuntanSidebarClose">
                    <i class="ph ph-x"></i>
                </button>
            </div>

            <nav class="akuntan-sidebar-menu">

                <a href="dashboardAkuntan.php" class="active">
                    <i class="ph ph-squares-four"></i>
                    <span>Dashboard</span>
                </a>

                <a href="pages/pemasukan.php">
                    <i class="ph ph-trend-up"></i>
                    <span>Pemasukan</span>
                </a>

                <a href="pages/pengeluaran.php">
                    <i class="ph ph-trend-down"></i>
                    <span>Pengeluaran</span>
                </a>

                <a href="pages/transaksiKeuangan.php">
                    <i class="ph ph-receipt"></i>
                    <span>Transaksi Keuangan</span>
                </a>

                <a href="pages/laporanKeuangan.php">
                    <i class="ph ph-chart-line"></i>
                    <span>Laporan Keuangan</span>
                </a>

            </nav>

            <div class="akuntan-sidebar-footer">
                <h4>About Us</h4>
                <p>Alamat Utama Kita</p>
                <p>Nomor Telepon</p>
                <p>Alamat Email Kita</p>
            </div>

        </aside>

        <div class="akuntan-overlay" id="akuntanOverlay"></div>

        <!-- KONTEN DASHBOARD -->
        <main class="akuntan-content">

            <div class="akuntan-heading">
                <div>
                    <h1>Dashboard Akuntan</h1>
                    <p>Selamat datang, <?= htmlspecialchars($namaAkuntan); ?>!</p>
                </div>

                <div class="akuntan-date">
                    <i class="ph ph-calendar-blank"></i>
                    <span><?= date('d F Y'); ?></span>
                </div>
            </div>

            <!-- RINGKASAN -->
            <section class="akuntan-summary">

                <div class="summary-card">
                    <div class="summary-icon blue">
                        <i class="ph ph-receipt"></i>
                    </div>
                    <div>
                        <p>Total Transaksi</p>
                        <h2>100</h2>
                        <small>Transaksi tercatat</small>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon green">
                        <i class="ph ph-trend-up"></i>
                    </div>
                    <div>
                        <p>Total Pemasukan</p>
                        <h2>Rp 23.000.000</h2>
                        <small>Akumulasi pemasukan</small>
                    </div>
                </div>

                <div class="summary-card">
                    <div class="summary-icon red">
                        <i class="ph ph-trend-down"></i>
                    </div>
                    <div>
                        <p>Total Pengeluaran</p>
                        <h2>Rp 10.000.000</h2>
                        <small>Akumulasi pengeluaran</small>
                    </div>
                </div>

            </section>

            <!-- GRAFIK DAN DETAIL -->
            <section class="akuntan-main-grid">

                <div class="akuntan-panel">
                    <div class="panel-heading">
                        <h3>Grafik Keuangan</h3>
                        <select id="filterGrafik">
                            <option value="bulan">Bulan Ini</option>
                            <option value="tahun">Tahun Ini</option>
                        </select>
                    </div>

                    <div class="chart-placeholder">
                        <i class="ph ph-chart-line-up"></i>
                        <p>Grafik keuangan akan ditampilkan di sini</p>
                    </div>
                </div>

                <div class="akuntan-panel">
                    <div class="panel-heading">
                        <h3>Detail Transaksi</h3>
                        <a href="pages/transaksiKeuangan.php">Lihat Semua</a>
                    </div>

                    <div class="transaction-placeholder">
                        <i class="ph ph-receipt"></i>
                        <p>Belum ada data transaksi yang terhubung</p>
                    </div>
                </div>

            </section>

            <!-- TOMBOL AKSI -->
            <section class="akuntan-actions">

                <button type="button" class="action-income" id="btnTambahPemasukan">
                    <i class="ph ph-plus"></i>
                    Tambah Pemasukan
                </button>

                <button type="button" class="action-expense" id="btnTambahPengeluaran">
                    <i class="ph ph-minus"></i>
                    Tambah Pengeluaran
                </button>

                <button type="button" class="action-report" id="btnCetakLaporan">
                    <i class="ph ph-printer"></i>
                    Cetak Laporan
                </button>

            </section>

        </main>

<!-- MODAL TAMBAH PEMASUKAN -->
<div class="finance-modal" id="modalPemasukan">
    <div class="finance-modal-content">
        <div class="finance-modal-header">
            <div>
                <h2>Tambah Pemasukan</h2>
                <p>Catat pemasukan keuangan toko</p>
            </div>
            <button type="button" class="modal-close" data-close="modalPemasukan">
                &times;
            </button>
        </div>

        <form id="formPemasukan">
            <div class="finance-form-group">
                <label for="tanggalPemasukan">Tanggal Transaksi</label>
                <input type="date" id="tanggalPemasukan" name="tanggal" required>
            </div>

            <div class="finance-form-group">
                <label for="kategoriPemasukan">Kategori Pemasukan</label>
                <select id="kategoriPemasukan" name="kategori" required>
                    <option value="">Pilih kategori</option>
                    <option value="Penjualan">Penjualan</option>
                    <option value="Modal">Modal</option>
                    <option value="Pendapatan Lainnya">Pendapatan Lainnya</option>
                </select>
            </div>

            <div class="finance-form-group">
                <label for="nominalPemasukan">Nominal Pemasukan</label>
                <div class="finance-input-rupiah">
                    <span>Rp</span>
                    <input
                        type="number"
                        id="nominalPemasukan"
                        name="nominal"
                        min="1"
                        step="1"
                        placeholder="Contoh: 500000"
                        required
                    >
                </div>
            </div>

            <div class="finance-form-group">
                <label for="keteranganPemasukan">Keterangan</label>
                <input
                    type="text"
                    id="keteranganPemasukan"
                    name="keterangan"
                    placeholder="Contoh: Penjualan alat tulis"
                    maxlength="150"
                    required
                >
            </div>

            <div class="finance-form-group">
                <label for="catatanPemasukan">Catatan Tambahan (Opsional)</label>
                <textarea
                    id="catatanPemasukan"
                    name="catatan"
                    rows="3"
                    placeholder="Tambahkan catatan jika diperlukan..."
                ></textarea>
            </div>

            <div class="finance-modal-actions">
                <button type="button" class="btn-cancel" data-close="modalPemasukan">
                    Batal
                </button>
                <button type="submit" class="btn-save income-save">
                    <i class="ph ph-floppy-disk"></i>
                    Simpan Pemasukan
                </button>
            </div>
        </form>
    </div>
</div>


<!-- MODAL TAMBAH PENGELUARAN -->
<div class="finance-modal" id="modalPengeluaran">
    <div class="finance-modal-content">
        <div class="finance-modal-header">
            <div>
                <h2>Tambah Pengeluaran</h2>
                <p>Catat pengeluaran keuangan toko</p>
            </div>
            <button type="button" class="modal-close" data-close="modalPengeluaran">
                &times;
            </button>
        </div>

        <form id="formPengeluaran">
            <div class="finance-form-group">
                <label for="tanggalPengeluaran">Tanggal Transaksi</label>
                <input type="date" id="tanggalPengeluaran" name="tanggal" required>
            </div>

            <div class="finance-form-group">
                <label for="kategoriPengeluaran">Kategori Pengeluaran</label>
                <select id="kategoriPengeluaran" name="kategori" required>
                    <option value="">Pilih kategori</option>
                    <option value="Pembelian Stok">Pembelian Stok</option>
                    <option value="Operasional">Operasional</option>
                    <option value="Gaji">Gaji</option>
                    <option value="Pengiriman">Pengiriman</option>
                    <option value="Pengeluaran Lainnya">Pengeluaran Lainnya</option>
                </select>
            </div>

            <div class="finance-form-group">
                <label for="nominalPengeluaran">Nominal Pengeluaran</label>
                <div class="finance-input-rupiah">
                    <span>Rp</span>
                    <input
                        type="number"
                        id="nominalPengeluaran"
                        name="nominal"
                        min="1"
                        step="1"
                        placeholder="Contoh: 250000"
                        required
                    >
                </div>
            </div>

            <div class="finance-form-group">
                <label for="keteranganPengeluaran">Keterangan</label>
                <input
                    type="text"
                    id="keteranganPengeluaran"
                    name="keterangan"
                    placeholder="Contoh: Pembelian stok buku tulis"
                    maxlength="150"
                    required
                >
            </div>

            <div class="finance-form-group">
                <label for="catatanPengeluaran">Catatan Tambahan (Opsional)</label>
                <textarea
                    id="catatanPengeluaran"
                    name="catatan"
                    rows="3"
                    placeholder="Tambahkan catatan jika diperlukan..."
                ></textarea>
            </div>

            <div class="finance-modal-actions">
                <button type="button" class="btn-cancel" data-close="modalPengeluaran">
                    Batal
                </button>
                <button type="submit" class="btn-save expense-save">
                    <i class="ph ph-floppy-disk"></i>
                    Simpan Pengeluaran
                </button>
            </div>
        </form>
    </div>
</div>

    </div>

    <script src="asset/js/navbar.js"></script>
    <script src="asset/js/dashboardAkuntan.js"></script>

</body>
</html>
