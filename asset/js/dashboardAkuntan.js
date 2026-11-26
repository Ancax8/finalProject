
document.addEventListener("DOMContentLoaded", function () {

    // =================================
    // SIDEBAR AKUNTAN
    // =================================

    const menu = document.getElementById("akuntanMenu");
    const sidebar = document.getElementById("akuntanSidebar");
    const closeButton = document.getElementById("akuntanSidebarClose");
    const overlay = document.getElementById("akuntanOverlay");

    function bukaSidebar() {
        sidebar?.classList.add("show");
        overlay?.classList.add("show");
        menu?.setAttribute("aria-expanded", "true");
    }

    function tutupSidebar() {
        sidebar?.classList.remove("show");
        overlay?.classList.remove("show");
        menu?.setAttribute("aria-expanded", "false");
    }

    menu?.addEventListener("click", function () {
        if (sidebar.classList.contains("show")) {
            tutupSidebar();
        } else {
            bukaSidebar();
        }
    });

    closeButton?.addEventListener("click", tutupSidebar);
    overlay?.addEventListener("click", tutupSidebar);


    // =================================
    // MODAL PEMASUKAN DAN PENGELUARAN
    // =================================

    const btnPemasukan = document.getElementById("btnTambahPemasukan");
    const btnPengeluaran = document.getElementById("btnTambahPengeluaran");

    const modalPemasukan = document.getElementById("modalPemasukan");
    const modalPengeluaran = document.getElementById("modalPengeluaran");

    function bukaModal(modal) {
        if (!modal) {
            console.error("Modal tidak ditemukan!");
            return;
        }

        modal.classList.add("active");
        document.body.style.overflow = "hidden";
    }

    function tutupModal(modal) {
        if (!modal) return;

        modal.classList.remove("active");

        if (!document.querySelector(".finance-modal.active")) {
            document.body.style.overflow = "";
        }
    }

    // Tombol tambah pemasukan
    btnPemasukan?.addEventListener("click", function () {
        bukaModal(modalPemasukan);
    });

    // Tombol tambah pengeluaran
    btnPengeluaran?.addEventListener("click", function () {
        bukaModal(modalPengeluaran);
    });


    // =================================
    // TOMBOL TUTUP MODAL
    // =================================

    document.querySelectorAll("[data-close]").forEach(function (button) {
        button.addEventListener("click", function () {
            const modal = document.getElementById(button.dataset.close);
            tutupModal(modal);
        });
    });

    // Klik area luar modal
    document.querySelectorAll(".finance-modal").forEach(function (modal) {
        modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                tutupModal(modal);
            }
        });
    });


    // =================================
    // TANGGAL OTOMATIS
    // =================================

    const sekarang = new Date();
    const tahun = sekarang.getFullYear();
    const bulan = String(sekarang.getMonth() + 1).padStart(2, "0");
    const tanggal = String(sekarang.getDate()).padStart(2, "0");

    const tanggalHariIni = `${tahun}-${bulan}-${tanggal}`;

    const inputTanggalPemasukan =
        document.getElementById("tanggalPemasukan");

    const inputTanggalPengeluaran =
        document.getElementById("tanggalPengeluaran");

    if (inputTanggalPemasukan) {
        inputTanggalPemasukan.value = tanggalHariIni;
    }

    if (inputTanggalPengeluaran) {
        inputTanggalPengeluaran.value = tanggalHariIni;
    }


    // =================================
    // VALIDASI FORM
    // =================================

    function validasiForm(form, jenis) {
        if (!form) return;

        form.addEventListener("submit", function (event) {
            event.preventDefault();

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const nominal = Number(
                form.querySelector('[name="nominal"]').value
            );

            if (!Number.isSafeInteger(nominal) || nominal <= 0) {
                alert("Nominal harus berupa angka positif yang valid.");
                return;
            }

            alert(
                `Form ${jenis} berhasil divalidasi!\n` +
                "Penyimpanan database akan dibuat pada tahap berikutnya."
            );
        });
    }

    validasiForm(
        document.getElementById("formPemasukan"),
        "pemasukan"
    );

    validasiForm(
        document.getElementById("formPengeluaran"),
        "pengeluaran"
    );


    // =================================
    // TOMBOL ESCAPE
    // =================================

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            tutupModal(modalPemasukan);
            tutupModal(modalPengeluaran);
            tutupSidebar();
        }
    });

});
