
document.addEventListener("DOMContentLoaded", function () {

    // =================================
    // NAVBAR USER
    // =================================

    const userMenu = document.querySelector(".user-navbar #menu");
    const userMain = document.querySelector(".user-navbar .main");

    const favoritMenu = document.getElementById("favoritMenu");
    const languageMenu = document.getElementById("languageMenu");
    const helpMenu = document.getElementById("helpMenu");

    // HAMBURGER USER
    if (userMenu && userMain) {
        userMenu.addEventListener("click", function () {
            userMain.classList.toggle("show");
        });
    }

    // MENU FAVORIT
    if (favoritMenu) {
        favoritMenu.addEventListener("click", function (e) {
            e.preventDefault();

            document.dispatchEvent(
                new CustomEvent("bukaFavorite")
            );
        });
    }

    // MENU HELP
    if (helpMenu) {
        helpMenu.addEventListener("click", function (e) {
            e.preventDefault();

            document.dispatchEvent(
                new CustomEvent("bukaHelp")
            );
        });
    }

    // =================================
    // NAVBAR ADMIN
    // =================================

    const adminMenu = document.getElementById("adminMenu");
    const adminSidebar = document.querySelector(".admin-sidebar");
    const sidebarOverlay = document.querySelector(".sidebar-overlay");
    const sidebarClose = document.querySelector(".sidebar-close");

    function bukaSidebarAdmin() {
        if (!adminSidebar) return;

        adminSidebar.classList.add("show");

        if (sidebarOverlay) {
            sidebarOverlay.classList.add("show");
        }

        if (adminMenu) {
            adminMenu.setAttribute("aria-expanded", "true");
        }
    }

    function tutupSidebarAdmin() {
        if (!adminSidebar) return;

        adminSidebar.classList.remove("show");

        if (sidebarOverlay) {
            sidebarOverlay.classList.remove("show");
        }

        if (adminMenu) {
            adminMenu.setAttribute("aria-expanded", "false");
        }
    }

    // HAMBURGER ADMIN
    if (adminMenu && adminSidebar) {
        adminMenu.addEventListener("click", function () {
            if (adminSidebar.classList.contains("show")) {
                tutupSidebarAdmin();
            } else {
                bukaSidebarAdmin();
            }
        });
    }

    // TOMBOL X SIDEBAR ADMIN
    if (sidebarClose) {
        sidebarClose.addEventListener("click", tutupSidebarAdmin);
    }

    // KLIK OVERLAY
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener("click", tutupSidebarAdmin);
    }

    // TUTUP DENGAN ESCAPE
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            tutupSidebarAdmin();
        }
    });

    // =================================
    // SISTEM BAHASA USER
    // =================================

    function terapkanBahasa(bahasa) {
        document.documentElement.lang = bahasa;

        document.querySelectorAll("[data-id][data-en]").forEach(function (element) {
            element.textContent = element.getAttribute(
                bahasa === "en" ? "data-en" : "data-id"
            );
        });

        const pencarian = document.getElementById("pencarian");

        if (pencarian) {
            pencarian.placeholder = pencarian.getAttribute(
                bahasa === "en" ? "data-en" : "data-id"
            );
        }

        document.dispatchEvent(
            new CustomEvent("bahasaDiubah")
        );
    }

    // MEMUAT BAHASA TERSIMPAN
    const bahasaTersimpan = localStorage.getItem("bahasa") || "id";

    terapkanBahasa(bahasaTersimpan);

    // MENU LANGUAGE
    if (languageMenu) {
        languageMenu.addEventListener("click", function (e) {
            e.preventDefault();

            const bahasaSekarang = localStorage.getItem("bahasa") || "id";
            const bahasaTujuan = bahasaSekarang === "id" ? "en" : "id";

            const namaBahasa = bahasaTujuan === "en"
                ? "English"
                : "Bahasa Indonesia";

            const konfirmasi = confirm(
                `Apakah Anda yakin ingin mengubah bahasa ke ${namaBahasa}?`
            );

            if (konfirmasi) {
                localStorage.setItem("bahasa", bahasaTujuan);
                terapkanBahasa(bahasaTujuan);

                alert(
                    bahasaTujuan === "en"
                        ? "Language successfully changed to English!"
                        : "Bahasa berhasil diubah ke Bahasa Indonesia!"
                );
            }
        });
    }

});
