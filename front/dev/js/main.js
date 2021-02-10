class AjaxRequest {
    constructor(t) {
        return this.loading = !1, this.options = {
            emitter: null,
            request: null,
            method: "POST",
            url: "",
            data: {},
            strData: "",
            dataType: "json",
            onSuccess: null,
            beforeSend: null
        }, this.init(t), this;
    }
    init(t) {
        for (var e in t)
        this.options.hasOwnProperty(e) && (this.options[e] = t[e]);
        return this.options.request || (this.options.request = new XMLHttpRequest), this;
    }
    send(t) {
        var e = this;
        return t || e.buildStrData(), e.abort(), e.beforeSend(), e.options.request.open(e.options.method, e.options.url), e.options.request.setRequestHeader("Content-type", "application/x-www-form-urlencoded"), e.options.request.setRequestHeader("X-Requested-With", "XMLHttpRequest"), e.options.request.send(t || e.options.strData), e.options.request.onreadystatechange = function () {
            4 === e.options.request.readyState && 404 === e.options.request.status ? (e.loading = !1, console.error("AjaxRequest - Page introuvable")) : 4 === e.options.request.readyState && 200 === e.options.request.status && (e.loading = !1, e.options.onSuccess && e.options.onSuccess(e.options.request.response || e.options.request.responseText, e));
        }, e;
    }
    reload() {
        this.send();
    }
    clearDatas() {
        this.data = {};
    }
    setDatas(t) {
        return this.options.data = t || {}, this;
    }
    addData(t, e) {
        return this.options.data[t] = e, this;
    }
    removeData(t) {
        this.options.data[t] && delete this.options.data[t];
    }
    buildStrData() {
        this.options.strData = "";
        for (var t in this.options.data)
        if ("[object Array]" !== Object.prototype.toString.apply(this.options.data[t]))
        this.addDataUrl(t, this.options.data[t]);
        else
        for (var e = this.options.data[t], o = 0; o < e.length; o++)
        this.addDataUrl(t + "[]", e[o]);
    }
    addDataUrl(t, e) {
        this.options.strData += (this.options.strData.length > 0 ? "&" : "") + t + "=" + e;
    }
    beforeSend() {
        return this.loading = !0, this.options.beforeSend ? this.options.beforeSend() : null;
    }
    abort() {
        return this.options.request && this.options.request.abort(), this.loading = !1, this;
    }
    isLoading() {
        return this.loading;
    }
    getNbLayers() {
        return this.nbLayers;
    }
}
class Tiles {
    constructor(datas) {
        this.datas = datas,
        this.currentTile = false,
        this.tilesList = document.querySelector('.tiles-list'),
        this.currentCategory = false,
        this.currentTheme = 0;
    }
    setTheme(theme) {
        this.currentTheme = theme;
        this.resetList();
        this.setCategory('default');
    }
    setCategory(category) {
        this.currentCategory = category;
        this.resetList();
        this.makeList();
    }
    resetList() {
        this.tilesList.innerHTML = '';
    }
    makeList() {
        var objet = this;
        var datas = this.datas[this.currentTheme].categorys[this.currentCategory];
        if(datas) {
            datas.assets.forEach((el) => {
                var tile = new Tile(objet.currentCategory, objet.currentTheme, el);
                objet.tilesList.appendChild(tile.element);
            });
        }
        
    }
}
class Tile {
    constructor(category, theme, picture) {
        this.category = category,
        this.theme = theme,
        this.picture = picture,
        this.name = '',
        this.element = false;
        if(this.category && this.theme && this.picture) {
            this.makeTile();
        }
    }
    makeTile() {
        var tile = document.createElement('div');
            tile.setAttribute('class', 'tile');
        var img = document.createElement('img');
            img.setAttribute('loading', 'lazy');
            img.setAttribute('src', `//jolstatic.fr/dofus/Modules/MapMaker/assets/category_${this.theme}/${this.category}/${this.picture}.png`);

            tile.appendChild(img);
            this.element = tile;

        return this;
    }
}

