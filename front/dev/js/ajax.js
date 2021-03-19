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

class SearchMenu {
    constructor(input) {
        this.input = input,
        this.resultDiv = document.querySelector('.result-group');

        if(this.input) {
            this.init();
        }
    }
    init() {
        this.input.addEventListener('keyup', this.search.bind(this));

        this.resultDiv.addEventListener('focusout', () => {
            this.resultDiv.classList.add('hidden');
        });
        this.input.addEventListener('focus', () => {
            this.resultDiv.classList.remove('hidden');
        });
    }
    search() {
        //this.resultDiv.classList.add('hidden');
        var word = this.input.value.trim();
        
        if(word.length > 3) {
            this.sendRequest(word);
        }
    }
    sendRequest(word) {
        var object = this;
        new AjaxRequest({
            url: "/ajaxsearch",
            dataType: "json",
            data: {text: word},
            onSuccess(response) {
                var result = JSON.parse(response);
                object.buildResults(result);
            }
        }).send();
    }
    buildResults(result) {
        var object = this;
            object.resultDiv.innerHTML = '';
        result.forEach(element => {
            var row = object.makeRow(element);
            object.resultDiv.appendChild(row);
        });
        object.resultDiv.classList.remove('hidden');
    }

    makeRow(object) {
        var element = document.createElement('a');
            element.setAttribute('class', 'item');
            element.href = object.link;

        var content = document.createElement('div');
            content.setAttribute('class', 'content');

        var pictureBox = document.createElement('div');
            pictureBox.setAttribute('class', 'picture');

        var img = document.createElement('img');
            img.setAttribute('src', object.picture);
        
        var main = document.createElement('div');
            main.setAttribute('class', 'main');

        var type = document.createElement('div');
            type.setAttribute('class', 'type');
            type.innerHTML = object.type;

        var title = document.createElement('div');
            title.setAttribute('class', 'title');
            title.innerHTML = object.title;

            main.appendChild(title);
            if(object.picture) {
                pictureBox.appendChild(img);
            }
        
            content.appendChild(pictureBox);
            content.appendChild(main);
            content.appendChild(type);
            element.appendChild(content);

            return element;
    }
}

const inputSearch = document.getElementById('search-menu');
if(inputSearch) {
    new SearchMenu(inputSearch);
}