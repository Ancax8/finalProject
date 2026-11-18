
<?php

// Memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi database
$servername = "localhost";
$username = "anca";
$password = "001122";
$dbname = "finalproject";

// Koneksi database
$db = new mysqli($servername, $username, $password, $dbname);

// Pemeriksaan koneksi
if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}

?>
