
<?php

include "connect.php";


// ==========================================
// PROSES TAMBAH PRODUK
// ==========================================

if (isset($_POST['tambah'])) {

    $barang = $_POST['barang'];
    $gambar = $_FILES['gambar']['name'];
    $jumlah = $_POST['quantity'];
    $harga = $_POST['harga'];
    $tmp = $_FILES['gambar']['tmp_name'];

    $folder = "asset/gambar/produk/" . $gambar;

    if (move_uploaded_file($tmp, $folder)) {

        $tambah = "INSERT INTO barang (barang, gambar, jumlah, harga)
                   VALUES ('$barang', '$gambar', '$jumlah', '$harga')";

        if ($db->query($tambah)) {

            $_SESSION['toast'] = [
                "icon" => "success",
                "title" => "Produk Berhasil Ditambahkan!"
            ];

        } else {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Produk Gagal Ditambahkan!"
            ];
        }

    } else {

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Upload Gambar Gagal!"
        ];
    }

    // Kembali ke halaman index
    header("Location: index.php");
    exit;
}


// ==========================================
// MENGAMBIL NOTIFIKASI SESSION
// ==========================================

$toast = $_SESSION['toast'] ?? null;

// Hapus agar notifikasi hanya muncul sekali
unset($_SESSION['toast']);

?>

?>

<!-- FORM TAMBAH PRODUK -->
<div class="feature-form">

    <h2>Tambah Produk</h2>

    <form action="" method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Nama Barang</label>

            <input
                type="text"
                name="nama_barang"
                placeholder="Masukkan nama barang"
                required
            >
        </div>

        <div class="form-group">
            <label>Harga Barang</label>

            <input
                type="number"
                name="harga_barang"
                placeholder="Masukkan harga barang"
                min="0"
                required
            >
        </div>

        <div class="form-group">
            <label>Stok Barang</label>

            <input
                type="number"
                name="stok_barang"
                placeholder="Masukkan stok barang"
                min="0"
                required
            >
        </div>

        <div class="form-group">
            <label>Kategori</label>

            <select name="kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Alat Tulis">Alat Tulis</option>
                <option value="Buku dan Kertas">Buku dan Kertas</option>
                <option value="Perlengkapan Sekolah">Perlengkapan Sekolah</option>
                <option value="Perlengkapan Kantor">Perlengkapan Kantor</option>
                <option value="Lainnya">Lainnya</option>
            </select>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>

            <textarea
                name="deskripsi"
                rows="4"
                placeholder="Masukkan deskripsi produk..."
                required
            ></textarea>
        </div>

        <div class="form-group">
            <label>Gambar Produk</label>

            <input
                type="file"
                name="gambar"
                accept="image/jpeg,image/png,image/webp"
                required
            >
        </div>

        <button type="submit" name="tambah_produk">
            Tambah Produk
        </button>

    </form>

</div>
