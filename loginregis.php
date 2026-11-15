<?php
$alert = null;

// PROSES REGISTER
if (isset($_POST['submit'])) {

    $nama = $_POST['nma'];
    $username = $_POST['nma3'];
    $telephone = $_POST['tlp'];
    $email = $_POST['mail'];
    $password = $_POST['pw'];

    $data = "INSERT INTO pengguna (nama, username, telephone, email, password)
             VALUES ('$nama', '$username', '$telephone', '$email', '$password')";

    if ($db->query($data)) {
        $alert = [
            "icon" => "success",
            "title" => "Registrasi Berhasil!",
            "text" => "Data berhasil disimpan."
        ];
    } else {
        $alert = [
            "icon" => "error",
            "title" => "Registrasi Gagal!",
            "text" => $db->error
        ];
    }
}
if (isset($_POST['gas'])) {

    $nama = $_POST['nama'];
    $password = $_POST['pwd'];

    $tod = "SELECT * FROM pengguna
            WHERE (username = '$nama' OR email = '$nama')
            AND password = '$password'";

    $berhasil = $db->query($tod);

    if ($berhasil && $berhasil->num_rows > 0) {

        $alert = [
            "icon" => "success",
            "title" => "Login Berhasil!",
            "text" => "Selamat datang, $nama!"
        ];

    } else {

        $alert = [
            "icon" => "error",
            "title" => "Login Gagal!",
            "text" => "Username atau password salah."
        ];
    }
}
?>