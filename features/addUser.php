<?php
// Ambil daftar role dari database
$dataRole = $db->query("SELECT * FROM Roles ORDER BY id_role ASC");
?>

<div class="form-produk">
    <div class="form-header">
        <h2>Tambah User</h2>
        <p>Masukkan informasi pengguna baru.</p>
    </div>

    <form action="dashboardSuper.php"
          method="POST"
          enctype="multipart/form-data">

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text"
                   name="fullname"
                   placeholder="Masukkan nama lengkap"
                   required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email"
                   name="email"
                   placeholder="Masukkan email"
                   required>
        </div>

        <div class="form-group">
            <label>Username</label>
            <input type="text"
                   name="username"
                   placeholder="Masukkan username"
                   required>
        </div>

        <div class="form-group">
            <label>Password Awal</label>
            <input type="password"
                   name="password"
                   placeholder="Masukkan password awal"
                   required>
        </div>

        <div class="form-group">
            <label>Gambar Profil</label>
            <input type="file"
                   name="gambar"
                   accept="image/*">
            <small>Gambar profil bersifat opsional.</small>
        </div>

        <div class="form-group">
            <label>Role Pengguna</label>
            <select name="id_role" required>
                <option value="">Pilih Role</option>

                <?php while ($role = $dataRole->fetch_assoc()) : ?>
                    <option value="<?= (int)$role['id_role'] ?>">
                        <?= htmlspecialchars($role['role_name']) ?>
                    </option>
                <?php endwhile; ?>

            </select>
        </div>

        <div class="form-action">
            <button type="button"
                    class="btn-batal"
                    onclick="tutupModal()">
                Batal
            </button>

            <button type="submit"
                    name="tambah_user"
                    class="btn-simpan">
                Simpan User
            </button>
        </div>

    </form>
</div>