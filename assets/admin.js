(function () {
    'use strict';

    function detectSleekyTheme() {
        var meta = document.querySelector('meta[name="sleeky_theme"]');
        if (!meta) return;
        var value = (meta.getAttribute('content') || '').toLowerCase();
        document.body.classList.add('ls-sleeky-active');
        if (value === 'dark') {
            document.body.classList.add('ls-sleeky-dark');
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', detectSleekyTheme);
    } else {
        detectSleekyTheme();
    }
})();
