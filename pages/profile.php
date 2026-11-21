
<?php
// sementara aja
$user = [
    "nama" => "Nama Pengguna",
    "username" => "username",
    "email" => "user@email.com",
    "telepon" => "08xxxxxxxxxx",
    "alamat" => "Belum ada alamat",
    "role" => "Customer",
    "tanggal_bergabung" => "21 November 2026"
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Saya - Marketplace ATK</title>

    <link rel="stylesheet" href="../asset/css/profile.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

    <main class="profile-container">

        <!-- HEADER -->
        <header class="profile-header">
            <h2>INFORMASI PRIBADI</h2>

            <a href="dashboardUser.php" class="btn-close" title="Kembali ke Dashboard">
                <i class="ph ph-x"></i>
            </a>
        </header>

        <div class="profile-content">

            <!-- KOLOM KIRI -->
            <section class="profile-sidebar">

                <div class="avatar-section">
                    <div class="avatar-wrapper">
                        <img
                            src="../asset/gambar/bahan/profile.png"
                            alt="Foto Profil"
                            id="profileAvatar"
                        >
                    </div>

                    <h3><?= htmlspecialchars($user["nama"]) ?></h3>
                    <span class="role-badge">
                        <?= htmlspecialchars($user["role"]) ?>
                    </span>
                </div>

                <div class="sidebar-divider"></div>

                <div class="account-info">
                    <div class="account-item">
                        <i class="ph ph-at"></i>
                        <span><?= htmlspecialchars($user["email"]) ?></span>
                    </div>

                    <div class="account-item">
                        <i class="ph ph-phone"></i>
                        <span><?= htmlspecialchars($user["telepon"]) ?></span>
                    </div>

                    <div class="account-item">
                        <i class="ph ph-calendar-blank"></i>
                        <span><?= htmlspecialchars($user["tanggal_bergabung"]) ?></span>
                    </div>
                </div>

            </section>

            <!-- KOLOM KANAN -->
            <section class="profile-form-section">

                <form id="profileForm">

                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            value="<?= htmlspecialchars($user["nama"]) ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="<?= htmlspecialchars($user["username"]) ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input
                            type="email"
                            id="email"
                            value="<?= htmlspecialchars($user["email"]) ?>"
                            readonly
                            class="input-readonly"
                        >
                    </div>

                    <div class="form-group">
                        <label for="telepon">No. Telepon</label>
                        <input
                            type="tel"
                            id="telepon"
                            name="telepon"
                            value="<?= htmlspecialchars($user["telepon"]) ?>"
                        >
                    </div>

                    <div class="form-group form-group-address">
                        <label for="alamat">Alamat</label>
                        <textarea
                            id="alamat"
                            name="alamat"
                            rows="4"
                        ><?= htmlspecialchars($user["alamat"]) ?></textarea>
                    </div>

                    <div class="form-divider"></div>

                    <div class="form-actions">
                        <button type="button" class="btn-cancel" id="btnCancel">
                            Batal
                        </button>

                        <button type="submit" class="btn-save">
                            <i class="ph ph-floppy-disk"></i>
                            Simpan
                        </button>
                    </div>

                </form>

            </section>

        </div>
    </main>

    <script src="../asset/js/profile.js"></script>
</body>
</html>
