class Tabs {
    constructor(tabs) {
        this.menus = tabs.querySelectorAll('.tab'),
        this.contents = tabs.querySelectorAll('.tab-content'),
        this.currentTabType = 'rapidposts',
        this.currentTabContent = Array.from(this.contents)[0],
        this.currentTabMenu = Array.from(this.menus)[0];
        
        if(this.menus && this.contents) {
            this.init();
        }
    }
    init() {
        this.menus.forEach(menu => {
            menu.addEventListener('click', this.changeTab.bind(this, menu));
        });
    }
    changeTab(menu) {
        this.currentTabContent.classList.remove('active');
        this.currentTabMenu.classList.remove('active');

        menu.classList.add('active');

        this.currentTabMenu = menu;
        this.currentTabType = menu.getAttribute('data-tab');

        this.currentTabContent = this.getContent(this.currentTabType);
        this.currentTabContent.classList.add('active');
    }
    getContent(type) {
        var found = Array.from(this.contents).find(el => el.getAttribute('data-tab-content') === type);
        return found;
    }

    getMenu(type) {
        var found = Array.from(this.menus).find(el => el.getAttribute('data-tab') === type);
        return found;
    }
}

const initTabs = document.querySelector('.tabs-container');
if(initTabs) {
    new Tabs(initTabs);
    
}