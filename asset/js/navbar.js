
const menu = document.getElementById("menu");
const main = document.querySelector(".main");

menu.addEventListener("click", function () {
    main.classList.toggle("show");
});
if (helpMenu) {
    helpMenu.addEventListener("click", function (e) {
        e.preventDefault();

        document.dispatchEvent(
            new CustomEvent("bukaHelp")
        );
    });
}

// MENU FAVORIT
if (favoritMenu) {
    favoritMenu.addEventListener("click", function () {
        document.dispatchEvent(
            new CustomEvent("bukaFavorite")
        );
    });
}

// MENU HAMBURGER
if (menu && main) {
    menu.addEventListener("click", function () {
        main.classList.toggle("show");
    });
}

// FUNGSI MENGUBAH BAHASA
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
// DROPDOWN PROFILE
const profile = document.getElementById("profile");
const profileMenu = document.getElementById("profileMenu");

if (profile && profileMenu) {

    profile.addEventListener("click", function (event) {
        event.stopPropagation();
        profileMenu.classList.toggle("show");
    });

    document.addEventListener("click", function (event) {
        if (!profileMenu.contains(event.target) &&
            !profile.contains(event.target)) {
            profileMenu.classList.remove("show");
        }
    });

}
