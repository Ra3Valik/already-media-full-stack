export default function initBurger() {
    const burger = document.querySelector('.burger');
    const menu = document.querySelector('.desktop-menu-wrap');
    const body = document.body;

    if (!burger || !menu) return;

    burger.addEventListener('click', () => {
        const isOpen = menu.classList.toggle('menu-open');
        burger.classList.toggle('menu-open', isOpen);
        body.classList.toggle('menu-open', isOpen);
    });
}
