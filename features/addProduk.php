
<?php
// Ambil kategori yang berstatus aktif
$dataKategori = $db->query("
    SELECT id_kategori, nama_kategori
    FROM kategori
    WHERE status = 'aktif'
    ORDER BY nama_kategori ASC
");
?>

<!-- ==========================================
     FORM TAMBAH PRODUK
========================================== -->

<div class="form-produk">

    <div class="form-header">
        <h2>Tambah Produk</h2>
        <p>Masukkan informasi produk baru ke dalam katalog.</p>
    </div>

    <form
        action="dashboardSuper.php"
        method="POST"
        enctype="multipart/form-data">

        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input
                type="text"
                id="nama_barang"
                name="nama_barang"
                placeholder="Masukkan nama barang"
                required>
        </div>

        <div class="form-group">
            <label for="gambar">Gambar Produk</label>
            <input
                type="file"
                id="gambar"
                name="gambar"
                accept=".jpg,.jpeg,.png,.webp"
                required>
            <small>Format gambar JPG, JPEG, PNG, atau WEBP.</small>
        </div>

        <div class="form-group">
            <label for="stok_barang">Jumlah Stok</label>
            <input
                type="number"
                id="stok_barang"
                name="stok_barang"
                placeholder="Masukkan jumlah stok"
                min="0"
                required>
        </div>

        <div class="form-group">
            <label for="harga_barang">Harga Barang</label>
            <input
                type="number"
                id="harga_barang"
                name="harga_barang"
                placeholder="Masukkan harga barang"
                min="0"
                step="1"
                required>
        </div>

        <div class="form-group">
            <label for="kategori">Kategori Barang</label>

            <select
                id="kategori"
                name="kategori"
                required>

                <option value="">-- Pilih Kategori --</option>

                <?php if ($dataKategori): ?>
                    <?php while ($kategori = $dataKategori->fetch_assoc()): ?>
                        <option value="<?= (int) $kategori['id_kategori'] ?>">
                            <?= htmlspecialchars($kategori['nama_kategori']) ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>

            </select>
        </div>

        <div class="form-group">
            <label for="deskripsi">Deskripsi Produk</label>
            <textarea
                id="deskripsi"
                name="deskripsi"
                placeholder="Masukkan deskripsi produk"
                required></textarea>
        </div>

        <button
            type="submit"
            name="tambah"
            class="btn-simpan">

            <i class="ph ph-floppy-disk"></i>
            Simpan Produk

        </button>

    </form>

</div>
