inputNama = document.getElementById("nma");
pass = document.getElementById("pwd")
inputPwd = document.getElementById("pwd1");
inputPwd2 = document.getElementById("pwd2");
nama = document.getElementById("nama");
pwd = document.getElementById("alert");
sama = document.getElementById("confirm");
mata = document.getElementById("closeEye")
tombol = document.getElementById('gas');
loginForm = document.getElementById("login");
registerForm = document.getElementById("register");



function showForm(formId, groupClass) {

    // Mengambil semua elemen berdasarkan class kelompok
    let semuaForm = document.querySelectorAll("." + groupClass);

    // Menghilangkan class active dari semua elemen
    semuaForm.forEach(function(form) {
        form.classList.remove("active");
    });

    // Mengambil elemen berdasarkan ID yang diklik
    let form = document.getElementById(formId);

    // Menambahkan class active
    if (form) {
        form.classList.add("active");
    }
}


function cekNama() {
    if (inputNama.value.length <= 8) {
        nama.innerHTML = "*Nama harus lebih dari 8 karakter";
    } else {
        nama.innerHTML = "";
    }
}

function cekPassword() {
    
    if (inputPwd.value.length < 8) {
        pwd.innerHTML = "Password minimal 8 karakter";
    } else {
        pwd.innerHTML = "";
    }
}
function cekSama(){
    if (inputPwd.value != inputPwd2.value){
        sama.innerHTML = "password tidak sesuai";
    } else{
        sama.innerHTML = "";
    }
}

function showPw(){
    if(pass.type == "password"){
        pass.type = "text";
        mata.src = "asset/gambar/openEye.png"
        
    }else{
        pass.type = "password";
        mata.src = "asset/gambar/closeEye.png"
    }
}

function tampilAlert() {
    if (typeof dataAlert !== "undefined") {
        Swal.fire({
            icon: dataAlert.icon,
            title: dataAlert.title,
            text: dataAlert.text,
            confirmButtonText: 'Oke'
        });
    }
}

tampilAlert();


mata.onclick = showPw;
inputNama.oninput = cekNama;
inputPwd.oninput = cekPassword;
inputPwd2.oninput = cekSama;
tombol.onclick = tampilAlert;
