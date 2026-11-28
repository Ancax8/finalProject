
<?php
// ==========================================
// LAPORAN PENJUALAN SUPER ADMIN
// ==========================================

// Data transaksi akan dihubungkan ke database
// setelah struktur tabel transaksi ditentukan.
?>

<div class="laporan-container">

    <!-- HEADER -->
    <div class="laporan-header">
        <div>
            <h2>Laporan Penjualan</h2>
            <p>Pantau aktivitas dan performa penjualan E-commerce ATK.</p>
        </div>
    </div>

    <!-- FILTER PERIODE -->
    <div class="laporan-filter">
        <div class="filter-group">
            <label for="tanggalMulai">Tanggal Mulai</label>
            <input type="date" id="tanggalMulai">
        </div>

        <div class="filter-group">
            <label for="tanggalAkhir">Tanggal Akhir</label>
            <input type="date" id="tanggalAkhir">
        </div>

        <button type="button" class="btn-filter">
            <i class="ph ph-funnel"></i>
            Terapkan Filter
        </button>
    </div>

    <!-- RINGKASAN PENJUALAN -->
    <div class="laporan-statistik">

        <div class="statistik-card">
            <div class="statistik-icon biru">
                <i class="ph ph-shopping-cart"></i>
            </div>
            <div>
                <p>Total Transaksi</p>
                <h3>—</h3>
            </div>
        </div>

        <div class="statistik-card">
            <div class="statistik-icon hijau">
                <i class="ph ph-money"></i>
            </div>
            <div>
                <p>Total Penjualan</p>
                <h3>—</h3>
            </div>
        </div>

        <div class="statistik-card">
            <div class="statistik-icon ungu">
                <i class="ph ph-package"></i>
            </div>
            <div>
                <p>Barang Terjual</p>
                <h3>—</h3>
            </div>
        </div>

    </div>

    <!-- TABEL TRANSAKSI -->
    <div class="laporan-tabel-container">

        <div class="laporan-tabel-header">
            <div>
                <h3>Riwayat Penjualan</h3>
                <p>Daftar transaksi penjualan produk.</p>
            </div>
        </div>

        <div class="table-container">
            <table class="table-produk">

                <thead>
                    <tr>
                        <th>No.</th>
                        <th>ID Transaksi</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td colspan="6" class="empty-data">
                            Data transaksi belum tersedia.
                            <br>
                            <small>
                                Hubungkan laporan dengan tabel transaksi
                                untuk menampilkan data penjualan.
                            </small>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

    </div>

</div>
