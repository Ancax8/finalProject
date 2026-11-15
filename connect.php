<?php
$servername = "localhost";
$username = "anca";
$password = "001122";
$dbname = "finalProject";

$db = new mysqli($servername, $username, $password, $dbname);

if ($db->connect_error) {
    die("Koneksi gagal: " . $db->connect_error);
}

?>
