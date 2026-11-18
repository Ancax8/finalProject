
<?php

// ==========================================
// PROSES REGISTER
// ==========================================

if (isset($_POST['action']) && $_POST['action'] === 'create') {

    // Mengambil data dari form Register
    $nama = $_POST['nma'];
    $username = $_POST['nma3'];
    $telephone = $_POST['tlp'];
    $email = $_POST['mail'];
    $password = $_POST['pw'];

    // Query untuk menyimpan data pengguna
    $data = "INSERT INTO pengguna (nama, username, telephone, email, password)
             VALUES ('$nama', '$username', '$telephone', '$email', '$password')";

    // Menjalankan query
    if ($db->query($data)) {

        // Register berhasil

    } else {

        // Register gagal
        // Error dapat diperiksa melalui $db->error

    }
}


// ==========================================
// PROSES LOGIN
// ==========================================

if (isset($_POST['action']) && $_POST['action'] === 'login') {

    $nama = $_POST['nama'];
    $password = $_POST['pwd'];

    $tod = "SELECT * FROM pengguna
            WHERE (username = '$nama' OR email = '$nama')
            AND password = '$password'";

    $berhasil = $db->query($tod);

    if ($berhasil && $berhasil->num_rows > 0) {

        $user = $berhasil->fetch_assoc();

        // Session pengguna
        $_SESSION['user_id'] = $user['id_pengguna'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama'] = $user['nama'];

        // Notifikasi berhasil
        $_SESSION['toast'] = [
            "icon" => "success",
            "title" => "Selamat Datang Kembali!",
            "text" => "Login berhasil. Senang melihat kamu kembali!"
        ];

        // Tandai bahwa pengguna harus diarahkan ke dashboard
        $_SESSION['redirect_after_toast'] = true;

        header("Location: form.php");
        exit;

    } else {

        // Notifikasi gagal
        $_SESSION['toast'] = [
            "icon" => "error",
            "title" => "Login Gagal!",
            "text" => "Username atau password yang kamu masukkan tidak sesuai."
        ];

        header("Location: form.php");
        exit;
    }
}


?>
