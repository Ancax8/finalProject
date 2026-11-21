
<?php
// Data contoh untuk frontend.
// Nantinya akan diganti dengan data dari database.
$pesanan = [
    [
        "nomor" => "#PS-00145",
        "tanggal" => "11 November 2026",
        "tanggal_filter" => "2026-11-11",
        "status" => "Diproses",
        "total" => 300000,
        "produk" => [
            ["nama" => "Pulpen Gel 0.5", "jumlah" => 2]
        ]
    ],
    [
        "nomor" => "#PS-00144",
        "tanggal" => "10 November 2026",
        "tanggal_filter" => "2026-11-10",
        "status" => "Dikirim",
        "total" => 300000,
        "produk" => [
            ["nama" => "Pulpen Gel 0.5", "jumlah" => 2]
        ]
    ],
    [
        "nomor" => "#PS-00143",
        "tanggal" => "09 November 2026",
        "tanggal_filter" => "2026-11-09",
        "status" => "Selesai",
        "total" => 150000,
        "produk" => [
            ["nama" => "Buku Tulis B5", "jumlah" => 3]
        ]
    ],
    [
        "nomor" => "#PS-00142",
        "tanggal" => "08 November 2026",
        "tanggal_filter" => "2026-11-08",
        "status" => "Dibatalkan",
        "total" => 50000,
        "produk" => [
            ["nama" => "Pensil 2B", "jumlah" => 5]
        ]
    ]
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Marketplace ATK</title>

    <link rel="stylesheet" href="../asset/css/riwayatPesanan.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

<main class="order-container">

    <!-- HEADER -->
    <header class="order-header">
        <h1>RIWAYAT PESANAN</h1>

        <a href="dashboardUser.php" class="btn-close" title="Kembali ke Dashboard">
            <i class="ph ph-x"></i>
        </a>
    </header>

    <!-- FILTER STATUS -->
    <section class="status-filter">
        <button class="status-btn active" data-status="Semua">
            Semua Pesanan
        </button>
        <button class="status-btn" data-status="Diproses">
            Diproses
        </button>
        <button class="status-btn" data-status="Dikirim">
            Dikirim
        </button>
        <button class="status-btn" data-status="Selesai">
            Selesai
        </button>
        <button class="status-btn" data-status="Dibatalkan">
            Dibatalkan
        </button>
    </section>

    <!-- SEARCH DAN FILTER TANGGAL -->
    <section class="order-tools">

        <div class="search-order">
            <i class="ph ph-magnifying-glass"></i>
            <input
                type="search"
                id="searchOrder"
                placeholder="Cari nama produk..."
            >
        </div>

        <div class="date-filter">
            <i class="ph ph-calendar-blank"></i>
            <input type="date" id="filterDate">
        </div>

    </section>

    <!-- DAFTAR PESANAN -->
    <section class="order-list" id="orderList">

        <?php foreach ($pesanan as $item): ?>
            <article
                class="order-card"
                data-status="<?= htmlspecialchars($item["status"]) ?>"
                data-date="<?= htmlspecialchars($item["tanggal_filter"]) ?>"
                data-products="<?= htmlspecialchars(strtolower(implode(" ", array_column($item["produk"], "nama")))) ?>"
            >

                <div class="order-main">

                    <div class="order-product-info">
                        <h3>No. Pesanan: <?= htmlspecialchars($item["nomor"]) ?></h3>

                        <p class="order-date">
                            Tanggal Pesanan: <?= htmlspecialchars($item["tanggal"]) ?>
                        </p>

                        <div class="product-preview">
                            <?php foreach ($item["produk"] as $produk): ?>
                                <div class="product-image">
                                    Gambar<br>Produk
                                </div>

                                <strong>
                                    <?= htmlspecialchars($produk["nama"]) ?>
                                </strong>

                                <span>
                                    x<?= (int) $produk["jumlah"] ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="order-total">
                        <span>Total Pembayaran</span>
                        <strong>
                            Rp <?= number_format($item["total"], 0, ",", ".") ?>
                        </strong>
                    </div>

                    <div class="order-action">

                        <span class="order-status status-<?= strtolower($item["status"]) ?>">
                            <?php if ($item["status"] === "Diproses"): ?>
                                <i class="ph ph-clock"></i>
                            <?php elseif ($item["status"] === "Dikirim"): ?>
                                <i class="ph ph-truck"></i>
                            <?php elseif ($item["status"] === "Selesai"): ?>
                                <i class="ph ph-check-circle"></i>
                            <?php else: ?>
                                <i class="ph ph-x-circle"></i>
                            <?php endif; ?>

                            <?= htmlspecialchars($item["status"]) ?>
                        </span>

                        <button
                            type="button"
                            class="btn-detail"
                            data-order="<?= htmlspecialchars($item["nomor"]) ?>"
                        >
                            <i class="ph ph-receipt"></i>
                            Detail Pesanan
                        </button>

                    </div>

                </div>

            </article>
        <?php endforeach; ?>

        <div class="empty-order" id="emptyOrder" hidden>
            <i class="ph ph-package"></i>
            <p>Pesanan tidak ditemukan.</p>
        </div>

    </section>

    <!-- PAGINATION -->
    <footer class="order-footer">
        <p id="paginationInfo">Menampilkan 1 dari 1 halaman</p>

        <div class="pagination">
            <button id="prevPage" disabled>
                <i class="ph ph-caret-left"></i>
            </button>

            <span id="currentPage">1</span>

            <button id="nextPage" disabled>
                <i class="ph ph-caret-right"></i>
            </button>
        </div>
    </footer>

</main>

<!-- POPUP DETAIL PESANAN -->
<div class="detail-overlay" id="detailOverlay">
    <div class="detail-modal">
        <div class="detail-header">
            <h2>DETAIL PESANAN</h2>
            <button type="button" id="closeDetail">
                <i class="ph ph-x"></i>
            </button>
        </div>

        <div id="detailContent"></div>
    </div>
</div>

<script src="../asset/js/riwayatPesanan.js"></script>

</body>
</html>
