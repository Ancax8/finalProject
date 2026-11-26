<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function wajibLogin(array $roleDiizinkan): void
{
    // Pastikan pengguna sudah login
    if (!isset($_SESSION['user_id'], $_SESSION['id_role'])) {
        header("Location: form.php");
        exit;
    }

    $idRole = (int) $_SESSION['id_role'];

    // Periksa hak akses
    if (!in_array($idRole, $roleDiizinkan, true)) {

        // Arahkan kembali ke dashboard sesuai role sebenarnya
        switch ($idRole) {
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
                session_destroy();
                header("Location: form.php");
                exit;
        }

        header("Location: " . $tujuan);
        exit;
    }
}
