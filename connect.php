
<?php

// Memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "123";
$dbname = "finalProject";

// Koneksi database
$db = new mysqli($servername, $username, $password, $dbname);

// Pemeriksaan koneksi
if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}

?>
