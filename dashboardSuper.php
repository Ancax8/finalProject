<?php
require_once __DIR__ . '/auth.php';
wajibLogin([1]);
?>

<?php
ob_start();

include "connect.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// ==========================================
// PROSES GANTI ROLE PENGGUNA
// ==========================================

if (isset($_POST['ganti_role'])) {

    $idUser = (int) ($_POST['id_user'] ?? 0);
    $idRoleBaru = (int) ($_POST['id_role_baru'] ?? 0);
    $idUserLogin = (int) ($_SESSION['user_id'] ?? 0);

    if ($idUser <= 0 || $idRoleBaru <= 0 || $idUser === $idUserLogin) {

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Data tidak valid atau akun sendiri tidak dapat diubah!"
        ];

    } else {

        // Periksa akun yang akan diubah
        $cekUser = $db->prepare("
            SELECT Users.id_user, Roles.role_name
            FROM Users
            INNER JOIN Roles ON Users.id_role = Roles.id_role
            WHERE Users.id_user = ?
        ");

        $cekUser->bind_param("i", $idUser);
        $cekUser->execute();
        $userTarget = $cekUser->get_result()->fetch_assoc();
        $cekUser->close();

        // Periksa role tujuan
        $cekRole = $db->prepare("
            SELECT id_role, role_name
            FROM Roles
            WHERE id_role = ?
        ");

        $cekRole->bind_param("i", $idRoleBaru);
        $cekRole->execute();
        $roleTujuan = $cekRole->get_result()->fetch_assoc();
        $cekRole->close();

        if (!$userTarget || !$roleTujuan) {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Pengguna atau role tidak ditemukan!"
            ];

        } elseif (strtolower($userTarget['role_name']) === 'super admin') {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Role akun Super Admin dilindungi!"
            ];

        } elseif (strtolower($roleTujuan['role_name']) === 'super admin') {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Tidak dapat memberikan role Super Admin melalui fitur ini!"
            ];

        } else {

            $stmt = $db->prepare("
                UPDATE Users
                SET id_role = ?
                WHERE id_user = ?
            ");

            $stmt->bind_param("ii", $idRoleBaru, $idUser);

            if ($stmt->execute()) {
                $_SESSION['toast'] = [
                    "icon" => "success",
                    "title" => "Role pengguna berhasil diperbarui!"
                ];
            } else {
                $_SESSION['toast'] = [
                    "icon" => "error",
                    "title" => "Gagal memperbarui role pengguna!"
                ];
            }

            $stmt->close();
        }
    }

    header("Location: dashboardSuper.php");
    exit;
}


// ==========================================
// PROSES HAPUS PENGGUNA
// ==========================================

if (isset($_POST['hapus_pengguna'])) {

    $idUser = (int) ($_POST['id_user'] ?? 0);
    $idUserLogin = (int) ($_SESSION['user_id'] ?? 0);

    if ($idUser <= 0 || $idUser === $idUserLogin) {

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Akun sendiri tidak dapat dihapus!"
        ];

    } else {

        $cekUser = $db->prepare("
            SELECT Users.id_user, Roles.role_name
            FROM Users
            INNER JOIN Roles ON Users.id_role = Roles.id_role
            WHERE Users.id_user = ?
        ");

        $cekUser->bind_param("i", $idUser);
        $cekUser->execute();
        $userTarget = $cekUser->get_result()->fetch_assoc();
        $cekUser->close();

        if (!$userTarget) {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Pengguna tidak ditemukan!"
            ];

        } elseif (strtolower($userTarget['role_name']) === 'super admin') {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Akun Super Admin tidak dapat dihapus melalui fitur ini!"
            ];

        } else {

            try {

                $db->begin_transaction();

                $stmt = $db->prepare("
                    DELETE FROM Users
                    WHERE id_user = ?
                ");

                $stmt->bind_param("i", $idUser);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    $db->commit();

                    $_SESSION['toast'] = [
                        "icon" => "success",
                        "title" => "Pengguna berhasil dihapus!"
                    ];
                } else {
                    $db->rollback();

                    $_SESSION['toast'] = [
                        "icon" => "error",
                        "title" => "Pengguna gagal dihapus!"
                    ];
                }

                $stmt->close();

            } catch (Throwable $e) {

                $db->rollback();

                $_SESSION['toast'] = [
                    "icon" => "error",
                    "title" => "Pengguna tidak dapat dihapus karena masih memiliki data terkait!"
                ];
            }
        }
    }

    header("Location: dashboardSuper.php");
    exit;
}


