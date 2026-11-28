
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// MODE PENGEMBANGAN
define('DEVELOPMENT_MODE', true);

function wajibLogin(array $roleDiizinkan): void
{
    // Mode pengembangan
    if (DEVELOPMENT_MODE && isset($_GET['dev_role'])) {

        $roleDev = (int) $_GET['dev_role'];

        if (in_array($roleDev, $roleDiizinkan, true)) {

            // Simpan identitas development ke session
            $_SESSION['user_id'] = $_SESSION['user_id'] ?? 'dev';
            $_SESSION['id_role'] = $roleDev;

            return;
        }

        http_response_code(403);
        exit("Role development tidak diizinkan untuk halaman ini.");
    }

    // Pastikan pengguna sudah login
    if (!isset($_SESSION['user_id'], $_SESSION['id_role'])) {
        header("Location: form.php");
        exit;
    }

    $idRole = (int) $_SESSION['id_role'];

    // Periksa hak akses
    if (!in_array($idRole, $roleDiizinkan, true)) {

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
