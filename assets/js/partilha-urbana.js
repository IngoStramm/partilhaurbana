(() => {
    'use strict';
    function highlightInit() {
        hljs.initHighlightingOnLoad();

        document.querySelectorAll('pre.code-view > code').forEach((codeBlock) => {
            codeBlock.textContent = codeBlock.innerHTML;
        });
    }
    window.addEventListener('load', () => {
        highlightInit();
    })
})();