// ==========================================
// PROSES HAPUS ROLE
// ==========================================

if (isset($_POST['hapus_role'])) {

    $idRole = (int) ($_POST['id_role'] ?? 0);

    // Cari role User sebagai tujuan pemindahan
    $hasilUser = $db->query("
        SELECT id_role
        FROM Roles
        WHERE LOWER(role_name) = 'user'
        LIMIT 1
    ");

    $roleUser = $hasilUser ? $hasilUser->fetch_assoc() : null;

    // Cari role yang akan dihapus
    $cekRole = $db->prepare("
        SELECT id_role, role_name
        FROM Roles
        WHERE id_role = ?
    ");

    $cekRole->bind_param("i", $idRole);
    $cekRole->execute();
    $roleTarget = $cekRole->get_result()->fetch_assoc();
    $cekRole->close();

    if (!$roleTarget || !$roleUser) {

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Role tidak ditemukan!"
        ];

    } elseif (
        strtolower($roleTarget['role_name']) === 'user' ||
        strtolower($roleTarget['role_name']) === 'super admin'
    ) {

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Role User dan Super Admin tidak dapat dihapus!"
        ];

    } elseif ((int) $roleUser['id_role'] === $idRole) {

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Role tujuan pemindahan tidak valid!"
        ];

    } else {

        try {

            $db->begin_transaction();

            // Pindahkan semua akun ke role User
            $pindahkan = $db->prepare("
                UPDATE Users
                SET id_role = ?
                WHERE id_role = ?
            ");

            $idRoleUser = (int) $roleUser['id_role'];

            $pindahkan->bind_param("ii", $idRoleUser, $idRole);
            $pindahkan->execute();
            $pindahkan->close();

            // Hapus role setelah tidak digunakan
            $hapus = $db->prepare("
                DELETE FROM Roles
                WHERE id_role = ?
            ");

            $hapus->bind_param("i", $idRole);
            $hapus->execute();

            if ($hapus->affected_rows > 0) {

                $db->commit();

                $_SESSION['toast'] = [
                    "icon" => "success",
                    "title" => "Role berhasil dihapus. Penggunanya dipindahkan ke User!"
                ];

            } else {

                $db->rollback();

                $_SESSION['toast'] = [
                    "icon" => "error",
                    "title" => "Role gagal dihapus!"
                ];
            }

            $hapus->close();

        } catch (Throwable $e) {

            $db->rollback();

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Penghapusan role gagal diproses!"
            ];
        }
    }

    header("Location: dashboardSuper.php");
    exit;
}


// ==========================================
// PROSES TAMBAH USER
// ==========================================

