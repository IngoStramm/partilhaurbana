(() => {
    'use strict';
    // Destaca o item do menu atual
    function highlightInit() {
        hljs.initHighlightingOnLoad();

        document.querySelectorAll('pre.code-view > code').forEach((codeBlock) => {
            codeBlock.textContent = codeBlock.innerHTML;
        });
    }

    // Máscaras dos campos
    function inputMasks() {
        const phoneInputs = document.querySelectorAll('.phone-input');
        const phoneMaskOptions = {
            mask: '+00[000000000000]'
        };
        phoneInputs.forEach(phoneInput => {
            const phoneMask = IMask(phoneInput, phoneMaskOptions);
        });

        const whatsappInputs = document.querySelectorAll('.whatsapp-input');
        const whatsappMaskOptions = {
            mask: '000-000-000'
        };
        whatsappInputs.forEach(whatsappInput => {
            const whatsappMask = IMask(whatsappInput, whatsappMaskOptions);

        });

        const moneyInputs = document.querySelectorAll('.money-input');
        const moneyMaskOptions = {
            mask: 'num €',
            lazy: false,
            blocks: {
                num: {
                    mask: Number,
                    scale: 2,
                    thousandsSeparator: '.',
                    padFractionalZeros: true,
                    radix: ',',
                    mapToRadix: ['.'],
                }
            }
        };
        moneyInputs.forEach(moneyInput => {
            const moneyMask = IMask(moneyInput, moneyMaskOptions);
        });

        const moneyNoDecimalsInputs = document.querySelectorAll('.money-no-decimals-input');
        const moneyNoDecimalsMaskOptions = {
            mask: 'num €',
            lazy: false,
            blocks: {
                num: {
                    mask: Number,
                    scale: 0,
                    thousandsSeparator: '.',
                    padFractionalZeros: true,
                    radix: ',',
                    mapToRadix: ['.'],
                }
            }
        };
        moneyNoDecimalsInputs.forEach(moneyNoDecimalsInput => {
            const moneyNoDecimalsMask = IMask(moneyNoDecimalsInput, moneyNoDecimalsMaskOptions);
        });

        const pctInputs = document.querySelectorAll('.pct-input');
        const pctMaskOptions = {
            mask: 'num %',
            lazy: false,
            blocks: {
                num: {
                    min: 0,
                    max: 100,
                    mask: Number,
                }
            }
        };
        pctInputs.forEach(pctInput => {
            const pctMask = IMask(pctInput, pctMaskOptions);
        });

    }

    function goBackBtn() {
        const btns = document.querySelectorAll('.go-back-btn');
        btns.forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                history.back();
            });
        });
    }
    window.addEventListener('load', () => {
        highlightInit();
        inputMasks();
        goBackBtn();
    });
})();