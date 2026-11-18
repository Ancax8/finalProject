
const menu = document.getElementById('menu');
const list = document.getElementById('main');

menu.addEventListener("click", () => {
    list.classList.toggle('show');
});