class Layers {
    constructor(list) {
        this.layersList = list,
        this.layersView = [],
        this.currentLayer = 1;
        this.nbLayers = map.getNbLayers();
        if(this.layersList) {
            this.init();
        }
    }
    init() {
        for(var i = this.nbLayers; i >= 1; i--) {
            this.makeLayerView(i);
        }
    }
    makeLayerView(i) {
        var layer = document.createElement('div');
                layer.setAttribute('class', 'layer');
            var number = document.createElement('div');
                number.innerText = i;
                number.setAttribute('class', 'layer-number');
            var view = document.createElement('div');
                view.setAttribute('class', 'layer-view');
            var eye = document.createElement('i');
                eye.setAttribute('class', 'jol-icon jol-icon-eye');
                view.appendChild(eye);
            if(i == this.currentLayer) {
                number.classList.add('active');
                view.classList.add('active');
            }
            layer.appendChild(number);
            layer.appendChild(view);
            this.layersView[i] = layer;
            this.layersList.appendChild(layer);
        
            number.addEventListener('click', () => {
                this.changeLayer(i);
            });
            view.addEventListener('click', () => {
                this.toggleView(i);
            });
        return layer;
    }
    drawLayer(i) {
        var layer = document.createElement('div');
            layer.setAttribute('class', 'layer layer_'+i);
            if(i == 1) {
                layer.classList.add('visible');

                /*var img = document.createElement('img');
                    img.setAttribute('class', 'layer-content');
                    img.setAttribute('src', 'https://i.imgur.com/AzUBe7Y.png');
                    layer.appendChild(img);*/
            }
        return layer;
    }
    changeLayer(i) {
        if(this.currentLayer != i) {
            this.layersView[this.currentLayer].querySelector('.layer-number').classList.remove('active');
            this.layersView[i].querySelector('.layer-number').classList.add('active');
            this.currentLayer = i;
            map.changeCurrentLayer(i);
        }
        
    }
    toggleView(i) {
        var objet = this;
        var cells = document.querySelectorAll('.cell');
        cells.forEach((cell) => {
            var current = cell.querySelector('.layer_'+i);
                current.classList.toggle('visible');
                objet.layersView[i].querySelector('.layer-view').classList.toggle('active');
        });
    }
}

class Menus {
    constructor() {
        this.menuChoices = document.querySelector('.map-type-choice'),
        this.menuOptions = document.querySelector('.options-menus'),
        this.tilesContainer = document.querySelector('.tiles-container'),
        this.mapTypeChoices = this.menuChoices.querySelectorAll('.choice'),
        this.optionsButtons = document.querySelectorAll('.option-button'),
        this.optionsMenus = this.menuOptions.querySelectorAll('.menu'),
        this.menuHeader = document.querySelector('.options-menus-choices'),
        this.currentOption = false,
        this.currentOptionMenu = false,
        this.currentMapType = false,
        this.currentMenu = this.menuChoices;

        if(this.menuChoices && this.menuOptions && this.tilesContainer && this.mapTypeChoices && this.optionsButtons && this.optionsMenus && this.menuHeader) {
            this.init();
        }
    }
    init() {
        var objet = this;
        this.mapTypeChoices.forEach((choice) => {
            choice.addEventListener('click', () => {
                objet.mapChangeType(choice);
            });
        });
        this.optionsButtons.forEach((button) => {
            button.addEventListener('click', () => {
                objet.optionChoice(button);
            });
        });
    }
    mapChangeType(choix) {
        var type = choix.getAttribute('data-type');

        categorys.init(type);
        themes.init();

        if(type === 'pvp') {
            categorys.disable(); themes.disable();
        } else {
            categorys.active(); themes.active();
        }

        this.changeCurrentMenu(this.tilesContainer);
        
    }
    optionChoice(button) {
        var objet = this;
        var type = button.getAttribute('data-option');
        this.menuOptions.classList.add('active');
        this.optionsMenus.forEach((menu) => {
            if(menu.classList.contains(type)) {
                if(menu == objet.currentOptionMenu) {
                    objet.changeCurrentMenu(objet.tilesContainer);
                    objet.changeCurrentOption(button, false);
                } else {
                    objet.changeCurrentMenu(objet.menuOptions);
                    objet.changeCurrentOption(button, menu);
                }
            }
        })
        
    }
    changeCurrentMenu(menu) {
        this.currentMenu.classList.remove('active');
        this.currentMenu = menu;
        this.currentMenu.classList.add('active');
    }
    changeCurrentOption(button, menu) {
        if(this.currentOption) {
            this.currentOption.classList.remove('active');
        }
        
        this.currentOption = button;
        this.currentOption.classList.add('active');
        if(this.currentOptionMenu) {
            this.currentOptionMenu.classList.remove('active');
        }
        
        this.currentOptionMenu = menu;
        this.currentOptionMenu.classList.add('active');
        menu.classList.add('active');
    }
}
class Category {
    constructor(type, name) {
        this.type = type,
        this.name = name,
        this.element = false;

        if(this.type && this.name) {
            this.make();
        }
    }
    getType() {
        return this.type;
    }
    getName() {
        return this.name;
    }
    getElement() {
        return this.element;
    }
    make() {
        var option = document.createElement('option');
            option.setAttribute('value', this.type);
            option.innerText = this.name;
            if(this.type === 'default') {
                option.selected = true;
                option.setAttribute('selected', 'selected');
            }
        this.element = option;

        return this;
    }
}
class Categorys {
    constructor(datas) {
        this.categorysInput = document.querySelector('.js-select-categorys'),
        this.categorys = [],
        this.currentCategory = 'default',
        this.currentThemeId = 0,
        this.datas = datas,
        this.mapType = false;

        if(this.datas) {
            console.log('Categories OK');
        }
    }
    init(type) {

        this.mapType = type;
        this.categorysInput.addEventListener('change', () => {
            this.changeCategory();
        });
        this.makeCategorys();
    }
    makeCategorys(theme = false) {
        this.categorysInput.innerHTML = '';
        var entries;
        (theme) ? entries = this.datas[theme].categorys : entries = this.datas[this.currentThemeId].categorys;
        for (const [key, value] of Object.entries(entries)) {
            var cat = new Category(key, value.name);
            this.categorys.push(cat);
            this.categorysInput.appendChild(cat.getElement());
        }
        var cat = new Category('default','Choisir une catégorie');
        this.categorysInput.prepend(cat.element);
    }
    changeCategory() {
        this.currentCategory = this.categorysInput.value;
        tiles.setCategory(this.currentCategory);
    }
    setTheme(id) {
        this.currentThemeId = id;
        this.makeCategorys(this.currentThemeId);
    }
    disable() {
        this.categorysInput.disabled = true;
    }
    active() {
        this.categorysInput.disabled = false;
    }
}

