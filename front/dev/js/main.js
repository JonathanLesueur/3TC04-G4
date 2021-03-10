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
}

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