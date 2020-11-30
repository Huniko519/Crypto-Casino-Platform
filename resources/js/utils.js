import Vue from 'vue';

// make translation function __() available like in Laravel
export function __(string) {
    return typeof window.i18n != 'undefined' && typeof window.i18n[string] != 'undefined' ? window.i18n[string] : string;
}

// make config() function available like in Laravel
export function config(string) {
    return typeof window.cfg != 'undefined' ? get(window.cfg, string) : string;
}

// copy element content to clipboard
export function copyToClipboard(el) {
    el.select();
    try {
        document.execCommand('copy');
    } catch (err) {
        //
    }
    // clear selection
    document.getSelection().removeAllRanges();
    document.activeElement.blur();
}

/**
 *
 * Programmatically mount a component to Vue instance
 * https://css-tricks.com/creating-vue-js-component-instances-programmatically/
 *
 * @param component
 * @param vm - vue instance
 * @param componentElement
 * @param componentProps
 */
export function mountVueComponent(component, vm, componentElement, componentProps) {
    var ComponentClass = Vue.extend(component);
    var instance = new ComponentClass({
        propsData: componentProps,
        parent: vm
    });
    instance.$mount(componentElement);
}

/**
 * Check if variable is numeric
 *
 * @param n
 * @returns {boolean}
 */
export function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

/**
 * Check if variable is an integer
 *
 * @param n
 * @returns {boolean}
 */
export function isInteger(n) {
    return n === parseInt(n, 10);
}

export function round(n, digits = 0) {
    var base = Math.pow(10, digits);
    return Math.round(n * base) / base;
}

export function lpad (string, symbol, length) {
    string = '' + string;

    while (string.length < length)
        string = symbol + string;

    return string;
}

/**
 * Check if full screen is currently enabled
 *
 * @param element
 * @returns boolean
 */
export function isFullScreen(element) {
    return document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement;
}

/**
 * Toggle full screen mode
 *
 * @param element
 */
export function toggleFullScreen(element) {
    if (!isFullScreen()) {
        if (element.requestFullscreen) {
            element.requestFullscreen();
        } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
        } else if (element.webkitRequestFullScreen) {
            element.webkitRequestFullScreen();
        } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
}

/**
 * Get nested object value by path
 * https://stackoverflow.com/a/43849204/2767324
 *
 * @param object
 * @param path
 * @param defaultValue
 * @returns {*}
 */
export function get(object, path, defaultValue = null) {
    return path.split('.').reduce((o, p) => o && typeof o[p] != undefined && o[p] != null ? o[p] : defaultValue, object);
}

export function csrf() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content')
}

/**
 * Dynalically load an external JS script
 *
 * @param url
 * @param callback
 */
export function loadScript (url, callback) {
    var script = document.createElement('script');
    script.onload = () => {
        callback()
    };
    script.src = url;
    document.body.appendChild(script);
}
