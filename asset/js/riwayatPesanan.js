
document.addEventListener("DOMContentLoaded", function () {

    const orderCards = Array.from(
        document.querySelectorAll(".order-card")
    );

    const statusButtons = document.querySelectorAll(".status-btn");
    const searchOrder = document.getElementById("searchOrder");
    const filterDate = document.getElementById("filterDate");

    const emptyOrder = document.getElementById("emptyOrder");
    const paginationInfo = document.getElementById("paginationInfo");
    const currentPageText = document.getElementById("currentPage");

    const prevPage = document.getElementById("prevPage");
    const nextPage = document.getElementById("nextPage");

    const detailOverlay = document.getElementById("detailOverlay");
    const detailContent = document.getElementById("detailContent");
    const closeDetail = document.getElementById("closeDetail");

    const perPage = 2;

    let activeStatus = "Semua";
    let currentPage = 1;
    let filteredOrders = [...orderCards];

    // FILTER PESANAN
    function filterOrders() {

        const keyword = searchOrder.value.toLowerCase().trim();
        const selectedDate = filterDate.value;

        filteredOrders = orderCards.filter(card => {

            const status = card.dataset.status;
            const products = card.dataset.products;
            const date = card.dataset.date;

            const matchStatus =
                activeStatus === "Semua" ||
                status === activeStatus;

            const matchSearch =
                products.includes(keyword);

            const matchDate =
                selectedDate === "" ||
                date === selectedDate;

            return matchStatus && matchSearch && matchDate;
        });

        currentPage = 1;
        renderOrders();
    }

    // TAMPILKAN PESANAN
    function renderOrders() {

        const totalOrders = filteredOrders.length;
        const totalPages = Math.ceil(totalOrders / perPage);

        orderCards.forEach(card => {
            card.style.display = "none";
        });

        if (totalOrders === 0) {

            emptyOrder.hidden = false;
            paginationInfo.textContent = "Tidak ada pesanan";
            currentPageText.textContent = "0";

            prevPage.disabled = true;
            nextPage.disabled = true;

            return;
        }

        emptyOrder.hidden = true;

        const start = (currentPage - 1) * perPage;
        const end = start + perPage;

        filteredOrders.slice(start, end).forEach(card => {
            card.style.display = "block";
        });

        paginationInfo.textContent =
            `Menampilkan ${currentPage} dari ${totalPages} halaman`;

        currentPageText.textContent = currentPage;

        prevPage.disabled = currentPage === 1;
        nextPage.disabled = currentPage === totalPages;
    }

    // FILTER STATUS
    statusButtons.forEach(button => {

        button.addEventListener("click", function () {

            statusButtons.forEach(btn => {
                btn.classList.remove("active");
            });

            this.classList.add("active");

            activeStatus = this.dataset.status;

            filterOrders();
        });

    });

    // PENCARIAN PRODUK
    searchOrder.addEventListener("input", filterOrders);

    // FILTER TANGGAL
    filterDate.addEventListener("change", filterOrders);

    // PAGINATION
    prevPage.addEventListener("click", function () {

        if (currentPage > 1) {
            currentPage--;
            renderOrders();
        }

    });

    nextPage.addEventListener("click", function () {

        const totalPages = Math.ceil(
            filteredOrders.length / perPage
        );

        if (currentPage < totalPages) {
            currentPage++;
            renderOrders();
        }

    });

    // BUKA DETAIL PESANAN
    document.querySelectorAll(".btn-detail").forEach(button => {

        button.addEventListener("click", function () {

            const card = this.closest(".order-card");

            const nomor = card.querySelector("h3").textContent;
            const tanggal = card.querySelector(".order-date").textContent;
            const status = card.dataset.status;

            const produk = card.querySelector(".product-preview");
            const total = card.querySelector(".order-total strong");

            detailContent.innerHTML = `
                <div class="detail-content-item">
                    <span>Nomor Pesanan</span>
                    <strong>${nomor.replace("No. Pesanan:", "").trim()}</strong>
                </div>

                <div class="detail-content-item">
                    <span>Tanggal Pesanan</span>
                    <strong>${tanggal.replace("Tanggal Pesanan:", "").trim()}</strong>
                </div>

                <div class="detail-content-item">
                    <span>Produk</span>
                    <strong>${produk.textContent.trim()}</strong>
                </div>

                <div class="detail-content-item">
                    <span>Status</span>
                    <strong>${status}</strong>
                </div>

                <div class="detail-content-item">
                    <span>Total Pembayaran</span>
                    <strong>${total.textContent}</strong>
                </div>
            `;

            detailOverlay.classList.add("show");

        });

    });

    // TUTUP DETAIL
    closeDetail.addEventListener("click", function () {
        detailOverlay.classList.remove("show");
    });

    detailOverlay.addEventListener("click", function (event) {
        if (event.target === detailOverlay) {
            detailOverlay.classList.remove("show");
        }
    });

    // TAMPILAN AWAL
    renderOrders();

});
