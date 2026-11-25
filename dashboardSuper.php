
<?php
ob_start();

include "connect.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// PROSES TAMBAH USER
// ==========================================

if (isset($_POST['tambah_user'])) {

    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $id_role  = (int) ($_POST['id_role'] ?? 0);

    $gambar = null;
    $folder = __DIR__ . "/asset/gambar/user/";

    if (
        $fullname === '' ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        $username === '' ||
        $password === '' ||
        $id_role <= 0
    ) {
        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Data user tidak valid!"
        ];

        header("Location: dashboardSuper.php");
        exit;
    }

    // Pastikan folder tersedia
    if (!is_dir($folder)) {
        mkdir($folder, 0775, true);
    }

    // Upload gambar profil jika ada
    if (
        isset($_FILES['gambar']) &&
        $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Upload gambar gagal!"
            ];

            header("Location: dashboardSuper.php");
            exit;
        }

        $ekstensi = strtolower(
            pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION)
        );

        $ekstensiValid = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ekstensi, $ekstensiValid, true)) {
            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Format gambar tidak didukung!"
            ];

            header("Location: dashboardSuper.php");
            exit;
        }

        // Validasi bahwa file benar-benar gambar
        if (getimagesize($_FILES['gambar']['tmp_name']) === false) {
            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "File yang diunggah bukan gambar!"
            ];

            header("Location: dashboardSuper.php");
            exit;
        }

        $namaGambar = uniqid('user_', true) . '.' . $ekstensi;

        if (
            move_uploaded_file(
                $_FILES['gambar']['tmp_name'],
                $folder . $namaGambar
            )
        ) {
            $gambar = $namaGambar;
        } else {
            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Gambar gagal disimpan!"
            ];

            header("Location: dashboardSuper.php");
            exit;
        }
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $db->prepare("
        INSERT INTO Users
        (id_role, fullname, email, username, password, gambar)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        if ($gambar !== null && file_exists($folder . $gambar)) {
            unlink($folder . $gambar);
        }

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Query tambah user gagal diproses!"
        ];

        header("Location: dashboardSuper.php");
        exit;
    }

    $stmt->bind_param(
        "isssss",
        $id_role,
        $fullname,
        $email,
        $username,
        $passwordHash,
        $gambar
    );

    if ($stmt->execute()) {
        $_SESSION['toast'] = [
            "icon" => "success",
            "title" => "User berhasil ditambahkan!"
        ];
    } else {
        if ($gambar !== null && file_exists($folder . $gambar)) {
            unlink($folder . $gambar);
        }

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "User gagal ditambahkan! Email atau username mungkin sudah digunakan."
        ];
    }

    $stmt->close();

    header("Location: dashboardSuper.php");
    exit;
}


// ==========================================
// PROSES TAMBAH PRODUK
// ==========================================

if (isset($_POST['tambah'])) {

    $nama      = trim($_POST['nama_barang'] ?? '');
    $stok      = (int) ($_POST['stok_barang'] ?? 0);
    $harga     = (float) ($_POST['harga_barang'] ?? 0);
    $kategori  = trim($_POST['kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    $gambar = $_FILES['gambar'] ?? null;

    $folder = __DIR__ . "/asset/gambar/produk/";

    if (
        $nama === '' ||
        $kategori === '' ||
        $stok < 0 ||
        $harga < 0 ||
        empty($gambar['name']) ||
        $gambar['error'] !== UPLOAD_ERR_OK
    ) {
        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Lengkapi data produk terlebih dahulu!"
        ];
    } else {

        $ekstensi = strtolower(
            pathinfo($gambar['name'], PATHINFO_EXTENSION)
        );

        $ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'webp'];

        if (
            !in_array($ekstensi, $ekstensiDiizinkan, true) ||
            $gambar['size'] > 2 * 1024 * 1024 ||
            getimagesize($gambar['tmp_name']) === false
        ) {
            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Format atau ukuran gambar tidak valid!"
            ];
        } else {

            $namaGambar = uniqid('produk_', true) . '.' . $ekstensi;

            if (
                move_uploaded_file(
                    $gambar['tmp_name'],
                    $folder . $namaGambar
                )
            ) {

                $query = $db->prepare("
                    INSERT INTO barang
                    (nama_barang, gambar, stok_barang, harga_barang, kategori, deskripsi)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");

                if ($query) {

                    $query->bind_param(
                        "ssidss",
                        $nama,
                        $namaGambar,
                        $stok,
                        $harga,
                        $kategori,
                        $deskripsi
                    );

                    if ($query->execute()) {
                        $_SESSION['toast'] = [
                            "icon" => "success",
                            "title" => "Produk berhasil ditambahkan!"
                        ];
                    } else {
                        unlink($folder . $namaGambar);

                        $_SESSION['toast'] = [
                            "icon" => "error",
                            "title" => "Produk gagal ditambahkan!"
                        ];
                    }

                    $query->close();

                } else {
                    unlink($folder . $namaGambar);

                    $_SESSION['toast'] = [
                        "icon" => "error",
                        "title" => "Query produk gagal diproses!"
                    ];
                }

            } else {
                $_SESSION['toast'] = [
                    "icon" => "error",
                    "title" => "Upload gambar gagal!"
                ];
            }
        }
    }

    header("Location: dashboardSuper.php");
    exit;
}


