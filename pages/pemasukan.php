
<?php
session_start();

$namaAkuntan = $_SESSION['nama'] ?? $_SESSION['username'] ?? 'Akuntan';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pemasukan - E-commerce ATK</title>

    <link rel="stylesheet" href="../asset/css/navbar.css">
    <link rel="stylesheet" href="../asset/css/dashboardAkuntan.css">
    <link rel="stylesheet" href="../asset/css/pemasukan.css">

    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

    <?php
    $navbarRole = 'akuntan';
    include_once __DIR__ . '/../layout/navbar.php';
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

                <a href="../dashboardAkuntan.php">
                    <i class="ph ph-squares-four"></i>
                    <span>Dashboard</span>
                </a>

                <a href="pemasukan.php" class="active">
                    <i class="ph ph-trend-up"></i>
                    <span>Pemasukan</span>
                </a>

                <a href="pengeluaran.php">
                    <i class="ph ph-trend-down"></i>
                    <span>Pengeluaran</span>
                </a>

                <a href="transaksiKeuangan.php">
                    <i class="ph ph-receipt"></i>
                    <span>Transaksi Keuangan</span>
                </a>

                <a href="laporanKeuangan.php">
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

        <!-- KONTEN -->
        <main class="akuntan-content">

            <div class="akuntan-heading">
                <div>
                    <h1>Data Pemasukan</h1>
                    <p>Kelola dan pantau seluruh pemasukan toko.</p>
                </div>

                <div class="akuntan-date">
                    <i class="ph ph-calendar-blank"></i>
                    <span><?= date('d F Y'); ?></span>
                </div>
            </div>

            <!-- RINGKASAN -->
            <section class="income-summary">

                <div class="income-summary-card">
                    <div class="income-summary-icon">
                        <i class="ph ph-wallet"></i>
                    </div>

                    <div>
                        <p>Total Pemasukan</p>
                        <h2>Rp 0</h2>
                        <small>Seluruh pemasukan tercatat</small>
                    </div>
                </div>

                <div class="income-summary-card">
                    <div class="income-summary-icon">
                        <i class="ph ph-calendar-check"></i>
                    </div>

                    <div>
                        <p>Pemasukan Bulan Ini</p>
                        <h2>Rp 0</h2>
                        <small>Akumulasi bulan berjalan</small>
                    </div>
                </div>

                <div class="income-summary-card">
                    <div class="income-summary-icon">
                        <i class="ph ph-receipt"></i>
                    </div>

                    <div>
                        <p>Jumlah Transaksi</p>
                        <h2>0</h2>
                        <small>Total transaksi pemasukan</small>
                    </div>
                </div>

            </section>

            <!-- TABEL PEMASUKAN -->
            <section class="income-table-panel">

                <div class="income-table-header">
                    <div>
                        <h3>Riwayat Pemasukan</h3>
                        <p>Daftar seluruh transaksi pemasukan toko.</p>
                    </div>

                    <button
                        type="button"
                        class="income-add-button"
                        id="btnTambahPemasukan"
                    >
                        <i class="ph ph-plus"></i>
                        Tambah Pemasukan
                    </button>
                </div>

                <!-- FILTER -->
                <div class="income-filter">

                    <div class="income-filter-group">
                        <label for="filterTanggal">Tanggal</label>
                        <input type="date" id="filterTanggal">
                    </div>

                    <div class="income-filter-group">
                        <label for="filterKategori">Kategori</label>
                        <select id="filterKategori">
                            <option value="">Semua Kategori</option>
                            <option value="Penjualan">Penjualan</option>
                            <option value="Modal">Modal</option>
                            <option value="Pendapatan Lainnya">Pendapatan Lainnya</option>
                        </select>
                    </div>

                    <button type="button" id="resetFilterIncome">
                        <i class="ph ph-arrow-counter-clockwise"></i>
                        Reset Filter
                    </button>

                </div>

                <!-- TABEL -->
                <div class="income-table-wrapper">
                    <table class="income-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Kategori</th>
                                <th>Nominal</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>

                        <tbody id="incomeTableBody">
                            <tr>
                                <td colspan="6" class="income-empty">
                                    <i class="ph ph-receipt"></i>
                                    <strong>Belum Ada Data Pemasukan</strong>
                                    <span>Transaksi pemasukan yang ditambahkan akan tampil di sini.</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </section>

        </main>
    </div>

    <!-- MODAL TAMBAH PEMASUKAN -->
    <div class="finance-modal" id="modalPemasukan">
        <div class="finance-modal-content">

            <div class="finance-modal-header">
                <div>
                    <h2>Tambah Pemasukan</h2>
                    <p>Catat pemasukan keuangan toko.</p>
                </div>

                <button
                    type="button"
                    class="modal-close"
                    data-close="modalPemasukan"
                >
                    &times;
                </button>
            </div>

            <form id="formPemasukan">

                <div class="finance-form-group">
                    <label for="tanggalPemasukan">Tanggal Transaksi</label>
                    <input
                        type="date"
                        id="tanggalPemasukan"
                        name="tanggal"
                        required
                    >
                </div>

                <div class="finance-form-group">
                    <label for="kategoriPemasukan">Kategori Pemasukan</label>
                    <select
                        id="kategoriPemasukan"
                        name="kategori"
                        required
                    >
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
                        maxlength="150"
                        placeholder="Contoh: Penjualan alat tulis"
                        required
                    >
                </div>

                <div class="finance-form-group">
                    <label for="catatanPemasukan">Catatan Tambahan (Opsional)</label>
                    <textarea
                        id="catatanPemasukan"
                        name="catatan"
                        rows="3"
                        placeholder="Tambahkan catatan..."
                    ></textarea>
                </div>

                <div class="finance-modal-actions">
                    <button
                        type="button"
                        class="btn-cancel"
                        data-close="modalPemasukan"
                    >
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

    <script src="../asset/js/dashboardAkuntan.js"></script>
    <script src="../asset/js/pemasukan.js"></script>

</body>
</html>
