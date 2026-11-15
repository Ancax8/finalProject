<?php

include "connect.php";

$alert = null;
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
            $alert = [
                "icon" => "success",
                "title" => "Berhasil!",
                "text" => "Data dan gambar berhasil disimpan."
            ];
        } else {
            $alert = [
                "icon" => "error",
                "title" => "Gagal!",
                "text" => $db->error
            ];
        }

    } else {
        $alert = [
            "icon" => "error",
            "title" => "Upload Gagal!",
            "text" => "Gambar tidak berhasil dipindahkan."
        ];
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="asset/css/index.css">
</head>
<body>
    <div class="addMenu active" id="create">
        <button type="submit" onclick="showForm('home')">gas</button>
    </div>
    <div class="addMenu" id="home">
        <form action="index.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="barang" id="barang" placeholder="nama barang">
            <input type="number" name="quantity" id="quantity" placeholder="jumlah">
            <input type="number" name="harga" id="harga" placeholder="harga">
            <input type="file" name="gambar" id="gambar" accept="image/*">
            <button type="submit" name="tambah" id="tambah">tambah menu</button>
            
        </form>
    </div>
    <?php if ($alert != null) { ?>
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script>
        Swal.fire({
            icon: <?= json_encode($alert['icon']) ?>,
            title: <?= json_encode($alert['title']) ?>,
            text: <?= json_encode($alert['text']) ?>
        });
    </script>
    <?php } ?>
    
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="asset/js/login.js"></script>

</body>
</html>