function onLoad() {
    document.querySelector('.menuIcon').addEventListener('click', function () {
        document.getElementsByTagName('body')[0].classList.toggle('menuShown');
        document.querySelector('.menu').classList.toggle('hidden');
        document.querySelector('.menuItem').classList.toggle('hidden');
    });
}