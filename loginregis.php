<?php

// ==========================================
// PROSES REGISTER
// ==========================================

if (isset($_POST['action']) && $_POST['action'] === 'create') {

    // Mengambil dan membersihkan data
    $nama = trim($_POST['nma'] ?? '');
    $username = trim($_POST['nma3'] ?? '');
    $telephone = trim($_POST['tlp'] ?? '');
    $email = strtolower(trim($_POST['mail'] ?? ''));
    $password = $_POST['pw'] ?? '';

    // Validasi input kosong
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

        // Validasi format email
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Email Tidak Valid!",
            "text" => "Silakan masukkan alamat email yang benar."
        ];

        // Validasi panjang password
    } elseif (strlen($password) < 8) {

        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Password Terlalu Pendek!",
            "text" => "Password harus memiliki minimal 8 karakter."
        ];

        $_SESSION['active_form'] = 'register';

        header("Location: form.php");
        exit;
    } else {

        // ==========================================
        // CEK USERNAME
        // ==========================================

        $cekUsername = $db->prepare(
            "SELECT id_pengguna FROM pengguna WHERE username = ?"
        );

        $cekUsername->bind_param("s", $username);
        $cekUsername->execute();
        $hasilUsername = $cekUsername->get_result();

        // ==========================================
        // CEK EMAIL
        // ==========================================

        $cekEmail = $db->prepare(
            "SELECT id_pengguna FROM pengguna WHERE email = ?"
        );

        $cekEmail->bind_param("s", $email);
        $cekEmail->execute();
        $hasilEmail = $cekEmail->get_result();

        // Username dan email sudah digunakan
        if ($hasilUsername->num_rows > 0 && $hasilEmail->num_rows > 0) {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Pendaftaran Gagal!",
                "text" => "Username dan email sudah digunakan."
            ];

            // Username sudah digunakan
        } elseif ($hasilUsername->num_rows > 0) {

            $_SESSION['toast'] = [
                "icon" => "error",
                "title" => "Username Sudah Digunakan!",
                "text" => "Silakan gunakan username yang berbeda."
            ];

            // Email sudah digunakan
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
            // SIMPAN DATA PENGGUNA
            // ==========================================

            $insert = $db->prepare(
                "INSERT INTO pengguna
                (nama, username, telephone, email, password)
                VALUES (?, ?, ?, ?, ?)"
            );

            $insert->bind_param(
                "sssss",
                $nama,
                $username,
                $telephone,
                $email,
                $passwordHash
            );

            if ($insert->execute()) {

                $_SESSION['toast'] = [
                    "icon" => "success",
                    "title" => "Akun Berhasil Dibuat!",
                    "text" => "Pendaftaran berhasil. Silakan login menggunakan akun kamu."
                ];

            } else {

                // Penanganan jika terjadi duplikasi saat INSERT
                if ($insert->errno === 1062) {

                    $_SESSION['toast'] = [
                        "icon" => "error",
                        "title" => "Data Sudah Digunakan!",
                        "text" => "Username atau email sudah terdaftar."
                    ];

                } else {

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

    header("Location: form.php");
    exit;
}


// ==========================================
// PROSES LOGIN
// ==========================================

if (isset($_POST['action']) && $_POST['action'] === 'login') {

    $nama = trim($_POST['nama'] ?? '');
    $password = $_POST['pwd'] ?? '';

    // Mencari pengguna berdasarkan username atau email
    $stmt = $db->prepare(
        "SELECT id_pengguna, nama, username, password
         FROM pengguna
         WHERE username = ? OR email = ?
         LIMIT 1"
    );

    $stmt->bind_param("ss", $nama, $nama);
    $stmt->execute();

    $hasil = $stmt->get_result();
    $user = $hasil->fetch_assoc();

    // Memeriksa password
    if ($user && password_verify($password, $user['password'])) {

        // Membuat session baru setelah login berhasil
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id_pengguna'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama'];

        // Toast berhasil
        $_SESSION['toast'] = [
            "icon" => "success",
            "title" => "Selamat Datang Kembali!",
            "text" => "Login berhasil. Senang melihat kamu kembali!"
        ];

        // Redirect setelah toast
        $_SESSION['redirect_after_toast'] = true;

    } else {

        // Toast gagal
        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Login Gagal!",
            "text" => "Username atau password yang kamu masukkan tidak sesuai."
        ];
    }

    $stmt->close();

    header("Location: form.php");
    exit;
}

?>