class Theme {
    constructor(id, name, desc) {
        this.id = id,
        this.name = name,
        this.desc = desc,
        this.element = false;

        if(this.id && this.name && this.desc) {
            this.make();
        }
    }
    make() {
        var option = document.createElement('option');
            option.setAttribute('value', this.id);
            option.innerText = this.name;

        this.element = option;

        return this;
    }
    getId() {
        return this.id;
    }
    getName() {
        return this.name;
    }
    getDesc() {
        return this.desc;
    }
    getElement() {
        return this.element;
    }
}

class Themes {
    constructor(datas) {
        this.themesInput = document.querySelector('.js-select-themes'),
        this.themes = [],
        this.currentTheme = 0,
        this.datas = datas;

        if(this.datas) {
            console.log('Themes OK');
        }
    }
    init() {
        this.themesInput.addEventListener('change', () => {
            this.changeTheme();
        });
        this.makeThemes();
        categorys.setTheme(this.currentTheme);
    }
    changeTheme() {
        this.currentTheme = this.themesInput.value;
        categorys.setTheme(this.currentTheme);
        tiles.setTheme(this.currentTheme);
    }
    makeThemes() {
        this.themesInput.innerHTML = '';

        for (const [key, value] of Object.entries(this.datas)) {
            var theme = new Theme(key, value.name, value.desc); 
            this.themes.push(theme);
            this.themesInput.appendChild(theme.getElement());
        }
        var theme = new Theme('default', 'Choisir un thème', ''); 
    }
    disable() {
        this.themesInput.disabled = true;
    }
    active() {
        this.themesInput.disabled = false;
    }
}

class Move {
    constructor(element) {
        this.target = element;
        this.mousePosition = {},
        this.offset = [0,0],
        this.isDown = false;

        if(this.target) {
            this.init();
        }
    }
    init() {
        this.target.addEventListener('mousedown', this.mouseDown.bind(this), true);
        this.target.addEventListener('mouseup', this.mouseUp.bind(this), true);
        this.target.addEventListener('mousemove', this.mouseMove.bind(this), true);
        this.target.addEventListener('contextmenu', function (e) {
            e.preventDefault();
        }, false);
        this.target.addEventListener('oncontextmenu', function () {
            window.event.returnValue = false;
        }, false);
    }
    mouseDown(event) {
        if(event.buttons == 2) {
            this.isDown = true;
            this.offset = [
                0 - event.clientX,
                0 - event.clientY
            ];
        }
    }
    mouseUp(event) {
        this.isDown = false;
    }
    mouseMove(event) {
        event.preventDefault();
        if(this.isDown) {
            this.mousePosition = {
                x : event.clientX,
                y : event.clientY
            };
            this.target.style.left = (this.mousePosition.x + this.offset[0]) + 'px';
            this.target.style.top =(this.mousePosition.y + this.offset[1]) + 'px';
        }
    }
}

