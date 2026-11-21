
const profileForm = document.getElementById("profileForm");
const btnCancel = document.getElementById("btnCancel");

// Simpan data awal untuk fungsi batal
const initialValues = {};

if (profileForm) {
    const fields = profileForm.querySelectorAll(
        "input:not([readonly]), textarea"
    );

    fields.forEach(field => {
        initialValues[field.name] = field.value;
    });
}

// Tombol batal
if (btnCancel) {
    btnCancel.addEventListener("click", function () {
        const kembali = confirm(
            "Batalkan perubahan dan kembali ke dashboard?"
        );

        if (kembali) {
            window.location.href = "dashboardUser.php";
        }
    });
}

// Tombol simpan sementara
if (profileForm) {
    profileForm.addEventListener("submit", function (event) {
        event.preventDefault();

        alert(
            "Tampilan frontend sudah siap. Fitur penyimpanan akan dihubungkan ke database pada tahap berikutnya."
        );
    });
}
