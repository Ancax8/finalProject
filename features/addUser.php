<?php
$dataRole = $db->query("SELECT * FROM Roles ORDER BY id_role ASC");
?>

<div class="form-user">

    <div class="form-user-header">
        <h2>Tambah User</h2>
        <p>Masukkan informasi pengguna baru.</p>
    </div>

    <form
        action="dashboardSuper.php"
        method="POST"
        enctype="multipart/form-data"
        class="form-user-body">

        <div class="form-user-group">
            <label for="fullname">Nama Lengkap</label>
            <input
                type="text"
                id="fullname"
                name="fullname"
                placeholder="Masukkan nama lengkap"
                required>
        </div>

        <div class="form-user-group">
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                placeholder="Masukkan email"
                required>
        </div>

        <div class="form-user-group">
            <label for="username">Username</label>
            <input
                type="text"
                id="username"
                name="username"
                placeholder="Masukkan username"
                required>
        </div>

        <div class="form-user-group">
            <label for="password">Password Awal</label>
            <input
                type="password"
                id="password"
                name="password"
                placeholder="Masukkan password awal"
                required>
        </div>

        <div class="form-user-group">
            <label for="gambar">Gambar Profil</label>
            <input
                type="file"
                id="gambar"
                name="gambar"
                accept=".jpg,.jpeg,.png,.webp">

            <small>Gambar profil bersifat opsional.</small>
        </div>

        <div class="form-user-group">
            <label for="id_role">Role Pengguna</label>
            <select id="id_role" name="id_role" required>
                <option value="">Pilih Role</option>

                <?php if ($dataRole && $dataRole->num_rows > 0): ?>
                    <?php while ($role = $dataRole->fetch_assoc()): ?>
                        <option value="<?= (int) $role['id_role'] ?>">
                            <?= htmlspecialchars($role['role_name']) ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>

            </select>
        </div>

        <div class="form-user-actions">
            <button
                type="button"
                class="btn-user-cancel"
                onclick="closeModal('modalForm')">
                Batal
            </button>

            <button
                type="submit"
                name="tambah_user"
                class="btn-user-submit">
                Simpan User
            </button>
        </div>

    </form>

</div>