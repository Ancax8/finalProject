
// ==========================================
// 1. MENGAMBIL ELEMEN HTML
// ==========================================

// Login
const passLogin = document.getElementById("pwd");
const eyeLogin = document.getElementById("closeEye");

// Register
const inputNama = document.getElementById("nma");
const inputPwd = document.getElementById("pwd1");
const inputPwd2 = document.getElementById("pwd2");

const eyeRegister = document.getElementById("eyeRegister");
const eyeConfirm = document.getElementById("eyeConfirm");

// Elemen pesan validasi Register
const errorNama = document.getElementById("nama");
const errorPassword = document.getElementById("alert");
const errorConfirm = document.getElementById("confirm");


// ==========================================
// 2. PINDAH FORM LOGIN DAN REGISTER
// ==========================================

function showForm(formId, groupClass) {
    const semuaForm = document.querySelectorAll("." + groupClass);

    semuaForm.forEach(function (form) {
        form.classList.remove("active");
    });

    const form = document.getElementById(formId);

    if (form) {
        form.classList.add("active");
    }
}


// ==========================================
// 3. SHOW / HIDE PASSWORD
// ==========================================

function togglePassword(input, eye) {
    if (!input || !eye) return;

    if (input.type === "password") {
        input.type = "text";
        eye.src = "asset/gambar/openEye.png";
    } else {
        input.type = "password";
        eye.src = "asset/gambar/closeEye.png";
    }
}

// Password Login
if (eyeLogin) {
    eyeLogin.onclick = function () {
        togglePassword(passLogin, eyeLogin);
    };
}

// Password Register
if (eyeRegister) {
    eyeRegister.onclick = function () {
        togglePassword(inputPwd, eyeRegister);
    };
}

// Konfirmasi Password
if (eyeConfirm) {
    eyeConfirm.onclick = function () {
        togglePassword(inputPwd2, eyeConfirm);
    };
}


// ==========================================
// 4. VALIDASI NAMA
// ==========================================

function cekNama() {
    if (!inputNama || !errorNama) return;

    if (inputNama.value.trim().length <= 8) {
        errorNama.innerHTML = "*Nama harus lebih dari 8 karakter";
    } else {
        errorNama.innerHTML = "";
    }
}


// ==========================================
// 5. VALIDASI PASSWORD
// ==========================================

function cekPassword() {
    if (!inputPwd || !errorPassword) return;

    if (inputPwd.value.length < 8) {
        errorPassword.innerHTML = "Password minimal 8 karakter";
    } else {
        errorPassword.innerHTML = "";
    }

    // Periksa kembali konfirmasi jika password berubah
    cekSama();
}


// ==========================================
// 6. VALIDASI KONFIRMASI PASSWORD
// ==========================================

function cekSama() {
    if (!inputPwd || !inputPwd2 || !errorConfirm) return;

    if (inputPwd2.value !== inputPwd.value) {
        errorConfirm.innerHTML = "Password tidak sesuai";
    } else {
        errorConfirm.innerHTML = "";
    }
}


// ==========================================
// 7. MENAMPILKAN SWEETALERT DARI PHP
// ==========================================

function tampilAlert() {
    if (
        typeof dataAlert !== "undefined" &&
        typeof Swal !== "undefined"
    ) {
        Swal.fire({
            icon: dataAlert.icon,
            title: dataAlert.title,
            text: dataAlert.text,
            confirmButtonText: "Oke"
        });
    }
}


// ==========================================
// 8. EVENT LISTENER
// ==========================================

if (inputNama) {
    inputNama.addEventListener("input", cekNama);
}

if (inputPwd) {
    inputPwd.addEventListener("input", cekPassword);
}

if (inputPwd2) {
    inputPwd2.addEventListener("input", cekSama);
}

// Tampilkan notifikasi dari PHP ketika halaman dimuat
tampilAlert();
