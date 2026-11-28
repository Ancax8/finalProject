
<?php

// Pastikan session aktif
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// PROSES REGISTER
// ==========================================

if (isset($_POST['action']) && $_POST['action'] === 'create') {

    // Ambil dan bersihkan data
    $nama      = trim($_POST['nma'] ?? '');
    $username  = trim($_POST['nma3'] ?? '');
    $telephone = trim($_POST['tlp'] ?? '');
    $email     = strtolower(trim($_POST['mail'] ?? ''));
    $password  = $_POST['pw'] ?? '';

    // Validasi data kosong
    if (
        $nama === '' ||
        $username === '' ||
        $telephone === '' ||
        $email === '' ||
        $password === ''
    ) {
        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Data Belum Lengkap!",
            "text" => "Silakan lengkapi seluruh formulir pendaftaran."
        ];

    // Validasi email
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Email Tidak Valid!",
            "text" => "Silakan masukkan alamat email yang benar."
        ];

    // Validasi nomor telepon
    } elseif (!preg_match('/^[0-9]{10,15}$/', $telephone)) {
        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Nomor Telepon Tidak Valid!",
            "text" => "Nomor telepon harus terdiri dari 10 sampai 15 digit."
        ];

    // Validasi password
    } elseif (strlen($password) < 8) {
        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Password Terlalu Pendek!",
            "text" => "Password harus memiliki minimal 8 karakter."
        ];

    } else {

        // ==========================================
        // CEK USERNAME
        // ==========================================

        $cekUsername = $db->prepare(
            "SELECT id_user FROM Users WHERE username = ? LIMIT 1"
        );

        $cekUsername->bind_param("s", $username);
        $cekUsername->execute();
        $hasilUsername = $cekUsername->get_result();

        // ==========================================
        // CEK EMAIL
        // ==========================================

        $cekEmail = $db->prepare(
            "SELECT id_user FROM Users WHERE email = ? LIMIT 1"
        );

        $cekEmail->bind_param("s", $email);
        $cekEmail->execute();
        $hasilEmail = $cekEmail->get_result();

        // ==========================================
        // VALIDASI DUPLIKASI
        // ==========================================

        if ($hasilUsername->num_rows > 0 && $hasilEmail->num_rows > 0) {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Pendaftaran Gagal!",
                "text" => "Username dan email sudah digunakan."
            ];

        } elseif ($hasilUsername->num_rows > 0) {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Username Sudah Digunakan!",
                "text" => "Silakan gunakan username yang berbeda."
            ];

        } elseif ($hasilEmail->num_rows > 0) {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Email Sudah Terdaftar!",
                "text" => "Silakan gunakan alamat email yang berbeda."
            ];

        } else {

            // ==========================================
            // HASH PASSWORD
            // ==========================================

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // ==========================================
            // SIMPAN DATA USER
            // ==========================================

            $insert = $db->prepare(
                "INSERT INTO Users
                (id_role, fullname, telephone, email, username, password)
                VALUES (4, ?, ?, ?, ?, ?)"
            );

            $insert->bind_param(
                "sssss",
                $nama,
                $telephone,
                $email,
                $username,
                $passwordHash
            );

            if ($insert->execute()) {

                $_SESSION['toast'] = [
                    "icon" => "success",
                    "title" => "Akun Berhasil Dibuat!",
                    "text" => "Pendaftaran berhasil. Silakan login menggunakan akun kamu."
                ];

            } else {

                if ($insert->errno === 1062) {
                    $_SESSION['toast'] = [
                        "icon" => "error",
                        "title" => "Data Sudah Digunakan!",
                        "text" => "Username atau email sudah terdaftar."
                    ];
                } else {
                    error_log("Register error: " . $insert->error);

                    $_SESSION['toast'] = [
                        "icon" => "error",
                        "title" => "Pendaftaran Gagal!",
                        "text" => "Terjadi kesalahan saat menyimpan data."
                    ];
                }
            }

            $insert->close();
        }

        $cekUsername->close();
        $cekEmail->close();
    }

    $_SESSION['active_form'] = 'register';

    header("Location: form.php");
    exit;
}


// ==========================================
// PROSES LOGIN BERDASARKAN ROLE
// ==========================================

if (isset($_POST['action']) && $_POST['action'] === 'login') {

    $usernameOrEmail = trim($_POST['nama'] ?? '');
    $password = $_POST['pwd'] ?? '';

    // Cari akun berdasarkan username atau email
    $stmt = $db->prepare(
        "SELECT
            u.id_user,
            u.id_role,
            u.fullname,
            u.email,
            u.username,
            u.password,
            u.status,
            r.role_name
         FROM Users u
         INNER JOIN Roles r ON u.id_role = r.id_role
         WHERE u.username = ? OR u.email = ?
         LIMIT 1"
    );

    if (!$stmt) {
        error_log("Login prepare error: " . $db->error);

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Terjadi Kesalahan!",
            "text" => "Sistem tidak dapat memproses login."
        ];

        header("Location: form.php");
        exit;
    }

    $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
    $stmt->execute();

    $hasil = $stmt->get_result();
    $user = $hasil->fetch_assoc();

    // ==========================================
    // VALIDASI AKUN
    // ==========================================

    if (!$user) {

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Login Gagal!",
            "text" => "Username atau password tidak sesuai."
        ];

    } elseif ((int)$user['status'] !== 1) {

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Akun Tidak Aktif!",
            "text" => "Silakan hubungi administrator."
        ];

    } elseif (!password_verify($password, $user['password'])) {

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Login Gagal!",
            "text" => "Username atau password tidak sesuai."
        ];

    } else {

        // ==========================================
        // LOGIN BERHASIL
        // ==========================================

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['fullname'];
        $_SESSION['id_role'] = $user['id_role'];
        $_SESSION['role_name'] = $user['role_name'];

        // ==========================================
        // REDIRECT BERDASARKAN ROLE
        // ==========================================

        switch ((int)$user['id_role']) {

            case 1:
                $tujuan = "dashboardSuper.php";
                break;

            case 2:
                $tujuan = "dashboardAdmin.php";
                break;

            case 3:
                $tujuan = "dashboardAkuntan.php";
                break;

            case 4:
                $tujuan = "pages/dashboardUser.php";
                break;

            default:
                $tujuan = null;
                break;
        }

        if ($tujuan !== null) {

            $_SESSION['toast'] = [
                "icon" => "success",
                "title" => "Login Berhasil!",
                "text" => "Selamat datang, " . $user['fullname'] . "!"
            ];

            $_SESSION['redirect_after_toast'] = $tujuan;

        } else {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Role Tidak Dikenali!",
                "text" => "Silakan hubungi administrator."
            ];
        }
    }

    $stmt->close();

    header("Location: form.php");
    exit;
}

?>
