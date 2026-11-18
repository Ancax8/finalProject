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

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Marketplace ATK</title>

    <link rel="stylesheet" href="asset/css/index.css">
</head>

<body>

    <div class="addMenu active" id="create">
        <button type="button" onclick="showForm('home', 'addMenu')">
            gas
        </button>
    </div>

    <div class="addMenu" id="home">

        <form action="index.php" name="plus" method="POST" enctype="multipart/form-data">

            <input type="text" name="barang" id="barang" placeholder="nama barang">

            <input type="number" name="quantity" id="quantity" placeholder="jumlah">

            <input type="number" name="harga" id="harga" placeholder="harga">

            <input type="file" name="gambar" id="gambar" accept="image/*">

            <button type="submit" name="tambah" id="tambah">
                tambah menu
            </button>

        </form>

    </div>


    <!-- SWEETALERT2 -->
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <?php if ($toast !== null): ?>
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',

                icon: <?= json_encode($toast['icon']) ?>,
                title: <?= json_encode($toast['title']) ?>,

                showConfirmButton: false,
                showCloseButton: true,

                timer: 3500,
                timerProgressBar: true,

                customClass: {
                    popup: 'marketplace-toast'
                },

                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        </script>
    <?php endif; ?>

    <script src="asset/js/login.js"></script>

</body>

</html>