<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include "connect.php";
include "loginregis.php";
// $toast = $_SESSION['toast'] ?? null;
// unset($_SESSION['toast']);
$toast = $_SESSION['toast'] ?? null;
$redirectAfterToast = $_SESSION['redirect_after_toast'] ?? false;

unset($_SESSION['toast']);
unset($_SESSION['redirect_after_toast']);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="asset/css/login.css">

</head>

<body>
    <main class="auth-container">

        <!-- PANEL KIRI -->
        <section class="branding-panel">
            <div class="brand-content">
                <div class="brand-logo">
                    <h2>Marketplace ATK</h2>
                    <p>logo</p>
                </div>

                <div class="brand-description">
                    <h1>KATA KATA NANTI</h1>
                    <p>
                        BLA BLA BLA!
                    </p>
                </div>

                <div class="brand-footer">
                    <p>Marketplace ATK &copy; 2026</p>
                </div>
            </div>
        </section>

        <!-- PANEL KANAN -->
        <section class="auth-panel">

            <!-- LOGIN -->
            <div class="login active" id="login">
                <form method="POST" action="form.php">

                    <div class="form-header">
                        <h2>LOGIN</h2>
                        <p>Silakan masukkan username dan password Anda.</p>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="nama" placeholder="Masukkan username" required>
                    </div>

                    <div class="form-group">
                        <label for="pwd">Password</label>

                        <div class="pass_box">
                            <input type="password" id="pwd" name="pwd" placeholder="Masukkan password" required>

                            <img src="asset/gambar/bahan/closeEye.png" alt="Tampilkan password" id="closeEye">
                        </div>
                    </div>
                    <div class="login-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember_me" id="remember_me">
                            <span>Remember me</span>
                        </label>

                        <div class="forgot-password">
                            <a href="newPassword.php">Lupa password?</a>
                        </div>
                    </div>
                    <button type="submit" name="action" value="login">
                        LOGIN
                    </button>

                    <p class="form-switch">
                        Belum memiliki akun?
                        <a href="#" onclick="showForm('register', 'login'); return false;">
                            Daftar sekarang
                        </a>
                    </p>

                </form>
            </div>

            <!-- REGISTER -->
            <div class="login" id="register">
                <form method="POST" action="form.php">

                    <div class="form-header">
                        <h2>Buat Akun Baru</h2>
                        <p>Daftarkan diri Anda untuk mulai berbelanja.</p>
                    </div>

                    <div class="form-group">
                        <label for="nma">Nama Lengkap</label>
                        <input type="text" id="nma" name="nma" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="form-group">
                        <label for="nma3">Username</label>
                        <input type="text" id="nma3" name="nma3" placeholder="Buat username" required>
                    </div>

                    <div class="form-group">
                        <label for="tlp">Nomor Telepon</label>
                        <input type="tel" id="tlp" name="tlp" placeholder="Masukkan nomor telepon" maxlength="15"
                            required>
                    </div>

                    <div class="form-group">
                        <label for="mail">Email</label>
                        <input type="email" id="mail" name="mail" placeholder="Masukkan email" required>
                    </div>

                    <!-- PASSWORD -->
                    <div class="form-group">
                        <label for="pwd1">Password</label>
                        <div class="pass_box">
                            <input type="password" id="pwd1" name="pw" placeholder="Buat password" required>
                            <img src="asset/gambar/closeEye.png" alt="Tampilkan password" id="eyeRegister">
                        </div>
                    </div>

                    <!-- KONFIRMASI PASSWORD -->
                    <div class="form-group">
                        <label for="pwd2">Konfirmasi Password</label>
                        <div class="pass_box">
                            <input type="password" id="pwd2" placeholder="Ulangi password" required>
                            <img src="asset/gambar/closeEye.png" alt="Tampilkan password" id="eyeConfirm">
                        </div>
                    </div>
                    <button type="submit" name="action" value="create">
                        Buat Akun
                    </button>

                    <p class="form-switch">
                        Sudah memiliki akun?
                        <a href="#" onclick="showForm('login', 'login'); return false;">
                            Masuk sekarang
                        </a>
                    </p>

                </form>
            </div>

        </section>

    </main>
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>

    <!-- <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script> -->
<?php if ($toast !== null): ?>
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: <?= json_encode($toast['icon']) ?>,
        title: <?= json_encode($toast['title']) ?>,
        text: <?= json_encode($toast['text']) ?>,
        showConfirmButton: false,
        showCloseButton: true,
        timer: 2500,
        timerProgressBar: true
    }).then(() => {
        <?php if ($redirectAfterToast): ?>
            window.location.href = "layout/navbar.html";
        <?php endif; ?>
    });
</script>
<?php endif; ?>

<script src="asset/js/login.js"></script>
</body>

</html>