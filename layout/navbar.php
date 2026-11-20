<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="../asset/css/navbar.css?v=2">
</head>
<body>
    <div class="navbar">
        <div class="profile">
            <img src="../asset/gambar/bahan/profile.png" alt="#" id="profile" name="profile">
            <label id="status"></label>
        </div>
        <div class="pencarian">
            <input type="search" name="pencarian" id="pencarian" placeholder="cari produk...">
            <img src="../asset/gambar/bahan/pencarian.png" alt="cari">
        </div>
        <div class="main">
            <ul id="main">
                <li href="#">Dashboard</li>
                <li href="#">keranjang</li> 
                <li href="#">produk suka</li> 
                <li href="#">Barang Masuk</li> 
                <li href="#">Barang Keluar</li> 
            </ul>
        </div>
        <div class="icon-list" id="menu">
            <i class="ph ph-list"></i>
        </div>
    </div>
    <script src="../asset/js/navbar.js"></script>
</body>
</html>