if (isset($_POST['tambah_user'])) {

    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $id_role = (int) ($_POST['id_role'] ?? 0);

    $gambar = null;
    $folder = __DIR__ . "/asset/gambar/user/";

    // --------------------------------------
    // VALIDASI DATA WAJIB
    // --------------------------------------

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

    // --------------------------------------
    // VALIDASI ROLE
    // --------------------------------------

    $cekRole = $db->prepare("
        SELECT id_role
        FROM Roles
        WHERE id_role = ?
    ");

    $cekRole->bind_param("i", $id_role);
    $cekRole->execute();
    $cekRole->store_result();

    $roleValid = $cekRole->num_rows > 0;
    $cekRole->close();

    if (!$roleValid) {
        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Role pengguna tidak ditemukan!"
        ];

        header("Location: dashboardSuper.php");
        exit;
    }

    // --------------------------------------
    // CEK EMAIL DAN USERNAME
    // --------------------------------------

    $cekUser = $db->prepare("
        SELECT email, username
        FROM Users
        WHERE email = ? OR username = ?
    ");

    $cekUser->bind_param("ss", $email, $username);
    $cekUser->execute();
    $cekUser->store_result();

    $userSudahAda = $cekUser->num_rows > 0;
    $cekUser->close();

    if ($userSudahAda) {
        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Email atau username sudah digunakan!"
        ];

        header("Location: dashboardSuper.php");
        exit;
    }

    // --------------------------------------
    // PERSIAPAN FOLDER GAMBAR
    // --------------------------------------

    if (!is_dir($folder)) {
        mkdir($folder, 0775, true);
    }

    // --------------------------------------
    // UPLOAD GAMBAR PROFIL (OPSIONAL)
    // --------------------------------------

    if (
        isset($_FILES['gambar']) &&
        $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $file = $_FILES['gambar'];

        $ekstensi = strtolower(
            pathinfo($file['name'], PATHINFO_EXTENSION)
        );

        $ekstensiValid = ['jpg', 'jpeg', 'png', 'webp'];

        if (
            $file['error'] !== UPLOAD_ERR_OK ||
            !in_array($ekstensi, $ekstensiValid, true) ||
            $file['size'] > 2 * 1024 * 1024 ||
            getimagesize($file['tmp_name']) === false
        ) {
            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Gambar tidak valid! Maksimal 2 MB."
            ];

            header("Location: dashboardSuper.php");
            exit;
        }

        $namaGambar = uniqid('user_', true) . '.' . $ekstensi;

        if (
            move_uploaded_file(
                $file['tmp_name'],
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

    // --------------------------------------
    // HASH PASSWORD
    // --------------------------------------

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // --------------------------------------
    // SIMPAN USER KE DATABASE
    // --------------------------------------

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
            "title" => "User gagal ditambahkan!"
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

    // Ambil data dari form
    $nama = trim($_POST['nama_barang'] ?? '');
    $stok = (int) ($_POST['stok_barang'] ?? 0);
    $harga = (float) ($_POST['harga_barang'] ?? 0);
    $kategori = (int) ($_POST['kategori'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    $gambar = $_FILES['gambar'] ?? null;

    $folder = __DIR__ . "/asset/gambar/produk/";

    // Validasi data
    if (
        $nama === '' ||
        $kategori <= 0 ||
        $stok < 0 ||
        $harga < 0 ||
        !$gambar ||
        $gambar['error'] !== UPLOAD_ERR_OK
    ) {
        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Lengkapi data produk terlebih dahulu!"
        ];

    } else {

        // Pastikan kategori tersedia dan aktif
        $cekKategori = $db->prepare("
            SELECT id_kategori
            FROM kategori
            WHERE id_kategori = ?
            AND status = 'aktif'
        ");

        $cekKategori->bind_param("i", $kategori);
        $cekKategori->execute();
        $hasilKategori = $cekKategori->get_result();

        if ($hasilKategori->num_rows === 0) {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Kategori tidak ditemukan atau tidak aktif!"
            ];

        } else {

            // Validasi gambar
            $ekstensi = strtolower(
                pathinfo($gambar['name'], PATHINFO_EXTENSION)
            );

            $ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'webp'];

            $infoGambar = getimagesize($gambar['tmp_name']);

            if (
                !in_array($ekstensi, $ekstensiDiizinkan, true) ||
                $gambar['size'] > 2 * 1024 * 1024 ||
                $infoGambar === false
            ) {
                $_SESSION['toast'] = [
                    "icon" => "error",
                    "title" => "Format atau ukuran gambar tidak valid!"
                ];

            } else {

                // Buat folder jika belum tersedia
                if (!is_dir($folder)) {
                    mkdir($folder, 0775, true);
                }

                // Buat nama gambar unik
                $namaGambar = uniqid('produk_', true) . '.' . $ekstensi;

                if (
                    move_uploaded_file(
                        $gambar['tmp_name'],
                        $folder . $namaGambar
                    )
                ) {

                    // Simpan produk sesuai struktur database
                    $query = $db->prepare("
                        INSERT INTO barang
                        (
                            barang,
                            gambar,
                            jumlah,
                            harga,
                            id_kategori,
                            deskripsi
                        )
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");

                    if ($query) {

                        $query->bind_param(
                            "ssidis",
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

        $cekKategori->close();
    }

    header("Location: dashboardSuper.php");
    exit;
}

// ==========================================
// PROSES EDIT PRODUK
// ==========================================

if (isset($_POST['edit_produk'])) {
    $id = (int) ($_POST['edit_id_barang'] ?? 0);
    $nama = trim($_POST['edit_barang'] ?? '');
    $stok = (int) ($_POST['edit_jumlah'] ?? 0);
    $harga = (float) ($_POST['edit_harga'] ?? 0);
    $kategori = (int) ($_POST['edit_id_kategori'] ?? 0);
    $deskripsi = trim($_POST['edit_deskripsi'] ?? '');

    $folder = __DIR__ . "/asset/gambar/produk/";
    $gambarBaru = null;

    if ($id <= 0 || $nama === '' || $kategori <= 0 || $stok < 0 || $harga < 0) {
        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Data produk tidak valid!"
        ];
        header("Location: dashboardSuper.php");
        exit;
    }

    // Upload gambar baru jika dipilih
    if (
        isset($_FILES['edit_gambar']) &&
        $_FILES['edit_gambar']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        $file = $_FILES['edit_gambar'];
        $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $valid = ['jpg', 'jpeg', 'png', 'webp'];

        if (
            $file['error'] !== UPLOAD_ERR_OK ||
            !in_array($ekstensi, $valid, true) ||
            $file['size'] > 2 * 1024 * 1024 ||
            getimagesize($file['tmp_name']) === false
        ) {
            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Gambar tidak valid! Maksimal 2 MB."
            ];
            header("Location: dashboardSuper.php");
            exit;
        }

        if (!is_dir($folder)) {
            mkdir($folder, 0775, true);
        }

        $gambarBaru = uniqid('produk_', true) . '.' . $ekstensi;

        if (!move_uploaded_file($file['tmp_name'], $folder . $gambarBaru)) {
            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Upload gambar gagal!"
            ];
            header("Location: dashboardSuper.php");
            exit;
        }
    }

    if ($gambarBaru !== null) {
        $stmt = $db->prepare("
            UPDATE barang
            SET barang = ?, jumlah = ?, harga = ?,
                id_kategori = ?, deskripsi = ?, gambar = ?
            WHERE id_barang = ?
        ");

        $stmt->bind_param(
            "sidissi",
            $nama,
            $stok,
            $harga,
            $kategori,
            $deskripsi,
            $gambarBaru,
            $id
        );
    } else {
        $stmt = $db->prepare("
            UPDATE barang
            SET barang = ?, jumlah = ?, harga = ?,
                id_kategori = ?, deskripsi = ?
            WHERE id_barang = ?
        ");

        $stmt->bind_param(
            "sidisi",
            $nama,
            $stok,
            $harga,
            $kategori,
            $deskripsi,
            $id
        );
    }

    if ($stmt && $stmt->execute()) {
        $_SESSION['toast'] = [
            "icon" => "success",
            "title" => "Produk berhasil diperbarui!"
        ];
    } else {
        if ($gambarBaru !== null && file_exists($folder . $gambarBaru)) {
            unlink($folder . $gambarBaru);
        }

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Produk gagal diperbarui!"
        ];
    }

    if ($stmt) {
        $stmt->close();
    }

    header("Location: dashboardSuper.php");
    exit;
}


// ==========================================
// PROSES NONAKTIFKAN PRODUK
// ==========================================

if (isset($_POST['nonaktifkan_barang'])) {
    $id = (int) ($_POST['id_barang'] ?? 0);

    $stmt = $db->prepare("
        UPDATE barang SET status = 'nonaktif'
        WHERE id_barang = ?
    ");

    $stmt->bind_param("i", $id);
    $berhasil = $stmt->execute();

    $_SESSION['toast'] = [
        "icon" => $berhasil ? "success" : "error",
        "title" => $berhasil
            ? "Produk berhasil dinonaktifkan!"
            : "Produk gagal dinonaktifkan!"
    ];

    $stmt->close();

    header("Location: dashboardSuper.php");
    exit;
}


// ==========================================
// PROSES AKTIFKAN KEMBALI PRODUK
// ==========================================

if (isset($_POST['aktifkan_barang'])) {
    $id = (int) ($_POST['id_barang'] ?? 0);

    $stmt = $db->prepare("
        UPDATE barang SET status = 'aktif'
        WHERE id_barang = ?
    ");

    $stmt->bind_param("i", $id);
    $berhasil = $stmt->execute();

    $_SESSION['toast'] = [
        "icon" => $berhasil ? "success" : "error",
        "title" => $berhasil
            ? "Produk berhasil diaktifkan!"
            : "Produk gagal diaktifkan!"
    ];

    $stmt->close();

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
    SELECT
        barang.*,
        kategori.nama_kategori
    FROM barang
    LEFT JOIN kategori
        ON barang.id_kategori = kategori.id_kategori
    ORDER BY barang.id_barang DESC
");

$dataKategori = $db->query("
    SELECT id_kategori, nama_kategori
    FROM kategori
    WHERE status = 'aktif'
    ORDER BY nama_kategori ASC
");
$dataPengguna = $db->query("
    SELECT
        Users.id_user,
        Users.fullname,
        Users.email,
        Users.username,
        Roles.role_name
    FROM Users
    INNER JOIN Roles
        ON Users.id_role = Roles.id_role
    WHERE LOWER(Roles.role_name) <> 'user'
    ORDER BY Roles.id_role ASC, Users.fullname ASC
");

// ==========================================
// AMBIL DATA PENGGUNA SELAIN USER
// ==========================================

$dataPengguna = $db->query("
    SELECT
        Users.id_user,
        Users.fullname,
        Users.email,
        Users.username,
        Users.id_role,
        Roles.role_name
    FROM Users
    INNER JOIN Roles ON Users.id_role = Roles.id_role
    WHERE LOWER(Roles.role_name) <> 'user'
    ORDER BY Roles.id_role ASC, Users.fullname ASC
");


// ==========================================
// AMBIL SEMUA ROLE
// ==========================================

$dataRoleKelola = $db->query("
    SELECT
        Roles.id_role,
        Roles.role_name,
        COUNT(Users.id_user) AS jumlah_pengguna
    FROM Roles
    LEFT JOIN Users ON Users.id_role = Roles.id_role
    GROUP BY Roles.id_role, Roles.role_name
    ORDER BY Roles.id_role ASC
");

$daftarRole = [];

if ($dataRoleKelola) {
    while ($role = $dataRoleKelola->fetch_assoc()) {
        $daftarRole[] = $role;
    }
}

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

    <!-- NAVBAR KHUSUS SUPER ADMIN -->
    <header class="super-navbar">
        <div class="super-navbar-brand">E-commerce ATK</div>

        <button type="button" class="super-menu-toggle" id="superMenuToggle" aria-label="Buka menu fitur"
            aria-expanded="false">
            <i class="ph ph-list"></i>
        </button>

        <nav class="super-menu" id="superMenu" aria-label="Navigasi fitur">
            <button type="button" class="super-menu-item active" data-target="barang">
                <i class="ph ph-package"></i><span>CRUD Barang</span>
            </button>
            <button type="button" class="super-menu-item" data-target="pengguna">
                <i class="ph ph-users"></i><span>Kelola Role/Pengguna</span>
            </button>
            <button type="button" class="super-menu-item" data-target="laporan">
                <i class="ph ph-chart-bar"></i><span>Laporan Penjualan</span>
            </button>
        </nav>
    </header>


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


        <!-- NAVIGASI FITUR -->
        <nav class="super-feature-nav" aria-label="Fitur Super Admin">
            <button type="button" class="super-feature-tab active" data-target="barang">
                <i class="ph ph-package"></i>
                <span>CRUD Barang</span>
            </button>

            <button type="button" class="super-feature-tab" data-target="pengguna">
                <i class="ph ph-users"></i>
                <span>Kelola Role/Pengguna</span>
            </button>

            <button type="button" class="super-feature-tab" data-target="laporan">
                <i class="ph ph-chart-bar"></i>
                <span>Laporan Penjualan</span>
            </button>
        </nav>

        <!-- PANEL CRUD BARANG -->
        <section class="super-feature-panel active" id="panel-barang" data-panel="barang">
            <section class="produk-section">
                <div class="produk-toolbar">
                    <div>
                        <h2>Daftar Barang</h2>
                        <p>Kelola data produk yang tersedia di toko.</p>
                    </div>

                    <div class="produk-actions">
                        <label class="search-produk" for="searchProduk">
                            <i class="ph ph-magnifying-glass"></i>
                            <input type="search" id="searchProduk" placeholder="Cari nama barang..." autocomplete="off">
                        </label>

                        <button type="button" class="btn-add" onclick="switchModalContent('produk')">
                            <i class="ph ph-plus"></i>
                            Tambah Barang
                        </button>
                    </div>
                </div>

                <div class="table-container">
                    <table class="table-produk" id="tableProduk">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Gambar</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody id="bodyProduk">

                            <?php while ($row = $dataProduk->fetch_assoc()): ?>
                                <tr class="baris-produk">

                                    <td><?= (int) $row['id_barang'] ?></td>

                                    <td>
                                        <img src="asset/gambar/produk/<?= rawurlencode($row['gambar'] ?? '') ?>"
                                            alt="Produk" class="gambar-produk">
                                    </td>

                                    <td class="nama-produk">
                                        <?= htmlspecialchars($row['barang'] ?? '') ?>
                                    </td>

                                    <td><?= (int) $row['jumlah'] ?></td>

                                    <td>
                                        Rp <?= number_format((float) $row['harga'], 0, ',', '.') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_kategori'] ?? '-') ?>
                                    </td>

                                    <td><?= htmlspecialchars($row['deskripsi'] ?? '-') ?></td>

                                    <td>
                                        <?= htmlspecialchars($row['status'] ?? '') ?>
                                    </td>

                                    <td>
                                        <button type="button" class="btn-edit" onclick="bukaEditBarang(this)"
                                            data-id="<?= (int) $row['id_barang'] ?>"
                                            data-nama="<?= htmlspecialchars($row['barang'] ?? '', ENT_QUOTES) ?>"
                                            data-stok="<?= (int) $row['jumlah'] ?>"
                                            data-harga="<?= (float) $row['harga'] ?>"
                                            data-kategori="<?= (int) ($row['id_kategori'] ?? 0) ?>"
                                            data-deskripsi="<?= htmlspecialchars($row['deskripsi'] ?? '', ENT_QUOTES) ?>">
                                            Edit
                                        </button>

                                        <?php if ($row['status'] === 'aktif'): ?>
                                            <form method="POST" class="form-aksi">
                                                <input type="hidden" name="id_barang" value="<?= (int) $row['id_barang'] ?>">

                                                <button type="submit" name="nonaktifkan_barang" class="btn-nonaktif"
                                                    onclick="return confirm('Nonaktifkan produk ini?')">
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="POST" class="form-aksi">
                                                <input type="hidden" name="id_barang" value="<?= (int) $row['id_barang'] ?>">

                                                <button type="submit" name="aktifkan_barang" class="btn-aktif"
                                                    onclick="return confirm('Aktifkan kembali produk ini?')">
                                                    Aktifkan
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endwhile; ?>

                        </tbody>
                    </table>
                </div>
            </section>
        </section>

       
<!-- PANEL KELOLA ROLE/PENGGUNA -->
<section class="super-feature-panel" id="panel-pengguna" data-panel="pengguna" hidden>

    <!-- HEADER -->
    <section class="super-content-card">
        <div>
            <h2>Kelola Role/Pengguna</h2>
            <p>Kelola akun pengguna dan hak akses aplikasi.</p>
        </div>

        <button type="button" class="btn-add" onclick="switchModalContent('user')">
            <i class="ph ph-user-plus"></i>
            Tambah Pengguna
        </button>
    </section>


    <!-- DAFTAR PENGGUNA -->
    <section class="produk-section">

        <div class="produk-toolbar">
            <div>
                <h2>Daftar Pengguna</h2>
                <p>Daftar akun pengelola aplikasi selain User.</p>
            </div>
        </div>

        <div class="table-container">

            <table class="table-produk">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pengguna</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if ($dataPengguna && $dataPengguna->num_rows > 0): ?>

                        <?php $no = 1; ?>

                        <?php while ($user = $dataPengguna->fetch_assoc()): ?>

                            <?php
                            $isAkunSendiri =
                                (int) $user['id_user'] === (int) ($_SESSION['user_id'] ?? 0);

                            $isSuperAdmin =
                                strtolower($user['role_name']) === 'super admin';
                            ?>

                            <tr>
                                <td><?= $no++ ?></td>

                                <td>
                                    <?= htmlspecialchars($user['fullname']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($user['email']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($user['username']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($user['role_name']) ?>
                                </td>

                                <td>
                                    <?php if (!$isAkunSendiri && !$isSuperAdmin): ?>

                                        <div class="aksi-pengguna">

                                            <!-- GANTI ROLE -->
                                            <form method="POST" class="form-ganti-role">

                                                <input type="hidden" name="id_user"
                                                    value="<?= (int) $user['id_user'] ?>">

                                                <select name="id_role_baru" required>
                                                    <?php foreach ($daftarRole as $role): ?>

                                                        <?php if (strtolower($role['role_name']) !== 'super admin'): ?>

                                                            <option
                                                                value="<?= (int) $role['id_role'] ?>"
                                                                <?= (int) $role['id_role'] === (int) $user['id_role'] ? 'selected' : '' ?>>

                                                                <?= htmlspecialchars($role['role_name']) ?>

                                                            </option>

                                                        <?php endif; ?>

                                                    <?php endforeach; ?>
                                                </select>

                                                <button type="submit" name="ganti_role" class="btn-role">
                                                    Simpan
                                                </button>

                                            </form>


                                            <!-- HAPUS PENGGUNA -->
                                            <form method="POST"
                                                onsubmit="return konfirmasiHapusPengguna(event)">

                                                <input type="hidden" name="id_user"
                                                    value="<?= (int) $user['id_user'] ?>">

                                                <button type="submit" name="hapus_pengguna" class="btn-hapus-user">
                                                    <i class="ph ph-trash"></i>
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    <?php elseif ($isAkunSendiri): ?>

                                        <span class="label-akun-sendiri">Akun Anda</span>

                                    <?php else: ?>

                                        <span class="label-akun-sendiri">Dilindungi</span>

                                    <?php endif; ?>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="empty-data">
                                Belum ada pengguna selain User.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>

        </div>
    </section>


    <!-- DAFTAR ROLE -->
    <section class="produk-section">

        <div class="produk-toolbar">
            <div>
                <h2>Manajemen Role</h2>
                <p>Hapus role untuk memindahkan seluruh penggunanya ke User.</p>
            </div>
        </div>

        <div class="table-container">

            <table class="table-produk">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Role</th>
                        <th>Jumlah Pengguna</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($daftarRole)): ?>

                        <?php $noRole = 1; ?>

                        <?php foreach ($daftarRole as $role): ?>

                            <?php
                            $roleDilindungi = in_array(
                                strtolower($role['role_name']),
                                ['user', 'super admin'],
                                true
                            );
                            ?>

                            <tr>
                                <td><?= $noRole++ ?></td>

                                <td>
                                    <?= htmlspecialchars($role['role_name']) ?>
                                </td>

                                <td>
                                    <?= (int) $role['jumlah_pengguna'] ?> pengguna
                                </td>

                                <td>

                                    <?php if (!$roleDilindungi): ?>

                                        <form method="POST"
                                            onsubmit="return konfirmasiHapusRole(event, <?= htmlspecialchars(json_encode($role['role_name']), ENT_QUOTES) ?>)">

                                            <input type="hidden" name="id_role"
                                                value="<?= (int) $role['id_role'] ?>">

                                            <button type="submit" name="hapus_role" class="btn-hapus-user">
                                                <i class="ph ph-trash"></i>
                                                Hapus Role
                                            </button>

                                        </form>

                                    <?php else: ?>

                                        <span class="label-akun-sendiri">Dilindungi</span>

                                    <?php endif; ?>

                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="4">Belum ada data role.</td>
                        </tr>

                    <?php endif; ?>

                </tbody>
            </table>

        </div> 
    </section>

</section>


        <!-- ==================================
         MODAL BERSAMA PRODUK DAN USER
    ================================== -->

        <div class="modal" id="modalForm">

            <div class="modal-content">

                <button type="button" class="close-modal" onclick="closeModal('modalForm')">

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

        <div class="modal" id="modalEditBarang">
            <div class="modal-content">

                <button type="button" class="close-modal" onclick="closeModal('modalEditBarang')">
                    &times;
                </button>

                <div class="form-produk">
                    <h2>Edit Barang</h2>
                    <p>Perbarui informasi produk.</p>

                    <form method="POST" enctype="multipart/form-data">

                        <input type="hidden" name="edit_id_barang" id="edit_id_barang">

                        <label for="edit_barang">Nama Barang</label>
                        <input type="text" name="edit_barang" id="edit_barang" required>

                        <label for="edit_gambar">Gambar Produk</label>
                        <input type="file" name="edit_gambar" id="edit_gambar" accept=".jpg,.jpeg,.png,.webp">
                        <small>Kosongkan jika tidak ingin mengganti gambar.</small>

                        <label for="edit_jumlah">Jumlah Stok</label>
                        <input type="number" name="edit_jumlah" id="edit_jumlah" min="0" required>

                        <label for="edit_harga">Harga Barang</label>
                        <input type="number" name="edit_harga" id="edit_harga" min="0" step="0.01" required>

                        <label for="edit_id_kategori">Kategori Barang</label>
                        <select name="edit_id_kategori" id="edit_id_kategori" required>
                            <option value="">Pilih Kategori</option>

                            <?php
                            if ($dataKategori) {
                                $dataKategori->data_seek(0);
                                while ($kategori = $dataKategori->fetch_assoc()):
                                    ?>
                                    <option value="<?= (int) $kategori['id_kategori'] ?>">
                                        <?= htmlspecialchars($kategori['nama_kategori']) ?>
                                    </option>
                                    <?php
                                endwhile;
                            }
                            ?>
                        </select>

                        <label for="edit_deskripsi">Deskripsi Produk</label>
                        <textarea name="edit_deskripsi" id="edit_deskripsi"></textarea>

                        <button type="submit" name="edit_produk">
                            Simpan Perubahan
                        </button>

                    </form>
                </div>
            </div>
        </div>


        <footer>
            &copy; <?= date('Y') ?> E-commerce ATK. All rights reserved.
        </footer>

        <!-- JAVASCRIPT -->

        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

        <script src="asset/js/dashboardSuper.js"></script>

        <script src="asset/js/navbar.js"></script>

<script>
// ==========================================
// KONFIRMASI HAPUS PENGGUNA
// ==========================================

function konfirmasiHapusPengguna(event) {
    event.preventDefault();

    const form = event.currentTarget;

    Swal.fire({
        title: 'Hapus pengguna?',
        text: 'Akun ini akan dihapus secara permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
}


// ==========================================
// KONFIRMASI HAPUS ROLE
// ==========================================

function konfirmasiHapusRole(event, namaRole) {
    event.preventDefault();

    const form = event.currentTarget;

    Swal.fire({
        title: 'Hapus role ' + namaRole + '?',
        text: 'Semua pengguna dengan role ini akan otomatis dipindahkan ke User.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus role',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
}
</script>
       
<!-- TOAST -->
<?php if ($toastMessage !== null): ?>
    <script>
        Swal.fire({
            toast: true,
            position: 'top',
            icon: <?= json_encode($toastMessage['icon']) ?>,
            title: <?= json_encode($toastMessage['title']) ?>,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    </>
<?php endif; ?>

</body>

</html>

<?php ob_end_flush(); ?>