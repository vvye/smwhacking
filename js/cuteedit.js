/********************************************
 *  CuteEdit v0.1
 *
 *  Last modification: 2012-09-08
 *  Copyright (c) 2012
 *  Kieran Menor
 *  kieran@menor.dk
 ********************************************/

var CuteEdit = (function () {

    var Browser = {
        LegacyIE: !window.addEventListener && !!window.attachEvent,
        IE: /MSIE/.test(navigator.userAgent),
        WebKit: /Chrome/.test(navigator.userAgent) || /Safari/.test(navigator.userAgent),
        Gecko: /Gecko/.test(navigator.userAgent),
        Opera: !!window.opera,
        MacOS: navigator.platform && /Mac/.test(navigator.platform)
    };

    var Editor = function (element) {
        this.textarea = typeof element === 'string' ? document.getElementById(element) : element;
        this.selection = {start: 0, end: 0};
        this.bar = document.createElement('ul');
        this.focused = false;
        this.keyboardShortcuts = {};

        this.theme = {
            bar: 'cuteedit',
            item: 'cuteedit-item',
            separator: 'cuteedit-separator'
        };

        this.setup();

        var clear = document.createElement('div');
        clear.style.clear = 'both';
        var container = document.createElement('div');
        container.className = this.theme.bar;
        container.appendChild(this.bar);
        container.appendChild(clear);

        if (Browser.LegacyIE) {
            container.style.zoom = 1;
            container.style.cursor = 'default';
            Event.addListener(container, 'selectstart', function (e) {
                e.preventDefault();
            });
            Event.addListener(this.textarea, 'beforedeactivate', [this, 'storeSelection']);
        }
        else {
            Event.addListener(container, 'mousedown', [this, function (e) {
                if (this.focused) {
                    this.storeSelection();
                }
                e.preventDefault();
            }]);
            Event.addListener(this.textarea, 'focus', [this, function (e) {
                this.focused = true;
            }]);
            Event.addListener(this.textarea, Browser.IE ? 'focusout' : 'blur', [this, function (e) {
                this.storeSelection();
                this.focused = false;
            }]);
        }
        Event.addListener(this.textarea, 'keydown', [this, 'keyboardHandler']);

        this.textarea.parentNode.insertBefore(container, this.textarea);
    };

    Editor.prototype.makeTheme = function (name) {
        return {
            normal: 'cuteedit-toolbar-all cuteedit-toolbar-' + name,
            hover: 'cuteedit-toolbar-all cuteedit-toolbar-' + name,
            pressed: 'cuteedit-toolbar-all cuteedit-toolbar-' + name + '-pressed',
            activeNormal: 'cuteedit-toolbar-all cuteedit-toolbar-' + name + '-pressed',
            activeHover: 'cuteedit-toolbar-all cuteedit-toolbar-' + name + '-pressed',
            activePressed: 'cuteedit-toolbar-all cuteedit-toolbar-' + name + '-pressed'
        };
    };

    Editor.prototype.setup = function () {
        var shortcutKey = Browser.MacOS && (Browser.Gecko || Browser.Opera || Browser.WebKit) && !Browser.LegacyIE ? '\u2318' : 'Ctrl+';
        var label, i;

        this.addItem(new Button({
            tooltip: 'Bold (' + shortcutKey + 'B)',
            theme: this.makeTheme('bold'),
            activate: Event.callback(this, function () {
                this.wrap('[b]', '[/b]');
            })
        }), 'B');

        this.addItem(new Button({
            tooltip: 'Italic (' + shortcutKey + 'I)',
            theme: this.makeTheme('italic'),
            activate: Event.callback(this, function () {
                this.wrap('[i]', '[/i]');
            })
        }), 'I');

        this.addItem(new Button({
            tooltip: 'Underline (' + shortcutKey + 'U)',
            theme: this.makeTheme('underline'),
            activate: Event.callback(this, function () {
                this.wrap('[u]', '[/u]');
            })
        }), 'U');

        this.addItem(new Button({
            tooltip: 'Strikethrough (' + shortcutKey + 'S)',
            theme: this.makeTheme('strike'),
            activate: Event.callback(this, function () {
                this.wrap('[s]', '[/s]');
            })
        }), 'S');

        this.addSeperator();

        var sizes = [8, 10, 12, 16, 24];
        var size = new Drop({
            button: {
                tooltip: 'Size',
                theme: this.makeTheme('size')
            }
        });
        for (i = 0; i < sizes.length; i++) {
            label = document.createElement('span');
            label.style.fontSize = sizes[i] + 'pt';
            label.appendChild(document.createTextNode(sizes[i]));

            size.addOption(label, Event.callback({editor: this, size: sizes[i]}, function () {
                this.editor.wrap('[size=' + this.size + ']', '[/size]');
            }));
        }
        this.addItem(size);

        this.addSeperator();

        this.addItem(new Colors({
            button: {
                tooltip: 'Color',
                theme: this.makeTheme('color')
            },
            method: Event.callback(this, function (color) {
                this.wrap('[color=' + color + ']', '[/color]');
            })
        }));

        this.addItem(new Colors({
            button: {
                tooltip: 'Highlight',
                theme: this.makeTheme('highlight')
            },
            method: Event.callback(this, function (color) {
                this.wrap('[highlight=' + color + ']', '[/highlight]');
            })
        }));

        this.addSeperator();

        this.addItem(new Input({
            button: {
                tooltip: 'Insert Link (' + shortcutKey + 'L)',
                theme: this.makeTheme('link'),
                beforeactivate: Event.callback(this, function () {
                    return this.link('[url]', '[/url]');
                })
            },
            label: 'Link URL:',
            defaultValue: 'http://',
            okMethod: Event.callback(this, function (url) {
                this.wrap('[url=' + url + ']', '[/url]');
            }),
            cancelMethod: Event.callback(this, 'restoreSelection')
        }), 'L');

        this.addItem(new Input({
            button: {
                tooltip: 'Insert Image (' + shortcutKey + 'M)',
                theme: this.makeTheme('image'),
                beforeactivate: Event.callback(this, function () {
                    return this.link('[img]', '[/img]');
                })
            },
            label: 'Image URL:',
            defaultValue: 'http://',
            okMethod: Event.callback(this, function (url) {
                this.replaceSelection('[img]' + url + '[/img]');
            }),
            cancelMethod: Event.callback(this, 'restoreSelection')
        }), 'M');

        this.addSeperator();

        this.addItem(new Input({
            button: {
                tooltip: 'Insert Quote',
                theme: this.makeTheme('quote')
            },
            label: 'Name of quoted user:',
            okMethod: Event.callback(this, function (url) {
                if (url === '') {
                    this.wrap('[quote]', '[/quote]');
                }
                else {
                    this.wrap('[quote="' + url + '"]', '[/quote]');
                }
            }),
            cancelMethod: Event.callback(this, 'restoreSelection')
        }));

        this.addItem(new Button({
            tooltip: 'Insert Code',
            theme: this.makeTheme('code'),
            activate: Event.callback(this, function () {
                this.wrap('[code]', '[/code]');
            })
        }));

        this.addItem(new Button({
            tooltip: 'Insert Spoiler',
            theme: this.makeTheme('spoiler'),
            activate: Event.callback(this, function () {
                this.wrap('[spoiler]', '[/spoiler]');
            })
        }));

        if (typeof CuteEdit_Emoticons === 'object') {
            var loader = new ImageLoader();

            for (i = 0; i < CuteEdit_Emoticons.length; i++) {
                for (j = 0; j < CuteEdit_Emoticons[i].items.length; j++) {
                    loader.addItem(CuteEdit_Emoticons[i].items[j].url, {group: i, item: j});
                }
            }

            loader.load(Event.callback(this, function (images) {
                var i, j, img, size;
                var groupSizes = [];
                var imageSizes = [];

                for (i = 0; i < images.length; i++) {
                    if (typeof groupSizes[images[i].data.group] === 'undefined') {
                        groupSizes[images[i].data.group] = {width: 0, height: 0};
                    }
                    if (typeof imageSizes[images[i].data.group] === 'undefined') {
                        imageSizes[images[i].data.group] = [];
                    }
                    if (images[i].success) {
                        if (images[i].image.width > groupSizes[images[i].data.group].width) {
                            groupSizes[images[i].data.group].width = images[i].image.width;
                        }
                        if (images[i].image.height > groupSizes[images[i].data.group].height) {
                            groupSizes[images[i].data.group].height = images[i].image.height;
                        }
                        imageSizes[images[i].data.group][images[i].data.item] = {
                            width: images[i].image.width,
                            height: images[i].image.height
                        };
                    }
                }

                var emoticons = new List({
                    button: {
                        tooltip: 'Insert Emoticon',
                        theme: this.makeTheme('emoticon')
                    },
                    listClass: 'cuteedit-emoticons'
                });

                for (i = 0; i < CuteEdit_Emoticons.length; i++) {
                    emoticons.addGroup(CuteEdit_Emoticons[i].name);
                    size = (groupSizes[i] === 'undefined') ? {width: 0, height: 0} : groupSizes[i];
                    for (j = 0; j < CuteEdit_Emoticons[i].items.length; j++) {
                        if (typeof imageSizes[i][j] !== 'undefined') {
                            img = document.createElement('img');
                            img.src = CuteEdit_Emoticons[i].items[j].url;
                            img.title = CuteEdit_Emoticons[i].items[j].name + ' (' + CuteEdit_Emoticons[i].items[j].text + ')';
                            img.style.cursor = 'pointer';
                            img.style.marginTop = Math.floor((size.height - imageSizes[i][j].height) / 2) + 'px';
                            emoticons.addItem(img, Event.callback({
                                editor: this,
                                text: CuteEdit_Emoticons[i].items[j].text
                            }, function () {
                                this.editor.replaceSelection(this.text)
                            }), size);
                        }
                    }
                }

                this.addSeperator();
                this.addItem(emoticons);
            }));
        }
    };

    Editor.prototype.addItem = function (control, keyboardShortcut) {
        var li = document.createElement('li');
        li.className = this.theme.item;
        control.appendTo(li);
        this.bar.appendChild(li);
        if (typeof keyboardShortcut === 'string') {
            var code = keyboardShortcut.toUpperCase().charCodeAt(0);
            if (code >= 65 && code <= 90) {
                this.keyboardShortcuts[code] = Event.callback(control, 'activate');
            }
        }
    };

    Editor.prototype.keyboardHandler = function (e) {
        var shortcutKey = Browser.MacOS && (Browser.Gecko || Browser.Opera || Browser.WebKit) && !Browser.LegacyIE ? e.metaKey : e.ctrlKey;
        if (shortcutKey && e.which >= 65 && e.which <= 90) {
            if (this.keyboardShortcuts[e.which]) {
                this.storeSelection();
                this.keyboardShortcuts[e.which]();
                e.preventDefault();
            }
        }
        else if (e.which === 9) {
            var selection = Selection.get(this.textarea);
            var newlineResult = /(\r?\n|\r)/.exec(this.textarea.value.substring(selection.start, selection.end));
            if (newlineResult) {
                var newline = newlineResult[1];

                var start = this.textarea.value.lastIndexOf(newline, selection.start);
                if (start === -1) {
                    start = 0;
                }
                else {
                    start += newline.length;
                }
                var end = this.textarea.value.indexOf(newline, selection.end);
                if (end === -1) {
                    end = this.textarea.value.length;
                }

                var before = this.textarea.value.substring(0, start);
                var selected = this.textarea.value.substring(start, end);
                var after = this.textarea.value.substring(end, this.textarea.value.length);

                var lines = selected.split(newline);

                if (e.shiftKey) {
                    for (var i = 0; i < lines.length; i++) {
                        lines[i] = lines[i].replace(/^( {1,4}|\t)/, '');
                    }
                }
                else {
                    for (var i = 0; i < lines.length; i++) {
                        lines[i] = '\t' + lines[i];
                    }
                }
                var tabulated = lines.join(newline);

                this.setText(before + tabulated + after);

                Selection.set(this.textarea, start, end + (tabulated.length - selected.length));
            }
            else {
                var cr = this.textarea.value.lastIndexOf('\r', selection.start - 1);
                var lf = this.textarea.value.lastIndexOf('\n', selection.start - 1);
                var offset = (cr > lf ? cr : lf) + 1;
                if (e.shiftKey) {
                    var before = this.textarea.value.substring(0, offset);
                    var after = this.textarea.value.substring(offset, this.textarea.value.length).replace(/^( {1,4}|\t)/, '');
                    this.setText(before + after);
                    var tabulation = /^([\t ]+)/.exec(after);
                    var tabLength = tabulation ? tabulation[1].length : 0;
                    Selection.set(this.textarea, offset + tabLength);
                }
                else {
                    var tabulation = /^([\t ]+)/.exec(this.textarea.value.substring(offset, this.textarea.value.length));
                    var tabLength = tabulation ? tabulation[1].length : 0;
                    if (selection.start < offset + tabLength) {
                        Selection.set(this.textarea, offset + tabLength);
                    }
                    this.replaceSelection('\t');
                }
            }
            e.preventDefault();
        }
    };

    Editor.prototype.addSeperator = function () {
        var li = document.createElement('li');
        li.className = this.theme.separator;
        this.bar.appendChild(li);
    };

    Editor.prototype.replaceSelection = function (text, select) {
        var selection = this.focused ? Selection.get(this.textarea) : this.selection;
        var before = this.textarea.value.substring(0, selection.start);
        var after = this.textarea.value.substring(selection.end, this.textarea.value.length);
        var textLength = this.getCorrectedLength(text);
        this.setText(before + text + after);
        if (select) {
            Selection.set(this.textarea, (before.length), (before.length + textLength));
        }
        else {
            Selection.set(this.textarea, before.length + textLength);
        }
    };

    Editor.prototype.storeSelection = function () {
        this.selection = Selection.get(this.textarea);
    };

    Editor.prototype.restoreSelection = function () {
        Selection.set(this.textarea, this.selection.start, this.selection.end);
    }

    Editor.prototype.wrap = function (start, end) {
        var before = this.textarea.value.substring(0, this.selection.start);
        var selected = this.textarea.value.substring(this.selection.start, this.selection.end);
        var after = this.textarea.value.substring(this.selection.end, this.textarea.value.length);
        var startLength = this.getCorrectedLength(start);
        this.setText(before + start + selected + end + after);
        Selection.set(this.textarea, (before.length + startLength), (before.length + startLength + selected.length));
    };

    Editor.prototype.link = function (start, end) {
        var uriRegex = /^(\s*)([a-zA-Z0-9+.\-]+:[^ ]+)(\s*)$/;
        var selection = this.selection;
        var result = uriRegex.exec(this.textarea.value.substring(selection.start, selection.end));
        if (result) {
            selection = {
                start: selection.start + result[1].length,
                end: selection.end - result[3].length
            };
            var before = this.textarea.value.substring(0, selection.start);
            var selected = this.textarea.value.substring(selection.start, selection.end);
            var after = this.textarea.value.substring(selection.end, this.textarea.value.length);
            this.setText(before + start + selected + end + after);
            Selection.set(this.textarea, (before.length + start.length), (before.length + start.length + selected.length));
            return false;
        }
    };

    Editor.prototype.setText = function (value) {
        var scroll = this.textarea.scrollTop;
        this.textarea.value = value;
        this.textarea.scrollTop = scroll;
    };

    Editor.prototype.getCorrectedLength = function (text) {
        var crlf = text.match(/\r\n/g);
        if (crlf && !Browser.LegacyIE) {
            return text.length - crlf.length;
        }
        return text.length;
    };

    var Button = function (options) {
        this.instant = !!options.instant;

        if (options.theme) {
            this.theme = options.theme;
        }
        else {
            this.theme = this.instant ? {
                normal: 'cuteedit-button',
                hover: 'cuteedit-button-hover',
                pressed: 'cuteedit-button-hover',
                activeNormal: 'cuteedit-button-active-pressed',
                activeHover: 'cuteedit-button-active-pressed',
                activePressed: 'cuteedit-button-active-pressed'
            } : {
                normal: 'cuteedit-button',
                hover: 'cuteedit-button-hover',
                pressed: 'cuteedit-button-pressed',
                activeNormal: 'cuteedit-button-active',
                activeHover: 'cuteedit-button-active-hover',
                activePressed: 'cuteedit-button-active-pressed'
            };
        }

        this._activate = options.activate;
        if (options.deactivate) {
            this._deactivate = options.deactivate;
            this.toggle = true;
        }
        else {
            this.toggle = false;
        }

        this._onactivate = typeof options.beforeactivate === 'function' ? options.beforeactivate : false;

        this.hover = false;
        this.pressed = false;
        this.active = false;

        this.element = document.createElement('div');
        this.element.className = this.theme.normal;
        if (options.tooltip) {
            this.element.title = options.tooltip;
        }

        Event.addListener(this.element, 'mouseover', [this, 'mouseover']);
        Event.addListener(this.element, 'mouseout', [this, 'mouseout']);
        Event.addListener(this.element, 'mousedown', [this, 'mousedown']);
        Event.addListener(this.element, 'mouseup', [this, 'mouseup']);
        Event.addListener(document, 'mouseup', [this, 'documentmouseup']);

        if (options.label) {
            this.setLabel(options.label);
        }
    };

    Button.prototype.setLabel = function (label) {
        if (typeof label === 'string') {
            label = document.createTextNode(label);
        }
        this.element.appendChild(label);
        return this;
    };

    Button.prototype.activate = function () {
        if (this._onactivate && this._onactivate() === false) {
            return;
        }
        if (this.toggle) {
            this.active = true;
            this.element.className = this.hover ? this.theme.activeHover : this.theme.activeNormal;
        }
        else {
            this.element.className = this.hover ? this.theme.hover : this.theme.normal;
        }
        this._activate();
    };

    Button.prototype.deactivate = function () {
        if (this.toggle) {
            this.active = false;
            this.element.className = this.hover ? this.theme.hover : this.theme.normal;
            this._deactivate();
        }
    };

    Button.prototype.isActive = function () {
        return this.active;
    }

    Button.prototype.mouseover = function () {
        this.hover = true;
        if (this.pressed) {
            this.element.className = this.active ? this.theme.activePressed : this.theme.pressed;
        }
        else {
            this.element.className = this.active ? this.theme.activeHover : this.theme.hover;
        }
    };

    Button.prototype.mouseout = function () {
        this.hover = false;
        this.element.className = this.active ? this.theme.activeNormal : this.theme.normal;
    };

    Button.prototype.mousedown = function (e) {
        this.pressed = true;
        this.element.className = this.active ? this.theme.activePressed : this.theme.pressed;
        if (this.instant) {
            if (this.active) {
                this.deactivate();
            }
            else {
                this.activate();
            }
        }
    };

    Button.prototype.mouseup = function () {
        if (this.pressed) {
            this.pressed = false;
            if (!this.instant) {
                if (this.active) {
                    this.deactivate();
                }
                else {
                    this.activate();
                }
            }
        }
    };

    Button.prototype.documentmouseup = function () {
        if (this.pressed) {
            this.pressed = false;
            this.element.className = this.active ? this.theme.activeNormal : this.theme.normal;
        }
    };

    Button.prototype.appendTo = function (element) {
        element.appendChild(this.element);
        return this;
    };

    var Toggle = function (options) {
        options.button.activate = Event.callback(this, 'show');
        options.button.deactivate = Event.callback(this, 'hide');

        this.visible = !!options.visible;
        this._onshow = typeof options.onshow === 'function' ? options.onshow : false;

        this.hover = false;

        this.buttonContainer = document.createElement('div');

        this.button = new Button(options.button);
        this.button.appendTo(this.buttonContainer);

        this.innerContainer = document.createElement('div');
        this.innerContainer.style.position = 'absolute';
        this.innerContainer.style.zIndex = '1000';
        this.innerContainer.style.display = 'none';
        this.innerContainer.appendChild(options.element);

        this.outerContainer = document.createElement('div');
        this.outerContainer.className = 'cuteedit-toggle';
        this.outerContainer.appendChild(this.buttonContainer);
        this.outerContainer.appendChild(this.innerContainer);

        if (options.hideOnBlur) {
            Event.addListener(this.innerContainer, 'mouseover', [this, function () {
                this.hover = true;
            }]);
            Event.addListener(this.innerContainer, 'mouseout', [this, function () {
                this.hover = false;
            }]);
            Event.addListener(document, 'mousedown', [this, function () {
                if (this.visible && !this.button.pressed && !this.hover) {
                    this.deactivate();
                }
            }]);
        }

        if (this.visible) {
            this.activate();
        }
        ;
    };

    Toggle.prototype.activate = function () {
        this.button.activate();
    };

    Toggle.prototype.deactivate = function () {
        this.button.deactivate();
    };

    Toggle.prototype.isActive = function () {
        return this.button.active;
    }

    Toggle.prototype.show = function () {
        this.visible = true;
        this.innerContainer.style.display = '';
        if (this._onshow) {
            this._onshow();
        }
    };

    Toggle.prototype.hide = function () {
        this.visible = false;
        this.innerContainer.style.display = 'none';
    };

    Toggle.prototype.appendTo = function (element) {
        element.appendChild(this.outerContainer);
        return this;
    };

    var Drop = function (options) {
        this.theme = {
            list: 'cuteedit-drop',
            item: 'cuteedit-drop-item',
            itemHighlight: 'cuteedit-drop-item-highlight'
        };

        this.element = document.createElement('ul');
        this.element.className = this.theme.list;

        options.element = this.element;
        options.button.instant = true;
        options.hideOnBlur = true;

        this.toggle = new Toggle(options);
    };

    Drop.prototype.addOption = function (label, method) {
        var li = document.createElement('li');
        li.className = this.theme.item;

        Event.addListener(li, 'mouseover', [this, function (e) {
            e.currentTarget.className = this.theme.itemHighlight;
        }]);
        Event.addListener(li, 'mouseout', [this, function (e) {
            e.currentTarget.className = this.theme.item;
        }]);
        Event.addListener(li, 'mouseup', {method: method}, [this, function (e) {
            if (this.toggle.button.pressed) {
                e.data.method();
                this.toggle.button.deactivate();
            }
        }]);
        Event.addListener(li, 'click', {method: method}, [this, function (e) {
            e.data.method();
            this.toggle.button.deactivate();
        }]);

        if (typeof label === 'string') {
            label = document.createTextNode(label);
        }
        li.appendChild(label);

        this.element.appendChild(li);
        return this;
    };

    Drop.prototype.activate = function () {
        this.toggle.button.activate();
    };

    Drop.prototype.deactivate = function () {
        this.toggle.button.deactivate();
    };

    Drop.prototype.isActive = function () {
        return this.toggle.button.active;
    }

    Drop.prototype.appendTo = function (element) {
        this.toggle.appendTo(element);
        return this;
    };

    var Colors = function (options) {
        this.element = document.createElement('table');

        options.element = this.element;
        options.button.instant = true;
        options.hideOnBlur = true;

        this.toggle = new Toggle(options);

        this.element.className = 'cuteedit-colors';
        this.element.cellSpacing = 0;
        var tbody = document.createElement('tbody');
        this.element.appendChild(tbody);
        var r, b, g, tr, td, color;
        for (r = 0; r <= 5; r++) {
            tr = document.createElement('tr');
            tbody.appendChild(tr);
            for (g = 0; g <= 5; g++) {
                for (b = 0; b <= 5; b++) {
                    color = '#' + this.toHex((r * 51), 2) + this.toHex((g * 51), 2) + this.toHex((b * 51), 2);
                    td = document.createElement('td');
                    td.title = color;
                    td.style.backgroundColor = color;
                    Event.addListener(td, 'mouseup', {color: color, method: options.method}, [this, function (e) {
                        if (this.toggle.button.pressed) {
                            e.data.method(e.data.color);
                            this.toggle.button.deactivate();
                        }
                    }]);
                    Event.addListener(td, 'click', {color: color, method: options.method}, [this, function (e) {
                        e.data.method(e.data.color);
                        this.toggle.button.deactivate();
                    }]);
                    tr.appendChild(td);
                    td.appendChild(document.createTextNode('\u00a0'));
                }
            }
        }
    };

    Colors.prototype.toHex = function (number, length) {
        var hex = number.toString(16);
        while (hex.length < length) {
            hex = '0' + hex;
        }
        return hex;
    };

    Colors.prototype.activate = function () {
        this.toggle.button.activate();
    };

    Colors.prototype.deactivate = function () {
        this.toggle.button.deactivate();
    };

    Colors.prototype.isActive = function () {
        return this.toggle.button.active;
    }

    Colors.prototype.appendTo = function (element) {
        this.toggle.appendTo(element);
        return this;
    };

    var Input = function (options) {
        this.defaultValue = typeof options.defaultValue === 'string' ? options.defaultValue : '';
        this.okMethod = options.okMethod;
        this.cancelMethod = options.cancelMethod;

        var div = document.createElement('div');
        div.className = 'cuteedit-input';

        var labelContainer = document.createElement('div');
        labelContainer.className = 'cuteedit-input-label';

        var label = document.createElement('label');
        if (typeof options.label === 'string') {
            label.appendChild(document.createTextNode(options.label));
        }
        else {
            label.appendChild(options.label);
        }
        labelContainer.appendChild(label);

        var textContainer = document.createElement('div');
        textContainer.className = 'cuteedit-input-text';

        this.input = document.createElement('input');
        Event.addListener(this.input, 'keydown', [this, 'keyboardHandler']);
        Event.addListener(this.input, 'mousedown', function (e) {
            e.stopPropagation();
        });
        textContainer.appendChild(this.input);

        var buttonContainer = document.createElement('div');
        buttonContainer.className = 'cuteedit-input-buttons';

        var ok = document.createElement('button');
        if (Browser.LegacyIE) {
            ok.setAttribute('type', 'button');
        }
        else {
            ok.type = 'button';
        }
        ok.className = 'cuteedit-input-ok';
        ok.appendChild(document.createTextNode('OK'));
        Event.addListener(ok, 'click', [this, 'okHandler']);
        Event.addListener(ok, 'mousedown', function (e) {
            e.stopPropagation();
        });
        buttonContainer.appendChild(ok);

        var cancel = document.createElement('button');
        if (Browser.LegacyIE) {
            cancel.setAttribute('type', 'button');
        }
        else {
            cancel.type = 'button';
        }
        cancel.className = 'cuteedit-input-cancel';
        cancel.appendChild(document.createTextNode('Cancel'));
        Event.addListener(cancel, 'click', [this, 'cancelHandler']);
        Event.addListener(cancel, 'mousedown', function (e) {
            e.stopPropagation();
        });
        buttonContainer.appendChild(cancel);

        div.appendChild(labelContainer);
        div.appendChild(textContainer);
        div.appendChild(buttonContainer);

        options.element = div;
        options.hideOnBlur = true;
        options.onshow = Event.callback(this, 'showHandler');

        this.toggle = new Toggle(options);
    };

    Input.prototype.showHandler = function () {
        this.input.value = this.defaultValue;
        Selection.set(this.input, 0, this.input.value.length);
    };

    Input.prototype.keyboardHandler = function (e) {
        if (e.which === 13) {
            this.okHandler(e);
        }
        else if (e.which === 27) {
            this.cancelHandler(e);
        }
    };

    Input.prototype.okHandler = function (e) {
        this.toggle.button.deactivate();
        this.okMethod(this.input.value);
        this.input.value = '';
        e.preventDefault();
    };

    Input.prototype.cancelHandler = function (e) {
        this.toggle.button.deactivate();
        this.input.value = '';
        this.cancelMethod();
        e.preventDefault();
    };

    Input.prototype.activate = function () {
        this.toggle.button.activate();
    };

    Input.prototype.deactivate = function () {
        this.toggle.button.deactivate();
    };

    Input.prototype.isActive = function () {
        return this.toggle.button.active;
    }

    Input.prototype.appendTo = function (element) {
        this.toggle.appendTo(element);
        return this;
    };

    var List = function (options) {
        this.theme = {
            list: 'cuteedit-list',
            parent: 'cuteedit-list-parent',
            item: 'cuteedit-list-item'
        };

        var container = document.createElement('div');
        container.className = this.theme.list;

        if (typeof options.listClass !== 'undefined') {
            container.className += ' ' + options.listClass;
        }

        this.currentParent = this.element = document.createElement('ul');

        container.appendChild(this.element);

        var clear = document.createElement('div');
        clear.style.clear = 'both';
        container.appendChild(clear);

        options.element = container;
        options.button.instant = true;
        options.hideOnBlur = true;

        this.toggle = new Toggle(options);
    };

    List.prototype.addItem = function (label, method, size) {
        var li = document.createElement('li');
        li.className = this.theme.item;

        if (typeof size !== 'undefined') {
            li.style.width = size.width + 'px';
            li.style.height = size.height + 'px';
        }

        Event.addListener(li, 'mouseup', {method: method}, [this, function (e) {
            if (this.toggle.button.pressed) {
                e.data.method();
                this.toggle.button.deactivate();
            }
        }]);
        Event.addListener(li, 'click', {method: method}, [this, function (e) {
            e.data.method();
            this.toggle.button.deactivate();
        }]);

        if (typeof label === 'string') {
            label = document.createTextNode(label);
        }
        li.appendChild(label);

        this.currentParent.appendChild(li);
        return this;
    };

    List.prototype.addGroup = function (label) {
        var li = document.createElement('li');
        li.className = this.theme.parent;

        var span = document.createElement('span');
        if (typeof label === 'string') {
            label = document.createTextNode(label);
        }
        span.appendChild(label);
        li.appendChild(span);

        this.currentParent = document.createElement('ul');
        li.appendChild(this.currentParent);

        this.element.appendChild(li);
    };

    List.prototype.closeGroup = function () {
        if (this.element !== this.currentParent) {
            this.currentParent = this.element;
        }
    };

    List.prototype.activate = function () {
        this.toggle.button.activate();
    };

    List.prototype.deactivate = function () {
        this.toggle.button.deactivate();
    };

    List.prototype.isActive = function () {
        return this.toggle.button.active;
    }

    List.prototype.appendTo = function (element) {
        this.toggle.appendTo(element);
        return this;
    };

    var ImageLoader = function () {
        this.imageUrls = [];
        this.images = [];
        this.imageCount = 0;
        this.callback = null;
    };

    ImageLoader.prototype.addItem = function (url, data) {
        this.imageUrls[this.imageUrls.length] = {url: url, data: data};
    };

    ImageLoader.prototype.load = function (callback) {
        var image;
        this.callback = callback;
        this.imageCount = this.imageUrls.length;
        for (var i = 0; i < this.imageUrls.length; i++) {
            image = new Image();

            Event.addListener(image, 'load', {index: i}, [this, this.successCallback]);
            Event.addListener(image, 'error', {index: i}, [this, this.errorCallback]);
            Event.addListener(image, 'abort', {index: i}, [this, this.errorCallback]);

            image.src = this.imageUrls[i].url;
            this.images[i] = {success: false, image: image, data: this.imageUrls[i].data};
        }
    };

    ImageLoader.prototype.successCallback = function (e) {
        this.images[e.data.index].success = true;
        this.loadCallback();
    };

    ImageLoader.prototype.errorCallback = function (e) {
        this.loadCallback();
    };

    ImageLoader.prototype.loadCallback = function () {
        this.imageCount--;
        if (this.imageCount === 0) {
            this.callback(this.images);
        }
    };

    var Selection = {
        set: function (element, start, end) {
            if (typeof end === 'undefined') {
                end = start;
            }
            element.focus();
            if (element.setSelectionRange) {
                element.setSelectionRange(start, end);
            }
            else if (element.createTextRange) {
                var adjustStartMatch = element.value.substring(0, start).match(/\r\n/g);
                if (adjustStartMatch) {
                    start -= adjustStartMatch.length;
                }

                var adjustEndMatch = element.value.substring(0, end).match(/\r\n/g);
                if (adjustEndMatch) {
                    end -= adjustEndMatch.length;
                }

                var range = element.createTextRange();
                range.collapse();
                range.moveEnd('character', end);
                range.moveStart('character', start);
                range.select();
            }
        },

        get: function (element) {
            if ((typeof element.selectionStart === 'number') && (typeof element.selectionEnd === 'number')) {
                return {start: element.selectionStart, end: element.selectionEnd};
            }
            else if (document.selection) {
                var selectedRange = document.selection.createRange();
                var beforeRange = selectedRange.duplicate();
                var afterRange = selectedRange.duplicate();
                var start, brEnd, arEnd;
                beforeRange.moveToElementText(beforeRange.parentElement());
                beforeRange.setEndPoint('EndToEnd', selectedRange);
                afterRange.moveToElementText(afterRange.parentElement());
                afterRange.setEndPoint('StartToEnd', selectedRange);
                start = beforeRange.text.length - selectedRange.text.length;
                brEnd = beforeRange.text.length;
                arEnd = element.value.length - afterRange.text.length;

                if ((start === brEnd) && (brEnd !== arEnd)) {
                    return {start: arEnd, end: arEnd};
                }
                else {
                    return {start: start, end: arEnd};
                }
            }
        }
    };

    var Event = {
        leftMeta: false,
        rightMeta: false,

        keyboardHandler: function (e) {
            if ((Browser.Gecko && e.which === 224)
                || (Browser.Opera && e.which === 17)
                || (Browser.WebKit && e.which === 91)) {
                if (e.type === 'keydown') {
                    this.leftMeta = true;
                }
                else if (e.type === 'keyup') {
                    this.leftMeta = false;
                }
            }
            else if (Browser.WebKit && e.which === 93) {
                if (e.type === 'keydown') {
                    this.rightMeta = true;
                }
                else if (e.type === 'keyup') {
                    this.rightMeta = false;
                }
            }
        },

        attachKeyboardHandler: function () {
            if (Browser.MacOS && window.addEventListener) {
                var handler = Event.callback(this, 'keyboardHandler');
                window.addEventListener('keydown', handler, false);
                window.addEventListener('keyup', handler, false);
            }
        },

        addListener: function (element, event, data, callback) {
            if (typeof callback === 'undefined') {
                var tmp = data;
                data = callback;
                callback = tmp;
            }
            if (typeof callback !== 'function') {
                callback = this.callback(callback[0], callback[1]);
            }
            var handler = this.eventHandler(element, callback, data);
            if (Browser.LegacyIE) {
                element.attachEvent('on' + event, handler);
            }
            else if (element.addEventListener) {
                element.addEventListener(event, handler, false);
            }
            return handler;
        },

        removeListener: function (element, event, handler) {
            if (Browser.LegacyIE) {
                element.detachEvent('on' + event, handler);
            }
            else if (element.removeEventListener) {
                element.removeEventListener(event, handler, false);
            }
        },

        eventHandler: function (element, callback, data) {
            return function (e) {
                callback.call(element, new EventArgs(e || window.event, element, data));
            }
        },

        callback: function (context, method) {
            if (typeof method === 'string') {
                method = context[method];
            }
            return function () {
                return method.apply(context, arguments);
            }
        }
    };

    var EventArgs = function (event, element, data) {
        var i,
            commonProperties = ['altKey', 'ctrlKey', 'shiftKey', 'screenX', 'screenY', 'clientX', 'clientY'],
            W3CProperties = ['button', 'target'];

        this.event = event;
        if (typeof data !== 'undefined') {
            this.data = data;
        }

        this.type = this.event.type;

        this.isPropagationStopped = this.isDefaultPrevented = function () {
            return false;
        }

        this.currentTarget = element;

        for (i = 0; i < commonProperties.length; i++) {
            if (typeof this.event[commonProperties[i]] !== 'undefined') {
                this[commonProperties[i]] = this.event[commonProperties[i]];
            }
        }

        if (Browser.LegacyIE) {
            if (typeof this.event.button !== 'undefined') {
                if (this.event.button & 1) {
                    this.button = 0;
                }
                else if (this.event.button & 2) {
                    this.button = 2;
                }
                else if (this.event.button & 4) {
                    this.button = 1;
                }
            }

            if (typeof this.event.srcElement !== 'undefined') {
                this.target = this.event.srcElement;
            }

            if (this.event.type === 'mouseover') {
                this.relatedTarget = this.event.fromElement;
            }
            else if (this.event.type === 'mouseout') {
                this.relatedTarget = this.event.toElement;
            }

            if (typeof this.event.keyCode !== 'undefined') {
                this.which = this.event.keyCode;
            }

            this.metaKey = false;
        }
        else {
            for (i = 0; i < W3CProperties.length; i++) {
                if (typeof this.event[W3CProperties[i]] !== 'undefined') {
                    this[W3CProperties[i]] = this.event[W3CProperties[i]];
                }
            }

            if (this.event.type === 'mouseover' || this.event.type === 'mouseout') {
                this.relatedTarget = this.event.relatedTarget;
            }

            if (typeof this.event.which !== 'undefined') {
                this.which = this.event.which;
            }

            if (Browser.Gecko || Browser.Opera || Browser.WebKit) {
                this.metaKey = Event.leftMeta || Event.rightMeta;
            }
            else {
                this.metaKey = this.event.MetaKey;
            }
        }
    };

    EventArgs.prototype.stopPropagation = function () {
        this.isPropagationStopped = function () {
            return true;
        }
        if (Browser.LegacyIE) {
            this.event.cancelBubble = true;
        }
        else if (this.event.stopPropagation) {
            this.event.stopPropagation();
        }
    };

    EventArgs.prototype.preventDefault = function () {
        this.isDefaultPrevented = function () {
            return true;
        }
        if (Browser.LegacyIE) {
            this.event.returnValue = false;
        }
        else if (this.event.preventDefault()) {
            this.event.preventDefault();
        }
    };

    Event.attachKeyboardHandler();

    return Editor;
})();