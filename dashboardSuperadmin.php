
<?php
include "connect.php";

// Variabel notifikasi
$toastMessage = null;

// Proses dan ambil tampilan form produk SATU KALI
ob_start();
include "feature/addProduk.php";
$formProduk = ob_get_clean();

// Buka kembali modal jika proses produk sudah dilakukan
$openAddProduk = !empty($toastMessage);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Super Admin</title>

    <link rel="stylesheet" href="../asset/css/navbar.css?v=3">
    <link rel="stylesheet" href="asset/css/dashboardSuper.css">
</head>

<body>

    <?php include "../layout/navbar.php"; ?>

    <main class="dashboard">

        <h1>Dashboard Super Admin</h1>

        <!-- TOMBOL FITUR -->
        <div class="feature-buttons">

            <button type="button" onclick="openModal('modalAddProduk')">
                Tambah Produk
            </button>

            <button type="button" onclick="openModal('modalAddUser')">
                Tambah User
            </button>

            <button type="button" onclick="openModal('modalLaporan')">
                Laporan
            </button>

        </div>

    </main>


    <!-- MODAL TAMBAH PRODUK -->
    <div class="modal" id="modalAddProduk">

        <div class="modal-content">

            <button type="button" class="close-modal"
                onclick="closeModal('modalAddProduk')">
                &times;
            </button>

            <?= $formProduk ?>

        </div>

    </div>


    <!-- MODAL TAMBAH USER -->
    <div class="modal" id="modalAddUser">

        <div class="modal-content">

            <button type="button" class="close-modal"
                onclick="closeModal('modalAddUser')">
                &times;
            </button>

            <?php include "feature/addUser.php"; ?>

        </div>

    </div>


    <!-- MODAL LAPORAN -->
    <div class="modal" id="modalLaporan">

        <div class="modal-content">

            <button type="button" class="close-modal"
                onclick="closeModal('modalLaporan')">
                &times;
            </button>

            <?php include "feature/laporan.php"; ?>

        </div>

    </div>


    <!-- SWEETALERT2 -->
    <script src="../node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <script src="asset/js/dashboardSuper.js"></script>
    <script src="../asset/js/navbar.js"></script>

    <!-- TOAST NOTIFICATION -->
    <?php if (!empty($toastMessage)) : ?>

        <script>
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });

            Toast.fire({
                icon: <?= json_encode($toastMessage['icon']) ?>,
                title: <?= json_encode($toastMessage['title']) ?>
            });
        </script>

    <?php endif; ?>


    <!-- BUKA KEMBALI MODAL PRODUK -->
    <?php if ($openAddProduk) : ?>

        <script>
            openModal('modalAddProduk');
        </script>

    <?php endif; ?>


    <script src="https://unpkg.com/@phosphor-icons/web"></script>

</body>
</html>