// ==========================================
// AMBIL NOTIFIKASI
// ==========================================

$toastMessage = $_SESSION['toast'] ?? null;
unset($_SESSION['toast']);


// ==========================================
// AMBIL DATA PRODUK
// ==========================================

$dataProduk = $db->query("
    SELECT * FROM barang
    ORDER BY id_barang DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Super Admin</title>

    <link rel="stylesheet" href="asset/css/navbar.css">
    <link rel="stylesheet" href="asset/css/dashboardSuper.css">
</head>

<body>

    <!-- NAVBAR -->
    <?php include "layout/navbar.php"; ?>


    <!-- ==================================
         DASHBOARD
    ================================== -->

    <main class="dashboard">

        <div class="dashboard-header">
            <div>
                <h1>Dashboard Super Admin</h1>
                <p>Kelola produk E-commerce ATK</p>
            </div>
        </div>


        <!-- TOMBOL FITUR -->

        <div class="feature-buttons">

            <button
                type="button"
                class="btn-add"
                onclick="switchModalContent('produk')">

                <i class="ph ph-plus"></i>
                Tambah Produk

            </button>


            <button
                type="button"
                class="btn-feature"
                onclick="switchModalContent('user')">

                <i class="ph ph-user-plus"></i>
                Tambah User

            </button>


            <button
                type="button"
                class="btn-feature"
                onclick="openModal('modalLaporan')">

                <i class="ph ph-chart-bar"></i>
                Laporan

            </button>

        </div>


        <!-- ==================================
             TABEL PRODUK
        ================================== -->

        <section class="produk-section">

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
                                    Belum ada produk.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>

    </main>


    <!-- ==================================
         MODAL BERSAMA PRODUK DAN USER
    ================================== -->

    <div class="modal" id="modalForm">

        <div class="modal-content">

            <button
                type="button"
                class="close-modal"
                onclick="closeModal('modalForm')">

                &times;

            </button>


            <!-- KONTEN ADD PRODUK -->

            <div id="kontenAddProduk">
                <?php include "features/addProduk.php"; ?>
            </div>


            <!-- KONTEN ADD USER -->

            <div id="kontenAddUser" style="display: none;">
                <?php include "features/addUser.php"; ?>
            </div>

        </div>

    </div>


    <!-- ==================================
         MODAL LAPORAN
    ================================== -->

    <div class="modal" id="modalLaporan">

        <div class="modal-content">

            <button
                type="button"
                class="close-modal"
                onclick="closeModal('modalLaporan')">

                &times;

            </button>

            <?php include "features/laporan.php"; ?>

        </div>

    </div>


    <!-- JAVASCRIPT -->

    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <script src="asset/js/dashboardSuper.js"></script>

    <script src="asset/js/navbar.js"></script>


    <!-- TOAST -->

    <?php if ($toastMessage !== null): ?>

        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',

                icon: <?= json_encode($toastMessage['icon']) ?>,
                title: <?= json_encode($toastMessage['title']) ?>,

                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        </script>

    <?php endif; ?>

</body>
</html>

<?php ob_end_flush(); ?>
