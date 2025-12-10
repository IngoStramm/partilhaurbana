(() => {
    'use strict';
    // Destaca o item do menu atual

    const estagios = ajax_object.estagios;
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

    // Go Back to Top Button
    function goBackBtn() {
        const btns = document.querySelectorAll('.go-back-btn');
        btns.forEach(btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                history.back();
            });
        });
    }

    function formGroupImage() {
        const formGroupImages = document.querySelectorAll('.form-group-image');
        formGroupImages.forEach(group => {
            const btn = group.querySelector('button');
            btn.addEventListener('click', () => {
                group.classList.remove('has-image');
            });
        });
    }

    function estagiosAddItem() {
        const newEstagio = {
            title: '',
            cost: 0,
            effort: 0
        };
        estagios.push(newEstagio);
        renderEstagios();
    }

    function estagiosRepeater() {
        const estagiosRepeaters = document.querySelectorAll('.estagios-repeater');
        estagiosRepeaters.forEach(table => {
            const btn = table.querySelector('.add-new-estagio');
            btn.addEventListener('click', estagiosAddItem);
        });
    }

    function estagiosRemoveItem(e) {
        const btn = e.target;
        const tr = btn.closest('tr');
        const estagioIndex = tr.dataset.order;
        tr.remove();
        estagios.splice(estagioIndex, 1);
        if (estagios.length === 0) {
            estagiosAddItem();
        } else {
            renderEstagios();
        }
    }

    function estagioReorderItem() {
        const estagiosRepeaters = document.querySelectorAll('.estagios-repeater');
        estagiosRepeaters.forEach(estagiosRepeater => {
            const tbody = estagiosRepeater.querySelector('tbody');
            let draggedRow = null;
            let currEstagioIndex = null;
            let targetEstagioIndex = null;
            tbody.addEventListener('dragstart', (e) => {
                draggedRow = e.target.closest('tr');
                currEstagioIndex = draggedRow.dataset.order;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', ''); // Required for Firefox
                draggedRow.classList.add('dragging'); // Optional: Add a class for styling
            });
            tbody.addEventListener('dragover', (e) => {
                e.preventDefault(); // Allow dropping
                const targetRow = e.target.closest('tr');
                targetEstagioIndex = targetRow.dataset.order;
                if (targetRow && targetRow !== draggedRow) {
                    // Optional: Add visual feedback for potential drop location
                    targetRow.classList.add('drag-over');
                }
            });
            tbody.addEventListener('dragleave', (e) => {
                // Optional: Remove visual feedback
                e.target.closest('tr')?.classList.remove('drag-over');
                draggedRow.classList.remove('dragging'); // Optional: Remove styling
            });
            tbody.addEventListener('drop', (e) => {
                e.preventDefault();
                const targetRow = e.target.closest('tr');

                if (draggedRow && targetRow && draggedRow !== targetRow) {
                    const draggedEstagio = estagios[currEstagioIndex];
                    // remove antes
                    estagios.splice(currEstagioIndex, 1);
                    // então posiciona
                    estagios.splice(targetEstagioIndex, 0, draggedEstagio);
                }
                draggedRow.classList.remove('dragging'); // Optional: Remove styling
                targetRow?.classList.remove('drag-over'); // Optional: Remove styling
                draggedRow = null;
                renderEstagios();
            });
        });
    }

    function renderEstagios() {
        const estagiosRepeaters = document.querySelectorAll('.estagios-repeater');
        estagiosRepeaters.forEach(estagiosRepeater => {
            const tbody = estagiosRepeater.querySelector('tbody');
            tbody.innerHTML = '';
            estagios.forEach((estagio, i) => {
                const tr = document.createElement('tr');
                tr.dataset.order = i;
                tr.dataset.title = estagio.title;
                tr.dataset.effort = estagio.effort;
                tr.dataset.cost = estagio.cost;
                tr.draggable = true;

                const td1 = document.createElement('td');
                td1.innerHTML = `
                <svg class="icon-draggable" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-grip-vertical"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M9 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M9 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M15 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M15 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M15 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>`;
                td1.innerHTML += i + 1;
                tr.appendChild(td1);

                const td2 = document.createElement('td');
                const estagioInput = document.createElement('input');
                estagioInput.type = 'text';
                estagioInput.name = `new-estagio-${i}`;
                estagioInput.id = `new-estagio-${i}`;
                estagioInput.value = estagio.title !== undefined && estagio.title ? estagio.title : '';
                estagioInput.classList.add('form-control');
                estagioInput.classList.add('focus-input');

                estagioInput.addEventListener('input', () => {
                    estagios[i].title = estagioInput.value;
                });
                if (!estagioInput.value) {
                    estagioInput.classList.add('show-bd');
                }
                estagioInput.addEventListener('focus', () => {
                    estagioInput.classList.add('show-bd');
                });
                estagioInput.addEventListener('blur', () => {
                    if (estagioInput.value) {
                        estagioInput.classList.remove('show-bd');
                    }
                });
                td2.append(estagioInput);
                tr.appendChild(td2);

                const td3 = document.createElement('td');
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.classList.add('btn');
                btn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M18 9L17.16 17.398C17.033 18.671 16.97 19.307 16.68 19.788C16.4257 20.2114 16.0516 20.55 15.605 20.761C15.098 21 14.46 21 13.18 21H10.82C9.541 21 8.902 21 8.395 20.76C7.94805 20.5491 7.57361 20.2106 7.319 19.787C7.031 19.307 6.967 18.671 6.839 17.398L6 9M13.5 15.5V10.5M10.5 15.5V10.5M4.5 6.5H9.115M9.115 6.5L9.501 3.828C9.613 3.342 10.017 3 10.481 3H13.519C13.983 3 14.386 3.342 14.499 3.828L14.885 6.5M9.115 6.5H14.885M14.885 6.5H19.5" stroke="#7C8FAC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>`;
                btn.addEventListener('click', estagiosRemoveItem);
                td3.appendChild(btn);
                tr.appendChild(td3);
                tbody.appendChild(tr);
            });
        });
    }

    window.addEventListener('load', () => {
        highlightInit();
        inputMasks();
        goBackBtn();
        formGroupImage();
        estagiosRepeater();
        renderEstagios();
        estagioReorderItem();
    });
})();