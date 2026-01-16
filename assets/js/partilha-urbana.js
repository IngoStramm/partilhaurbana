(() => {
    'use strict';

    const estagios = ajax_object.estagios;
    const projetoImages = ajax_object.projeto_images;
    const projetoProjecao = ajax_object.projeto_projecao;
    let projetoObservacoes = ajax_object.projeto_observacoes;
    const projetoPreco = convertStringToNumber(ajax_object.projeto_preco);
    const themeUrl = ajax_object.theme_url;
    const projecaoInputs = [
        'preco-venda',
        'comissao-impostos',
        'certificado-documentacao',
        'imposto-lucro',
        'escrituras-registros',
        'outros-a',
        'outros-b'
    ];
    let reduction = 0;
    let totalEstagiosEffort = 0;
    let totalEstagiosCost = 0;
    let totalGeralCost = 0;
    console.log('estagios', estagios);
    console.log('projetoProjecao', projetoProjecao);
    console.log('projetoObservacoes', projetoObservacoes);

    function showAlert(alertPlaceholder, message, type) {
        const wrapper = document.createElement('div');
        wrapper.classList.add('mt-3');
        wrapper.innerHTML = [
            `<div id="form-alert" class="alert alert-${type} alert-dismissible" role="alert">`,
            `   <div>${message}</div>`,
            '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
            '</div>'
        ].join('');
        alertPlaceholder.append(wrapper);
    }

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

        const previsaoLucroResultadoInputs = document.querySelectorAll('.previsao-lucro-resultado-input');
        const previsaoLucroResultadoMaskOptions = {
            mask: [
                {
                    mask: '+num €',
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
                },
                {
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
                },
            ],
            dispatch: (appended, dynamicMasked) => {
                const maskIndex = dynamicMasked.value > 0 ? 0 : 1;
                return dynamicMasked.compiledMasks[maskIndex];
            }
        };
        previsaoLucroResultadoInputs.forEach(moneyNoDecimalsInput => {
            const previsaoLucroResultadoMask = IMask(moneyNoDecimalsInput, previsaoLucroResultadoMaskOptions);
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

        const pctNoLimitInputs = document.querySelectorAll('.pct-no-limit-input');
        const pctNoLimitMaskOptions = {
            mask: 'num %',
            lazy: false,
            blocks: {
                num: {
                    min: 0,
                    mask: Number,
                }
            }
        };
        pctNoLimitInputs.forEach(pctInput => {
            const pctMask = IMask(pctInput, pctNoLimitMaskOptions);
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

    // Estágios do Projeto
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
            if (estagios.length === 0) {
                const newEstagio = {
                    title: '',
                    cost: 0,
                    effort: 0
                };
                estagios.push(newEstagio);
            }
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
                // estagioInput.name = `new-estagio-${i}`;
                estagioInput.name = `estagios[]`;
                estagioInput.id = `new-estagio-${i}`;
                estagioInput.value = estagio.title !== undefined && estagio.title ? estagio.title : '';
                estagioInput.classList.add('form-control');
                estagioInput.classList.add('focus-input');
                estagioInput.required = true;

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

    // Imagem do projeto
    function removeImage(e) {
        e.preventDefault();
        const btn = e.target;
        const container = btn.closest('.file-image-preview');
        const fileInput = container.querySelector('#featured-image');
        const deleteInput = container.querySelector('#delete-featured-image');
        deleteInput.value = true;
        fileInput.value = null;
        projetoImages.splice(btn.dataset.index, 1);
        renderImagesPreview();
    }

    function addProjetoImage() {
        const fileInput = document.querySelector('#featured-image');
        if (typeof fileInput === undefined || !fileInput) {
            return;
        }
        fileInput.addEventListener('input', e => {
            const newFiles = e.target.files;
            for (const newFile of newFiles) {
                projetoImages.push(URL.createObjectURL(newFile));
            }
            renderImagesPreview();
        });
    }

    function toggleImagesPreviewInput() {
        const container = document.querySelector('#file-image-preview');
        if (projetoImages.length > 0) {
            container.classList.add('has-image');
        } else {
            container.classList.remove('has-image');
        }
    }

    function renderImagesPreview() {
        const imagesPreviewList = document.querySelector('#images-preview');
        if (typeof imagesPreviewList === undefined || !imagesPreviewList) {
            return;
        }

        imagesPreviewList.innerHTML = '';
        projetoImages.forEach((image, i) => {
            const li = document.createElement('li');
            li.classList.add('images-preview-item');

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.classList.add('btn-clear-image');
            btn.ariaLabel = 'Remover imagem';
            btn.dataset.index = i;
            btn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>`;
            btn.addEventListener('click', removeImage);
            li.appendChild(btn);

            const newImage = document.createElement('img');
            newImage.classList.add('image-preview');
            newImage.src = image;
            li.appendChild(newImage);

            imagesPreviewList.appendChild(li);
        });
        toggleImagesPreviewInput();
    }

    function formsValidation() {
        const forms = document.querySelectorAll('.needs-validation');

        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {

                if (event.submitter.name !== 'previous-step' && !form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                if (event.submitter.name !== 'previous-step') {
                    form.classList.add('was-validated');
                }
            }, false);
        });
    }

    function projetoSettingsForm() {
        const projetoSettingsForms = document.querySelectorAll('.form-settings-projeto');
        const alertPlaceholder = document.getElementById('form-alert-placeholder');
        const newProjetoSuccess_message = sessionStorage.getItem('showSuccessAlert');
        if (newProjetoSuccess_message) {
            showAlert(alertPlaceholder, newProjetoSuccess_message, 'success');
            sessionStorage.removeItem('showSuccessAlert');
        }
        projetoSettingsForms.forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();

                if (typeof document.getElementById('form-alert') !== undefined && document.getElementById('form-alert')) {
                    const formAlert = bootstrap.Alert.getOrCreateInstance('#form-alert');
                    formAlert.close();
                }

                if (!form.checkValidity()) {
                    return;
                }
                console.log('submit');
                tooglePreloader();
                form.classList.add('was-validated');
                const titleInput = form.querySelector('#title');
                const priceInput = form.querySelector('#price');
                const ownerInput = form.querySelector('#owner');
                const featuredImageInput = form.querySelector('#featured-image');
                const btn = form.querySelector('button[type="submit"]');

                console.log('ownerInput', ownerInput.value);


                if (typeof btn === undefined || !btn) {
                    return;
                }

                if (btn.disabled) {
                    return;
                }
                btn.disabled = true;
                const originalBtnHtml = btn.innerHTML;
                btn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   <span class="ml-5">Enviando...</span>`;

                const ajaxUrl = ajax_object.ajax_url;
                const data = new FormData(form);

                const action = data.get('action');
                fetch(ajaxUrl, {
                    method: 'POST',
                    body: data
                })
                    .then((response) => response.json())
                    .then((response) => {
                        const status = response.success ? 'success' : 'danger';
                        showAlert(alertPlaceholder, response.data.msg, status);
                        console.log('dono-do-projeto-id', response.data.post['dono-do-projeto-id']);
                        console.log('owner', response.data.post.owner);
                        console.log('post_owner', response.data.post_owner);
                        console.log('dono_do_projeto_id', response.data.dono_do_projeto_id);
                        console.log('post', response.data.post);
                        if (status === 'success') {
                            titleInput.value = response.data.projetos_data.title;
                            priceInput.value = response.data.projetos_data.price;
                            ownerInput.value = response.data.projetos_data.owner;
                            featuredImageInput.value = '';
                            projetoImages.length = 0;
                            projetoImages.push(...response.data.projetos_data.images);
                            renderImagesPreview();
                            renderEstagios();
                            featuredImageInput.dispatchEvent(new Event('input', { bubbles: true }));
                            priceInput.dispatchEvent(new Event('input', { bubbles: true }));
                            if (response.data.redirect_to) {
                                window.location = response.data.redirect_to;
                                sessionStorage.setItem('showSuccessAlert', response.data.msg);
                            }
                        }
                    })
                    .catch((error) => {
                        showAlert(alertPlaceholder, error, 'danger');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalBtnHtml;
                        form.classList.remove('was-validated');
                        tooglePreloader(false);
                    });

            });
        });
    }

    function loadedSettingsForm() {
        const projetoSettingsForm = document.querySelector('#form-settings-projeto');
        if (typeof projetoSettingsForm === undefined || !projetoSettingsForm) {
            return;
        }
        const btn = projetoSettingsForm.querySelector('button[type="submit"]');
        btn.disabled = false;
    }

    function tooglePreloader(show = true) {
        const preloaderId = 'preloader';
        const existingPreloader = document.querySelector(`#${preloaderId}`);
        if (typeof existingPreloader !== undefined && existingPreloader) {
            existingPreloader.classList.add('hiding');
            setTimeout(() => {
                existingPreloader.classList.remove('hiding');
                existingPreloader.remove();
            }, 1000);
        }
        if (!show) {
            return;
        }
        const newPreloader = document.createElement('div');
        newPreloader.id = preloaderId;
        newPreloader.classList.add('preloader');
        newPreloader.innerHTML = `
        <div 
            class="spinner-grow text-primary" 
            style="width: 3rem; height: 3rem" 
            role="status">
            <span class="visually-hidden">Processando...</span>
        </div>`;
        document.body.appendChild(newPreloader);
    }

    function inputEstagioEffortEvts() {
        const effortInputs = document.querySelectorAll('.effort-input');
        effortInputs.forEach((effortInput, i) => {
            effortInput.addEventListener('input', e => {
                let effort = e.target.value;
                effort = effort.replace(/%/g, '');
                effort = convertStringToNumber(effort);
                estagios[i].effort = effort;
                calcEstagioTotais();
                renderEstagiosTotais();
            });
            effortInput.addEventListener('blur', e => {
                if (!e.target.value || e.target.value === ' %') {
                    e.target.value = 0;
                    e.target.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        });
    }

    function inputEstagioCostEvts() {
        const costInputs = document.querySelectorAll('.cost-input');
        costInputs.forEach((costInput, i) => {
            costInput.addEventListener('input', e => {
                let cost = e.target.value;
                cost = cost.replace(/%/g, '');
                cost = convertStringToNumber(cost);
                cost = isNaN(cost) ? 0 : cost;
                estagios[i].cost = cost;
                calcEstagioTotais();
                calcTotalCost();
                renderPrevisaoLucro();
                renderROI();
                renderEstagiosTotais();
            });
            costInput.addEventListener('blur', e => {
                if (!e.target.value || e.target.value === ' €') {
                    e.target.value = 0;
                    e.target.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        });
    }

    function calcEstagioTotais() {
        totalEstagiosCost = 0;
        totalEstagiosEffort = 0;
        estagios.forEach((estagio, i) => {
            totalEstagiosEffort += parseInt(estagio.effort);
            totalEstagiosCost += parseInt(estagio.cost);
        });
    }

    function renderEstagiosTotais() {
        const totalEffortInput = document.querySelector('#total-estagios-effort');
        const totalCostInput = document.querySelector('#total-estagios-cost');
        const totalCostView = document.querySelector('#total-estagios-cost-view');
        totalEffortInput.value = totalEstagiosEffort;
        totalEffortInput.dispatchEvent(new Event('input', { bubbles: true }));
        totalCostInput.value = totalEstagiosCost;
        totalCostInput.dispatchEvent(new Event('input', { bubbles: true }));
        totalCostView.value = totalEstagiosCost;
        totalCostView.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function renderEstagiosInputs() {
        estagios.forEach((estagio, i) => {
            const inputEffort = document.querySelector(`#effort-${i}`);
            inputEffort.value = estagio.effort;
            inputEffort.dispatchEvent(new Event('input', { bubbles: true }));

            const inputCost = document.querySelector(`#cost-${i}`);
            inputCost.value = estagio.cost;
            inputCost.dispatchEvent(new Event('input', { bubbles: true }));
        });
    }

    function convertStringToNumber(str) {
        let value = '';
        value = str.replace('.', '');
        value = value.replace(',', '.');
        value = parseFloat(value);
        return value;
    }

    function formatNumberToMoney(num) {
        if (isNaN(num) || num === 0) {
            return num;
        }
        const formattedMoney = new Intl.NumberFormat('de-DE', {
            style: 'currency',
            currency: 'EUR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0,
        });
        return formattedMoney.format(num);
    }

    function atualizaProjecaoInput(e) {
        const propNewValue = e.target.value;
        const objectKeyname = e.target.id.replace(/-/g, '_');
        projetoProjecao[objectKeyname] = propNewValue;
        renderProjecaoResultados();
    }

    function renderProjecaoResultados() {
        const formProjecaoProjeto = document.querySelector('.form-projecao-projeto');
        if (typeof formProjecaoProjeto === undefined || !formProjecaoProjeto) {
            return;
        }
        let newReduction = 0;
        projecaoInputs.forEach(selector => {
            const keyname = selector.replace(/-/g, '_');
            const subtotalInput = document.querySelector(`#${selector}-total`);
            const formGroup = subtotalInput.closest('.form-group');
            let subtotalNewValue = 0;
            if (projetoProjecao[`${keyname}_tipo`] === 'fixed') {
                // Valor fixo
                if (keyname === 'preco_venda') {
                    subtotalNewValue = Math.round(projetoProjecao[`${keyname}_valor`]) - projetoPreco;
                } else {
                    subtotalNewValue = Number(projetoProjecao[`${keyname}_valor`]);
                    subtotalNewValue = subtotalNewValue > 0 ? -subtotalNewValue : subtotalNewValue;
                }
            } else {
                // Valor porcentagem
                subtotalNewValue = Math.round((projetoProjecao[`${keyname}_valor`] / 100) * projetoPreco);
                if (keyname === 'preco_venda') {
                    // pegar o valor da porcentagem e subtrair o valor de compra
                    subtotalNewValue = Math.round(subtotalNewValue - projetoPreco);
                } else {
                    subtotalNewValue = -subtotalNewValue;
                }
            }
            // reduction += keyname === 'preco_venda' ? -subtotalNewValue : subtotalNewValue;
            newReduction += subtotalNewValue;
            subtotalInput.value = subtotalNewValue;
            formGroup.classList.remove('green-input');
            formGroup.classList.remove('pink-input');
            if (subtotalNewValue < 0) {
                formGroup.classList.add('pink-input');
            } else {
                formGroup.classList.add('green-input');
            }
            subtotalInput.dispatchEvent(new Event('input', { bubbles: true }));
        });
        newReduction = Math.round(newReduction);
        reduction = newReduction;
        renderPrevisaoLucro();
        calcTotalCost();
        renderROI();
    }

    function calcProjecao() {
        const formProjecao = document.querySelector('#form-projecao-projeto');
        if (typeof formProjecao === undefined || !formProjecao) {
            return;
        }
        projecaoInputs.forEach((selector, i) => {
            document.querySelector(`#${selector}-valor`).addEventListener('input', atualizaProjecaoInput);
            document.querySelector(`#${selector}-tipo`).addEventListener('input', atualizaProjecaoInput);
        });
    }

    function calcProjecaoLucro() {
        let lucro = 0;
        // console.log('projetoProjecao.preco_venda_valor', projetoProjecao.preco_venda_valor);
        // console.log('projetoPreco', projetoPreco);
        // lucro = projetoProjecao.preco_venda_valor - projetoPreco;
        // console.log('lucro', lucro);
        // console.log('reduction', reduction);
        lucro += reduction;
        // console.log('lucro', lucro);
        // console.log('totalEstagiosCost', totalEstagiosCost);
        lucro -= totalEstagiosCost;
        // console.log('lucro', lucro);
        // console.log('===============');

        return lucro;
    }

    function renderPrevisaoLucro() {
        const previsaoLucroResultadoInput = document.querySelector('#previsao-lucro-resultado');
        if (typeof previsaoLucroResultadoInput === undefined || !previsaoLucroResultadoInput) {
            return;
        }
        previsaoLucroResultadoInput.value = calcProjecaoLucro();
        previsaoLucroResultadoInput.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function renderObservacoes() {
        const observacoes_textarea = document.querySelector('#projeto-observacoes');
        observacoes_textarea.value = projetoObservacoes;
    }

    function projetoProjecaoForm() {
        const projetoProjecaoForms = document.querySelectorAll('.form-projecao-projeto');
        const alertPlaceholder = document.getElementById('form-alert-placeholder');
        const newProjetoSuccess_message = sessionStorage.getItem('showSuccessAlert');
        if (newProjetoSuccess_message) {
            showAlert(alertPlaceholder, newProjetoSuccess_message, 'success');
            sessionStorage.removeItem('showSuccessAlert');
        }
        projetoProjecaoForms.forEach(form => {
            form.addEventListener('submit', e => {
                e.preventDefault();

                if (typeof document.getElementById('form-alert') !== undefined && document.getElementById('form-alert')) {
                    const formAlert = bootstrap.Alert.getOrCreateInstance('#form-alert');
                    formAlert.close();
                }

                if (!form.checkValidity()) {
                    return;
                }
                console.log('submit');
                tooglePreloader();
                form.classList.add('was-validated');
                const precoVendaValorInput = form.querySelector('#preco-venda-valor');
                const precoVendaTipoInput = form.querySelector('#preco-venda-tipo');
                const btn = form.querySelector('button[type="submit"]');

                if (typeof btn === undefined || !btn) {
                    return;
                }

                if (btn.disabled) {
                    return;
                }
                btn.disabled = true;
                const originalBtnHtml = btn.innerHTML;
                btn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   <span class="ml-5">Enviando...</span>`;

                const ajaxUrl = ajax_object.ajax_url;
                const data = new FormData(form);
                const action = data.get('action');
                fetch(ajaxUrl, {
                    method: 'POST',
                    body: data
                })
                    .then((response) => response.json())
                    .then((response) => {
                        const status = response.success ? 'success' : 'danger';
                        showAlert(alertPlaceholder, response.data.msg, status);
                        console.log('response', response);
                        if (status === 'success') {
                            precoVendaValorInput.value = response.data.projetos_data.preco_venda_valor;
                            precoVendaValorInput.dispatchEvent(new Event('input', { bubbles: true }));
                            precoVendaTipoInput.value = response.data.projetos_data.preco_venda_tipo;
                            precoVendaTipoInput.dispatchEvent(new Event('input', { bubbles: true }));

                            // Atualiza projetoProjecao com os valores recebidos
                            for (let key in projetoProjecao) {
                                if (Object.hasOwnProperty.call(projetoProjecao, key)) {
                                    projetoProjecao[key] = response.data.projetos_data[key];
                                }
                            }
                            // Atualiza estagios com os valores recebidos
                            for (let key in estagios) {
                                if (Object.hasOwnProperty.call(estagios, key)) {
                                    estagios[key] = response.data.estagios[key];
                                }
                            }
                            // Atualiza projetoObservacoes com o valor recebido
                            projetoObservacoes = response.data.projeto_observacoes;

                            calcEstagioTotais();
                            renderEstagiosTotais();
                            renderEstagiosInputs();
                            renderProjecaoResultados();
                            renderObservacoes();
                        }
                    })
                    .catch((error) => {
                        showAlert(alertPlaceholder, error, 'danger');
                    })
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = originalBtnHtml;
                        form.classList.remove('was-validated');
                        tooglePreloader(false);
                    });

            });
        });
    }

    function loadedProjecaoForm() {
        const projetoProjecaoForm = document.querySelector('#form-projecao-projeto');
        if (typeof projetoProjecaoForm === undefined || !projetoProjecaoForm) {
            return;
        }
        const btn = projetoProjecaoForm.querySelector('button[type="submit"]');
        btn.disabled = false;
    }

    function calcTotalCost() {
        totalGeralCost = 0;
        totalGeralCost += Math.abs(projetoPreco);
        totalGeralCost += Math.abs(totalEstagiosCost);
        projecaoInputs.forEach(selector => {
            const keyname = selector.replace(/-/g, '_');
            if (keyname !== 'preco_venda') {
                if (projetoProjecao[`${keyname}_tipo`] === 'fixed') {
                    totalGeralCost += Number(projetoProjecao[`${keyname}_valor`]);
                } else {
                    totalGeralCost += Math.round((projetoProjecao[`${keyname}_valor`] / 100) * projetoPreco);
                }
            }
        });
    }

    function calculaROI() {
        const lucro = calcProjecaoLucro();
        let resultado = 0;
        if (lucro !== 0 && totalGeralCost !== 0) {
            resultado = lucro / totalGeralCost;
        }
        resultado = resultado * 100;
        resultado = Math.round(resultado);
        return resultado;
    }

    function renderROI() {
        const lucro = calcProjecaoLucro();
        const roisDivs = document.querySelectorAll('.roi');
        roisDivs.forEach(roiDiv => {
            const resultadoROI = calculaROI();
            const roiLucroSpan = roiDiv.querySelector('.roi-lucro');
            const roiCustoSpan = roiDiv.querySelector('.roi-custo');
            const roiResultadoSpan = roiDiv.querySelector('.roi-resultado');
            roiLucroSpan.textContent = formatNumberToMoney(lucro);
            roiCustoSpan.textContent = formatNumberToMoney(totalGeralCost);
            roiResultadoSpan.textContent = resultadoROI;
        });
    }

    window.addEventListener('load', () => {
        highlightInit();
        inputMasks();
        goBackBtn();
        estagiosRepeater();
        renderEstagios();
        estagioReorderItem();
        renderImagesPreview();
        addProjetoImage();
        formsValidation();
        projetoSettingsForm();
        loadedSettingsForm();
        inputEstagioEffortEvts();
        inputEstagioCostEvts();
        calcProjecao();
        renderProjecaoResultados();
        renderPrevisaoLucro();
        projetoProjecaoForm();
        loadedProjecaoForm();
    });
})();