class Carte {
    constructor(map) {
        this.map = map,
        this.mapElements = [],
        this.nbLines = 20,
        this.cellsByLine = 20,
        this.currentLayer = 1,
        this.nbLayers = 10,
        this.isSymetric = false,
        this.isPVP = false,
        this.isCustom = false,
        this.isDown = false,
        this.datas = false;

        if(this.map) {
            this.init();
        }
    }
    init() {
        this.getDatas();
    }
    getDatas() {
        var objet = this;
        new AjaxRequest({
            url: "https://dofus.jeuxonline.info/map-maker/getDatas",
            dataType: "json",
            data: {},
            onSuccess(response) {
                var result = JSON.parse(response);
                objet.datas = result;

                themes = new Themes(objet.datas);
                categorys = new Categorys(objet.datas);
                tiles = new Tiles(objet.datas);
                menus = new Menus();

                layers = new Layers(pageContent.layersList);

                objet.drawMap();
            }
        }).send();
    }
    drawMap() {
        for(var i = 0; i <= this.nbLines; i++) {
            var line = this.drawLine(i);
            this.map.appendChild(line);

            for(var j = 0; j <= this.cellsByLine; j++) {
                var cell = this.drawCell(i, j);
                line.appendChild(cell);
            }
            this.mapElements.push(line);
        }
    }
    drawLine(x) {
        var line = document.createElement('div');
        line.setAttribute('class', 'line');
        line.setAttribute('data-x', x);
        return line;
    }
    drawCell(x,y) {
        var objet = this;
        var cell = document.createElement('div');
            cell.setAttribute('class', 'cell');
            cell.setAttribute('data-x', x);
            cell.setAttribute('data-y', y);
            if(x%2 == 0) {
                (y%2 == 0)? cell.classList.add('even') : cell.classList.add('odd');
            } else {
                (y%2 == 0)? cell.classList.add('odd') : cell.classList.add('even');
            }

        var base = document.createElement('div');
            base.setAttribute('class', 'base');
            cell.appendChild(base);

            for(var i = 1; i <= this.nbLayers+1; i++) {
                if(i == this.nbLayers+1) {
                    i = 'master';
                }
                var layer = layers.drawLayer(i);
                cell.prepend(layer);
            }

        cell.addEventListener('mouseover', () => {
            objet.cellMouseHover(cell);
        });
        cell.addEventListener('mouseout', () => {
            objet.cellMouseOut(cell);
        });
        
        return cell;
    }
    cellMouseHover(cell) {
        cell.classList.add('hover');
        if(this.isSymetric) {
            var Xsymetry = parseInt(cell.getAttribute('data-x'));
            var Ysymetry = parseInt(cell.getAttribute('data-y'));
            var symetryCell = this.getCell(this.nbLines - Xsymetry, this.cellsByLine - Ysymetry);
                symetryCell.classList.add('symetry');
        }
    }
    cellMouseOut(cell) {
        cell.classList.remove('hover');
        if(this.isSymetric) {
            var Xsymetry = parseInt(cell.getAttribute('data-x'));
            var Ysymetry = parseInt(cell.getAttribute('data-y'));
            var symetryCell = this.getCell(this.nbLines - Xsymetry, this.cellsByLine - Ysymetry);
                symetryCell.classList.remove('symetry');
        }
    }
    getLine(x) {
        return this.mapElements[x];
    }
    getCells(x) {
        return this.mapElements[x].querySelectorAll('.cell');
    }
    getCell(x,y) {
        return this.mapElements[x][y];
    }
    getNbLayers() {
        return this.nbLayers;
    }
    changeBaseColor(type, color) {
        this.mapElements.forEach((line) => {
            line.forEach((cell) => {
                if(cell.classList.contains(type)) {
                    cell.style.backgroundColor = color;
                }
            });
        })
    }
    changeCurrentLayer(i) {
        this.currentLayer = i;
    }
    toggleBorder() {
        this.map.classList.toggle('no-border');
    }
}

function toggleFullscreen(elem) {
    elem = elem || document.documentElement;
    
    if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement && !document.msFullscreenElement) {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
    }
}

map = false, menus = false, categorys = false, themes = false, tiles = false, layers = false;

const pageContent = {
    mapCover : document.querySelector('.map-cover'),
    mapPage : document.querySelector('.map-page'),
    carte : document.querySelector('#main-map'),
    fullScreenButton : document.querySelector('.btn-fullscreen'),
    layersList : document.querySelector('.map-layers')
}


if(pageContent.carte) {
    map = new Carte(pageContent.carte);
    new Move(pageContent.mapCover);
}

if(pageContent.fullScreenButton && pageContent.mapPage) {
    pageContent.fullScreenButton.addEventListener('click', () => {
        toggleFullscreen(pageContent.mapPage)
    });
}