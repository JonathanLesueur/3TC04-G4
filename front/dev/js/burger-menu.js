class BurgerMenu {
    constructor(button) {
        this.buttonMenu = button,
        this.menu = document.querySelector('#nav-menu');

        if(this.buttonMenu && this.menu) {
            this.init();
        }
    }

    init() {
        this.buttonMenu.addEventListener('click', (e) => {
            this.menu.classList.toggle('active');
        });
    }
}

var initMenu = document.querySelector('#burger-button');
if(initMenu) {
    new BurgerMenu(initMenu);
}