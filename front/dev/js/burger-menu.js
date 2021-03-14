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
            (this.menu.style.display === 'none')? this.menu.style.display = 'block' : this.menu.style.display = 'none';
        });
    }
}

var initMenu = document.querySelector('#burger-button');
if(initMenu) {
    new BurgerMenu(initMenu);
}