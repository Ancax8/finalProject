document.addEventListener("DOMContentLoaded", function () {
    // =========================================
    // NAVIGASI TAB SUPER ADMIN + HAMBURGER
    // =========================================
    const tabs = document.querySelectorAll(".super-feature-tab");
    const menuItems = document.querySelectorAll(".super-menu-item");
    const panels = document.querySelectorAll(".super-feature-panel");
    const menuToggle = document.getElementById("superMenuToggle");
    const menu = document.getElementById("superMenu");

    function aktifkanFitur(target) {
        tabs.forEach(function (tab) {
            const aktif = tab.dataset.target === target;
            tab.classList.toggle("active", aktif);
            tab.setAttribute("aria-selected", aktif ? "true" : "false");
        });

        menuItems.forEach(function (item) {
            const aktif = item.dataset.target === target;
            item.classList.toggle("active", aktif);
            item.setAttribute("aria-current", aktif ? "page" : "false");
        });

        panels.forEach(function (panel) {
            const aktif = panel.dataset.panel === target;
            panel.hidden = !aktif;
            panel.classList.toggle("active", aktif);
        });
    }

    tabs.forEach(function (tab) {
        tab.addEventListener("click", function () {
            aktifkanFitur(tab.dataset.target);
        });
    });

    menuItems.forEach(function (item) {
        item.addEventListener("click", function () {
            aktifkanFitur(item.dataset.target);
            tutupMenuSuper();
        });
    });

    function bukaMenuSuper() {
        if (!menu || !menuToggle) return;
        menu.classList.add("show");
        menuToggle.setAttribute("aria-expanded", "true");
    }

    function tutupMenuSuper() {
        if (!menu || !menuToggle) return;
        menu.classList.remove("show");
        menuToggle.setAttribute("aria-expanded", "false");
    }

    if (menuToggle) {
        menuToggle.addEventListener("click", function () {
            const sedangTerbuka = menu && menu.classList.contains("show");
            sedangTerbuka ? tutupMenuSuper() : bukaMenuSuper();
        });
    }

    document.addEventListener("click", function (event) {
        if (
            menu && menuToggle &&
            !menu.contains(event.target) &&
            !menuToggle.contains(event.target)
        ) {
            tutupMenuSuper();
        }
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") tutupMenuSuper();
    });

    aktifkanFitur("barang");

    // =========================================
    // PENCARIAN BARANG (FILTER LANGSUNG)
    // =========================================
    const searchProduk = document.getElementById("searchProduk");
    const barisProduk = document.querySelectorAll("#bodyProduk .baris-produk");
    const hasilKosong = document.getElementById("hasilPencarianKosong");

    if (searchProduk) {
        searchProduk.addEventListener("input", function () {
            const kataKunci = searchProduk.value.trim().toLocaleLowerCase("id");
            let jumlahCocok = 0;

            barisProduk.forEach(function (baris) {
                const nama = baris.querySelector(".nama-produk");
                const cocok = nama &&
                    nama.textContent.toLocaleLowerCase("id").includes(kataKunci);

                baris.hidden = !cocok;
                if (cocok) jumlahCocok++;
            });

            if (hasilKosong) {
                hasilKosong.hidden = jumlahCocok !== 0 || barisProduk.length === 0;
            }
        });
    }

    // =========================================
    // MODAL FORM TAMBAH PRODUK / PENGGUNA
    // =========================================
    window.openModal = function (id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.add("active");
        document.body.style.overflow = "hidden";
    };

    window.closeModal = function (id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.remove("active");

        if (!document.querySelector(".modal.active")) {
            document.body.style.overflow = "";
        }
    };

    window.switchModalContent = function (type) {
        const produk = document.getElementById("kontenAddProduk");
        const user = document.getElementById("kontenAddUser");

        if (produk) produk.style.display = type === "produk" ? "block" : "none";
        if (user) user.style.display = type === "user" ? "block" : "none";

        window.openModal("modalForm");
    };

    window.tutupModal = function () {
        window.closeModal("modalForm");
    };

    document.querySelectorAll(".modal").forEach(function (modal) {
        modal.addEventListener("click", function (event) {
            if (event.target === modal) window.closeModal(modal.id);
        });
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape") {
            document.querySelectorAll(".modal.active").forEach(function (modal) {
                window.closeModal(modal.id);
            });
        }
    });
});
function bukaEditBarang(button) {
    document.getElementById('edit_id_barang').value =
        button.dataset.id;

    document.getElementById('edit_barang').value =
        button.dataset.nama;

    document.getElementById('edit_jumlah').value =
        button.dataset.stok;

    document.getElementById('edit_harga').value =
        button.dataset.harga;

    document.getElementById('edit_id_kategori').value =
        button.dataset.kategori;

    document.getElementById('edit_deskripsi').value =
        button.dataset.deskripsi;

    openModal('modalEditBarang');
}