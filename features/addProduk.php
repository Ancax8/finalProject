
<?php

// ==========================================
// PROSES TAMBAH PRODUK
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {

    $nama      = trim($_POST['nama_barang'] ?? '');
    $stok      = (int) ($_POST['stok_barang'] ?? 0);
    $harga     = (int) ($_POST['harga_barang'] ?? 0);
    $kategori  = trim($_POST['kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    $gambar = $_FILES['gambar'] ?? null;

    // Validasi input
    if (
        $nama === '' ||
        $stok < 0 ||
        $harga <= 0 ||
        $kategori === '' ||
        $deskripsi === '' ||
        !$gambar ||
        $gambar['error'] !== UPLOAD_ERR_OK
    ) {

        $_SESSION['toast'] = [
            'icon' => 'error',
            'title' => 'Lengkapi semua data produk!'
        ];

        header("Location: dashboardSuper.php#produk");
        exit;
    }

    // Validasi tipe gambar
    $tipeGambar = mime_content_type($gambar['tmp_name']);

    $tipeDiizinkan = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (!isset($tipeDiizinkan[$tipeGambar])) {

        $_SESSION['toast'] = [
            'icon' => 'error',
            'title' => 'Format gambar harus JPG, PNG, atau WEBP!'
        ];

        header("Location: dashboardSuper.php#produk");
        exit;
    }

    // Nama gambar unik
    $namaGambar = uniqid('produk_', true) . '.' .
                  $tipeDiizinkan[$tipeGambar];

    // Lokasi penyimpanan gambar
    $folder = __DIR__ . '/../asset/gambar/produk/';

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $tujuan = $folder . $namaGambar;

    // Upload gambar
    if (move_uploaded_file($gambar['tmp_name'], $tujuan)) {

        // INSERT DATABASE
        $query = $db->prepare(
            "INSERT INTO barang
            (nama_barang, gambar, stok_barang, harga_barang, kategori, deskripsi)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        if (!$query) {

            unlink($tujuan);

            $_SESSION['toast'] = [
                'icon' => 'error',
                'title' => 'Query database gagal dibuat!'
            ];

        } else {

            $query->bind_param(
                "ssiiss",
                $nama,
                $namaGambar,
                $stok,
                $harga,
                $kategori,
                $deskripsi
            );

            if ($query->execute()) {

                $_SESSION['toast'] = [
                    'icon' => 'success',
                    'title' => 'Produk berhasil ditambahkan!'
                ];

            } else {

                unlink($tujuan);

                $_SESSION['toast'] = [
                    'icon' => 'error',
                    'title' => 'Produk gagal ditambahkan: ' . $query->error
                ];
            }

            $query->close();
        }

    } else {

        $_SESSION['toast'] = [
            'icon' => 'error',
            'title' => 'Upload gambar gagal!'
        ];
    }

    header("Location: dashboardSuper.php#produk");
    exit;
}

?>

<!-- =====================================
     FORM TAMBAH PRODUK
====================================== -->

<div class="form-produk">

    <h2>Tambah Produk</h2>

    <form action="dashboardSuper.php#produk"
          method="POST"
          enctype="multipart/form-data">

        <div class="form-group">
            <label>Nama Barang</label>
            <input
                type="text"
                name="nama_barang"
                placeholder="Masukkan nama barang"
                maxlength="100"
                required>
        </div>

        <div class="form-group">
            <label>Gambar Produk</label>
            <input
                type="file"
                name="gambar"
                accept="image/jpeg,image/png,image/webp"
                required>
        </div>

        <div class="form-group">
            <label>Stok Barang</label>
            <input
                type="number"
                name="stok_barang"
                min="0"
                placeholder="Masukkan jumlah stok"
                required>
        </div>

        <div class="form-group">
            <label>Harga Barang</label>
            <input
                type="number"
                name="harga_barang"
                min="1"
                placeholder="Masukkan harga"
                required>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <input
                type="text"
                name="kategori"
                maxlength="100"
                placeholder="Contoh: Alat Tulis"
                required>
        </div>

        <div class="form-group">
            <label>Deskripsi</label>
            <textarea
                name="deskripsi"
                maxlength="500"
                placeholder="Deskripsi produk"
                required></textarea>
        </div>

        <button type="submit" name="tambah" class="btn-simpan">
            Simpan Produk
        </button>

    </form>

</div>
