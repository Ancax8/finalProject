
<?php

// Aktifkan output buffering agar redirect PHP tetap aman
ob_start();

include "connect.php";

// Pastikan session aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// INCLUDE FITUR TAMBAH PRODUK
// ==========================================

ob_start();
include "features/addProduk.php";
$formProduk = ob_get_clean();

// ==========================================
// AMBIL NOTIFIKASI
// ==========================================

$toastMessage = $_SESSION['toast'] ?? null;
unset($_SESSION['toast']);

// ==========================================
// AMBIL DATA PRODUK
// ==========================================

$dataProduk = $db->query(
    "SELECT * FROM barang ORDER BY id_barang DESC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Super Admin</title>

    <link rel="stylesheet" href="asset/css/dashboardSuper.css">
</head>

<body>

    <main class="dashboard">

        <div class="dashboard-header">

            <div>
                <h1>Dashboard Super Admin</h1>
                <p>Kelola produk E-commerce ATK</p>
            </div>

            <button
                type="button"
                class="btn-add"
                onclick="openModal('modalAddProduk')">

                <span>+</span>
                Tambah Produk

            </button>

        </div>


        <!-- ==================================
             TABEL PRODUK
        =================================== -->

        <section class="produk-section" id="produk">

            <h2>Daftar Barang</h2>

            <div class="table-container">

                <table class="table-produk">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>
                            <th>Harga</th>
                            <th>Kategori</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if ($dataProduk && $dataProduk->num_rows > 0): ?>

                            <?php while ($row = $dataProduk->fetch_assoc()): ?>

                                <tr>

                                    <td>
                                        <?= htmlspecialchars($row['id_barang']) ?>
                                    </td>

                                    <td>
                                        <img
                                            src="asset/gambar/produk/<?= rawurlencode($row['gambar']) ?>"
                                            alt="Produk"
                                            class="gambar-produk">
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_barang']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['stok_barang']) ?>
                                    </td>

                                    <td>
                                        Rp <?= number_format((float)$row['harga_barang'], 0, ',', '.') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['kategori']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['deskripsi']) ?>
                                    </td>

                                </tr>

                            <?php endwhile; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="7" class="empty-data">
                                    Belum ada produk yang ditambahkan.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>


    <!-- ==================================
         MODAL TAMBAH PRODUK
    =================================== -->

    <div class="modal" id="modalAddProduk">

        <div class="modal-content">

            <button
                type="button"
                class="close-modal"
                onclick="closeModal('modalAddProduk')">

                &times;

            </button>

            <?= $formProduk ?>

        </div>

    </div>


    <!-- SWEETALERT2 -->

    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <script src="asset/js/dashboardSuper.js"></script>


    <!-- TOAST KANAN ATAS -->

    <?php if ($toastMessage !== null): ?>

        <script>

            Swal.fire({

                toast: true,

                position: 'top-end',

                icon: <?= json_encode($toastMessage['icon']) ?>,

                title: <?= json_encode($toastMessage['title']) ?>,

                showConfirmButton: false,

                showCloseButton: true,

                timer: 3500,

                timerProgressBar: true

            });

        </script>

    <?php endif; ?>

</body>
</html>

<?php ob_end_flush(); ?>
