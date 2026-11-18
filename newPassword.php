
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 25px;
            font-family: Arial, sans-serif;
            background-color: #f1f5f9;
        }

        .new-password-container {
            width: 100%;
            max-width: 460px;
            padding: 40px;
            background-color: white;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            margin-bottom: 35px;
        }

        .form-header h2 {
            font-size: 28px;
            color: #15243b;
            margin-bottom: 10px;
        }

        .form-header p {
            color: #64748b;
            font-size: 14px;
            line-height: 1.6;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 9px;
            margin-bottom: 22px;
        }

        .form-group label {
            font-size: 15px;
            font-weight: 600;
            color: #334155;
        }

        .pass_box {
            position: relative;
            width: 100%;
        }

        .pass_box input {
            width: 100%;
            padding: 15px 48px 15px 15px;
            border: 1px solid #dbe3ed;
            border-radius: 9px;
            outline: none;
            background-color: #f8fafc;
            color: #1e293b;
            font-size: 15px;
            transition: 0.2s;
        }

        .pass_box input:focus {
            border-color: #173b70;
            background-color: white;
        }

        .pass_box img {
            position: absolute;
            width: 21px;
            height: 21px;
            object-fit: contain;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
        }

        .new-password-container button {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 9px;
            background-color: #173b70;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .new-password-container button:hover {
            background-color: #102d59;
        }

        .back-login {
            margin-top: 25px;
            text-align: center;
            font-size: 14px;
        }

        .back-login a {
            color: #173b70;
            font-weight: 600;
            text-decoration: none;
        }

        .back-login a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .new-password-container {
                padding: 28px 22px;
            }

            .form-header h2 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <main class="new-password-container">

        <form id="newPasswordForm">

            <div class="form-header">
                <h2>Buat Password Baru</h2>
                <p>Silakan masukkan password baru Anda dan konfirmasi password tersebut.</p>
            </div>

            <!-- Password Baru -->
            <div class="form-group">
                <label for="newPwd">Password Baru</label>

                <div class="pass_box">
                    <input
                        type="password"
                        id="newPwd"
                        name="new_password"
                        placeholder="Masukkan password baru"
                        required
                    >

                    <img
                        src="asset/gambar/closeEye.png"
                        alt="Tampilkan password"
                        id="eyeNewPwd"
                    >
                </div>

                <span class="error-message" id="errorPassword"></span>
            </div>

            <!-- Konfirmasi Password -->
            <div class="form-group">
                <label for="confirmPwd">Konfirmasi Password Baru</label>

                <div class="pass_box">
                    <input
                        type="password"
                        id="confirmPwd"
                        name="confirm_password"
                        placeholder="Ulangi password baru"
                        required
                    >

                    <img
                        src="asset/gambar/closeEye.png"
                        alt="Tampilkan password"
                        id="eyeConfirmPwd"
                    >
                </div>

                <span class="error-message" id="errorConfirm"></span>
            </div>

            <button type="submit">
                Simpan Password
            </button>

            <p class="back-login">
                <a href="form.php">Kembali ke halaman Login</a>
            </p>

        </form>

    </main>

    <script>
        // Mengambil elemen HTML
        const newPwd = document.getElementById("newPwd");
        const confirmPwd = document.getElementById("confirmPwd");

        const eyeNewPwd = document.getElementById("eyeNewPwd");
        const eyeConfirmPwd = document.getElementById("eyeConfirmPwd");

        const errorPassword = document.getElementById("errorPassword");
        const errorConfirm = document.getElementById("errorConfirm");

        const newPasswordForm = document.getElementById("newPasswordForm");


        // Fungsi Show / Hide Password
        function togglePassword(input, eye) {
            if (input.type === "password") {
                input.type = "text";
                eye.src = "asset/gambar/openEye.png";
            } else {
                input.type = "password";
                eye.src = "asset/gambar/closeEye.png";
            }
        }

        eyeNewPwd.onclick = function () {
            togglePassword(newPwd, eyeNewPwd);
        };

        eyeConfirmPwd.onclick = function () {
            togglePassword(confirmPwd, eyeConfirmPwd);
        };


        // Validasi Password
        function cekPassword() {
            if (newPwd.value.length < 8) {
                errorPassword.innerHTML = "Password minimal 8 karakter";
            } else {
                errorPassword.innerHTML = "";
            }

            cekKonfirmasi();
        }


        // Validasi Konfirmasi Password
        function cekKonfirmasi() {
            if (confirmPwd.value === "") {
                errorConfirm.innerHTML = "";
                return;
            }

            if (confirmPwd.value !== newPwd.value) {
                errorConfirm.innerHTML = "Password tidak sesuai";
            } else {
                errorConfirm.innerHTML = "";
            }
        }


        // Event Listener
        newPwd.addEventListener("input", cekPassword);
        confirmPwd.addEventListener("input", cekKonfirmasi);


        // Validasi ketika tombol Simpan ditekan
        newPasswordForm.addEventListener("submit", function (event) {
            if (newPwd.value.length < 8 ||
                newPwd.value !== confirmPwd.value) {

                event.preventDefault();

                cekPassword();
                cekKonfirmasi();
            }
        });
    </script>

</body>
</html>
