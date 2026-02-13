(async () => {
    'use strict';

    const estagios = ajax_object.estagios;
    const projetoImages = ajax_object.projeto_images;
    const projetoProjecao = ajax_object.projeto_projecao;
    let projetoObservacoes = ajax_object.projeto_observacoes;
    const projetoPreco = convertStringToNumber(ajax_object.projeto_preco);
    const themeUrl = ajax_object.theme_url;
    const siteUrl = ajax_object.site_url;
    const currUrl = ajax_object.curr_url;
    let lancamentosFinanceiros = ajax_object.lancamentos_financeiros;
    let diariosDaObra = ajax_object.diarios_da_obra;
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
    const CACHE_NAME = 'v1-image-cache';
    const TTL_MS = 24 * 60 * 60 * 1000; // 24 horas em milissegundos

    // Cache de Imagens
    async function createCachedImage(url) {
        const cache = await caches.open(CACHE_NAME);
        let cachedResponse = await cache.match(url);

        if (cachedResponse) {
            const fetchedDate = cachedResponse.headers.get('sw-fetched-on');

            // Verifica se expirou
            if (fetchedDate && (Date.now() - new Date(fetchedDate).getTime()) > TTL_MS) {
                await cache.delete(url);
                cachedResponse = null; // Força o re-download
                console.log("Cache expirado, baixando novamente...");
            }
        }

        if (!cachedResponse) {
            const response = await fetch(url);
            // Clonamos a resposta para adicionar o header de data manualmente
            const headers = new Headers(response.headers);
            headers.append('sw-fetched-on', new Date().toISOString());

            const responseToCache = new Response(await response.blob(), {
                status: response.status,
                statusText: response.statusText,
                headers: headers
            });

            await cache.put(url, responseToCache.clone());
            cachedResponse = responseToCache;
        }

        const blob = await cachedResponse.blob();
        const img = document.createElement('img');
        img.classList.add('loading');
        img.src = URL.createObjectURL(blob);
        return img;
    }

    // Limpeza em massa de itens expirados (pode ser chamada ao iniciar o app)
    async function cleanExpiredCache() {
        const cache = await caches.open(CACHE_NAME);
        const keys = await cache.keys();

        for (const request of keys) {
            const response = await cache.match(request);
            const date = response.headers.get('sw-fetched-on');
            if (date && (Date.now() - new Date(date).getTime()) > TTL_MS) {
                await cache.delete(request);
                console.log(`Item removido por TTL: ${request.url}`);
            }
        }
    }

    async function getAndCacheVideoFrame(videoUrl) {
        const cache = await caches.open(CACHE_NAME);
        // Procuramos no cache usando a URL do vídeo como identificador
        let cachedResponse = await cache.match(videoUrl);

        if (cachedResponse) {
            const fetchedDate = cachedResponse.headers.get('sw-fetched-on');
            if (fetchedDate && (Date.now() - new Date(fetchedDate).getTime()) < TTL_MS) {
                const blob = await cachedResponse.blob();
                return URL.createObjectURL(blob);
            }
        }

        // Se não houver cache válido, gera o frame do vídeo
        const dataUri = await capturarPrimeiroFrame(videoUrl);

        // Converte Data URI para Blob para salvar no Cache API de forma eficiente
        const res = await fetch(dataUri);
        const blob = await res.blob();

        const headers = new Headers();
        headers.append('sw-fetched-on', new Date().toISOString());
        headers.append('Content-Type', 'image/jpeg');

        const responseToCache = new Response(blob, { headers });
        await cache.put(videoUrl, responseToCache);

        return URL.createObjectURL(blob);
    }

    function capturarPrimeiroFrame(url) {
        return new Promise((resolve, reject) => {
            const v = document.createElement('video');
            v.src = url;
            v.crossOrigin = "anonymous";
            v.muted = true;
            v.preload = "auto";

            v.onloadeddata = () => v.currentTime = 0.5; // Meio segundo para evitar telas pretas
            v.onseeked = () => {
                const canvas = document.createElement('canvas');
                canvas.width = v.videoWidth;
                canvas.height = v.videoHeight;
                canvas.getContext('2d').drawImage(v, 0, 0);
                resolve(canvas.toDataURL('image/jpeg', 0.8));
            };
            v.onerror = (e) => reject("Erro ao carregar vídeo: " + e);
        });
    }

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

    function setInputValue(inputEl, val = '') {
        inputEl.value = val;
        inputEl.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function setDateInputValue(inputEl, val = '') {
        if (val) {
            const parts = val.split('-');
            const day = parts[0];
            const month = parts[1];
            const year = parts[2];
            const formattedDateString = `${year}-${month}-${day}`;
            val = formattedDateString;
        }
        setInputValue(inputEl, val);
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
                tooglePreloader();
                form.classList.add('was-validated');
                const titleInput = form.querySelector('#title');
                const priceInput = form.querySelector('#price');
                const ownerInput = form.querySelector('#owner');
                const featuredImageInput = form.querySelector('#featured-image');
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
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
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
        lucro += reduction;
        lucro -= totalEstagiosCost;
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
        const saveBtn = projetoProjecaoForm.querySelector('#btn-salvar-projeto');
        const convertToObraBtn = projetoProjecaoForm.querySelector('#btn-transformar-projeto-em-obra');
        const removeBtn = projetoProjecaoForm.querySelector('#btn-remove-projeto');
        saveBtn.disabled = false;
        convertToObraBtn.disabled = false;
        removeBtn.disabled = false;
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

    function removeProjeto() {
        const removeBtn = document.querySelector('#btn-remove-projeto');
        if (typeof removeBtn === undefined || !removeBtn) {
            return;
        }
        removeBtn.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();
            tooglePreloader();
            const alertPlaceholder = document.getElementById('form-alert-placeholder');
            if (typeof document.getElementById('form-alert') !== undefined && document.getElementById('form-alert')) {
                const formAlert = bootstrap.Alert.getOrCreateInstance('#form-alert');
                formAlert.close();
            }
            if (sessionStorage.getItem('removeProjetoMsg')) {
                sessionStorage.removeItem('removeProjetoMsg');
            }
            removeBtn.disabled = true;
            const originalBtnHtml = removeBtn.innerHTML;
            removeBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   <span class="ml-5">Removendo...</span>`;
            const postId = e.target.dataset.postId;
            const userId = e.target.dataset.userId;
            const action = e.target.dataset.action;
            const nonce = e.target.dataset.nonce;
            const ajaxUrl = ajax_object.ajax_url;
            const data = new FormData();
            data.append('post-id', postId);
            data.append('user-id', userId);
            data.append('action', action);
            data.append('nonce', nonce);
            fetch(ajaxUrl, {
                method: 'POST',
                body: data
            })
                .then((response) => response.json())
                .then((response) => {
                    const status = response.success ? 'success' : 'danger';
                    showAlert(alertPlaceholder, response.data.msg, status);
                    if (status === 'success') {
                        if (response.data.redirect_to) {
                            sessionStorage.setItem('removeProjetoMsg', response.data.msg);
                            window.location = response.data.redirect_to;
                        }
                    }
                })
                .catch((error) => {
                    showAlert(alertPlaceholder, error, 'danger');
                })
                .finally(() => {
                    removeBtn.disabled = false;
                    removeBtn.innerHTML = originalBtnHtml;
                    tooglePreloader(false);
                });

        });
    }

    function alertRemovedProjeto() {
        if (!sessionStorage.getItem('removeProjetoMsg')) {
            return;
        }
        const msg = sessionStorage.getItem('removeProjetoMsg');
        sessionStorage.removeItem('removeProjetoMsg');
        const bodyWrapper = document.querySelector('.body-wrapper');
        const firstCol = bodyWrapper.querySelector('.col-md-12');
        showAlert(firstCol, msg, 'success');
    }

    function comparePasswords(e) {
        const container = e.target.closest('form');
        const pass1 = document.querySelector('#user_pass');
        const pass2 = document.querySelector('#repeat_pass');
        container.classList.remove('was-validated');

        if (pass1.value !== pass2.value) {
            container.classList.add('was-validated');
            pass1.setCustomValidity('As senhas precisam combinar.');
            pass2.setCustomValidity('As senhas precisam combinar.');
            container.classList.add('was-validated');

        } else {
            pass1.setCustomValidity('');
            pass2.setCustomValidity('');
        }
    }

    function checkPasswordsEvt() {
        const passwordInputs = document.querySelectorAll('.check-pass');
        passwordInputs.forEach(passwordInput => {
            passwordInput.addEventListener('keyup', comparePasswords);
        });
    }

    // Obra

    function selectObraContentRedirect() {
        const formSelectObraContent = document.querySelector('#form-select-obra-content');
        if (typeof formSelectObraContent === undefined || !formSelectObraContent) {
            return;
        }
        const selectObraContent = formSelectObraContent.querySelector('#select-obra-content');
        selectObraContent.addEventListener('input', e => {
            const selectedOption = e.target.value ? `${siteUrl}/${e.target.value}` : currUrl;
            formSelectObraContent.requestSubmit();
        });
    }

    // Financeiro

    function financeiroObraTableInit() {
        if (!lancamentosFinanceiros) {
            return;
        }
        renderFinanceiroObraTable(lancamentosFinanceiros);
        renderFinanceiroObraTableTotals(lancamentosFinanceiros);
        financeiroObraTableFiltersEvts();
    }

    function sortLancamentosFinanceiros(rowsData) {
        rowsData.sort((a, b) => {
            const dateA = a.date.split('-').reverse().join('-');
            const dateB = b.date.split('-').reverse().join('-');
            return new Date(dateB) - new Date(dateA);
        });
    }

    function calcFinanceiroObraTableTotals(rowsData) {
        let totalEntradas = 0;
        let totalSaidas = 0;
        rowsData.forEach(row => {
            if (row.tipo === 'entrada') {
                totalEntradas += parseFloat(row.valor);
            } else {
                totalSaidas += parseFloat(row.valor);
            }
        });
        return {
            'total-entradas': totalEntradas,
            'total-saidas': totalSaidas
        };
    }

    function renderFinanceiroObraTableTotals(rowsData) {
        const totals = calcFinanceiroObraTableTotals(rowsData);

        const totalEntradaLancamentosInput = document.querySelector('#total-entrada-lancamentos');
        if (typeof totalEntradaLancamentosInput === undefined || !totalEntradaLancamentosInput) {
            return;
        }
        const totalSaidaLancamentosInput = document.querySelector('#total-saida-lancamentos');
        if (typeof totalSaidaLancamentosInput === undefined || !totalSaidaLancamentosInput) {
            return;
        }
        totalEntradaLancamentosInput.value = totals['total-entradas'];
        totalEntradaLancamentosInput.dispatchEvent(new Event('input', { bubbles: true }));
        totalSaidaLancamentosInput.value = totals['total-saidas'];
        totalSaidaLancamentosInput.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function renderFinanceiroObraTable(rowsData) {
        sortLancamentosFinanceiros(rowsData);
        const table = document.querySelector('#table-financeiro-obra');
        if (typeof table === undefined || !table) {
            return;
        }
        const tbody = table.querySelector('tbody');
        tbody.innerHTML = '';
        const rowsArray = [];
        if (rowsData.length > 0) {
            rowsData.forEach(row => {
                const tr = document.createElement('tr');
                tr.dataset.id = row.id;
                const lancamentoTitle = row.comprovante ? `<a class="table-link" target="_blank" href="${row.comprovante}">${row.title}</a>` : row.title;
                const downloadIcon = row.comprovante ?
                    `<a target="_blank" href="${row.comprovante}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none"><path d="M5.87984 9.50008L9.49888 13.329L13.1179 9.53718M9.49888 3.16675V13.1191M4.07031 15.8334H14.9275" stroke="#5A6A85" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>` :
                    '';
                const valor = formatNumberToMoney(row.valor);
                const btnModal = document.createElement('button');
                btnModal.classList.add('btn-no-style');
                btnModal.type = 'button';
                btnModal.dataset.id = row.id;
                btnModal.dataset.bsToggle = 'modal';
                btnModal.dataset.bsTarget = '#modal-editar-lancamento-financeiro-obra';
                btnModal.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5A6A85" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>`;

                const formattedDateString = row.date.replaceAll('-', '/');
                const tdDate = document.createElement('td');
                tdDate.append(formattedDateString);
                tr.append(tdDate);

                const tdTipo = document.createElement('td');
                tdTipo.innerHTML = `${row.tipo_icon} ${row.tipo}`;
                tr.append(tdTipo);

                const tdAuthor = document.createElement('td');
                tdAuthor.append(row.author);
                tr.append(tdAuthor);

                const tdEstagio = document.createElement('td');
                tdEstagio.append(row.estagio);
                tr.append(tdEstagio);

                const tdTitle = document.createElement('td');
                tdTitle.innerHTML = lancamentoTitle;
                tr.append(tdTitle);

                const tdSku = document.createElement('td');
                tdSku.append(row.sku);
                tr.append(tdSku);

                const tdIcon = document.createElement('td');
                tdIcon.innerHTML = downloadIcon;
                tr.append(tdIcon);

                const tdValor = document.createElement('td');
                tdValor.append(valor);
                tr.append(tdValor);

                const tdModal = document.createElement('td');
                tdModal.append(btnModal);
                tr.append(tdModal);
                rowsArray.push(tr);
            });
        } else {
            const tr = document.createElement('tr');
            tr.dataset.id = '0';
            const td =
                `<td colspan="7">
                    <div class="not-found-container">
                        <h2 class="not-found-message">Não há nada por aqui.</h2>
                    </div>
                </td>`;
            tr.innerHTML = td;
            rowsArray.push(tr);
        }
        tbody.append(...rowsArray);
    }

    function financeiroObraTableFiltersEvts() {
        const estagiosLancamentosSelect = document.querySelector('#estagios-lancamentos');
        estagiosLancamentosSelect.addEventListener('input', filterLancamentos);

        const dataLancamentosSelectAsc = document.querySelector('#data-lancamentos-asc');
        dataLancamentosSelectAsc.addEventListener('input', filterLancamentos);

        const dataLancamentosSelectDesc = document.querySelector('#data-lancamentos-desc');
        dataLancamentosSelectDesc.addEventListener('input', filterLancamentos);

        const search = document.querySelector('#search-lancamentos');
        search.addEventListener('input', filterLancamentos);
    }

    function filterLancamentos(e) {
        const selectedEstagio = document.querySelector('#estagios-lancamentos').value;
        const selectedDataStart = document.querySelector('#data-lancamentos-asc').value;
        const selectedDataEnd = document.querySelector('#data-lancamentos-desc').value;
        const search = document.querySelector('#search-lancamentos').value;
        let results = lancamentosFinanceiros;
        console.log('results', results);


        // Filtra estágio
        if (selectedEstagio) {
            results = results.filter(item => {
                if (item.estagio === selectedEstagio) {
                    return item;
                }
            });
        }

        // Filtra data início
        if (selectedDataStart) {
            results = results.filter(item => {
                let dateStart = selectedDataStart;
                const [year1, month1, day1] = selectedDataStart.split('-');
                dateStart = `${month1}-${day1}-${year1}`;
                dateStart = new Date(dateStart);

                let itemDate = item.date;
                const [day2, month2, year2] = item.date.split('-');
                itemDate = `${month2}-${day2}-${year2}`;
                itemDate = new Date(itemDate);

                if (itemDate >= dateStart) {
                    return item;
                }
            });
        }

        // Filtra data fim
        if (selectedDataEnd) {
            results = results.filter(item => {
                let dateEnd = selectedDataEnd;
                const [year1, month1, day1] = selectedDataEnd.split('-');
                dateEnd = `${month1}-${day1}-${year1}`;
                dateEnd = new Date(dateEnd);

                let itemDate = item.date;
                const [day2, month2, year2] = item.date.split('-');
                itemDate = `${month2}-${day2}-${year2}`;
                itemDate = new Date(itemDate);

                if (itemDate <= dateEnd) {
                    return item;
                }
            });
        }

        // Filtra search
        if (search) {
            const searchValue = search.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
            results = results.filter(item => {
                const title = item.title.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                const author = item.author.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                const tipo = item.tipo.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                const valor = item.valor.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");
                const sku = item.sku.toLowerCase().normalize("NFD").replace(/[\u0300-\u036f]/g, "");

                let filter = true;
                if (title.includes(searchValue)) {
                    filter = false;
                }
                if (author.includes(searchValue)) {
                    filter = false;
                }
                if (tipo.includes(searchValue)) {
                    filter = false;
                }
                if (valor.includes(searchValue)) {
                    filter = false;
                }
                if (sku.includes(searchValue)) {
                    filter = false;
                }
                if (!filter) {
                    return item;
                }
            });
        }
        renderFinanceiroObraTable(results);
        renderFinanceiroObraTableTotals(results);
    }

    function resetLancamentoFinanceiroInputs() {
        const form = document.getElementById('form-edit-lancamento-financeiro-obra');
        if (typeof form === undefined || !form) {
            return;
        }
        const fornecedorLancamentoSelect = form.querySelector('#fornecedor-lancamento');
        const codigoFaturaLancamentoInput = form.querySelector('#codigo-fatura-lancamento');
        const skuFaturaLancamentoInput = form.querySelector('#sku-lancamento');
        const dataLancamentoInput = form.querySelector('#data-lancamento');
        const tipoLancamentoSelect = form.querySelector('#tipo-lancamento');
        const estagioLancamentoSelect = form.querySelector('#estagio-lancamento');
        const titleLancamentoInput = form.querySelector('#title-lancamento');
        const valorUnitarioLancamentoInput = form.querySelector('#valor-unitario-lancamento');
        const quantidadeLancamentoInput = form.querySelector('#quantidade-lancamento');
        const valorLancamentoInput = form.querySelector('#valor-lancamento');
        const arquivoLancamentoInput = form.querySelector('#arquivo-lancamento');
        const arquivoLancamentoUrlInput = form.querySelector('#arquivo-lancamento-url');
        const arquivoLancamentoUrlDiv = form.querySelector('#arquivo-lancamento-url-text');
        const postIdInput = form.querySelector('[name="post_id"]');
        const btnDelete = form.querySelector('#btn-delete-lancamento');
        const btnSubmit = form.querySelector('button[type="submit"]');

        form.classList.remove('was-validate');
        form.classList.add('not-validate');

        setInputValue(fornecedorLancamentoSelect);
        setInputValue(codigoFaturaLancamentoInput);
        setInputValue(skuFaturaLancamentoInput);
        setInputValue(dataLancamentoInput);
        setInputValue(tipoLancamentoSelect);
        setInputValue(estagioLancamentoSelect);
        setInputValue(titleLancamentoInput);
        setInputValue(valorUnitarioLancamentoInput, '0,00');
        setInputValue(quantidadeLancamentoInput, 1);
        setInputValue(valorLancamentoInput, '0,00');
        setInputValue(arquivoLancamentoInput);
        setInputValue(arquivoLancamentoUrlInput);
        setInputValue(postIdInput);
        arquivoLancamentoUrlDiv.innerHTML = '';

        btnDelete.disabled = false;
        btnSubmit.innerHTML = btnSubmit.dataset.originalText;
        btnSubmit.disabled = false;
    }

    function modalLancamentoFinanceiro() {
        const modalLancamentoFinanceiro = document.getElementById('modal-editar-lancamento-financeiro-obra');
        if (typeof modalLancamentoFinanceiro === undefined || !modalLancamentoFinanceiro) {
            return;
        }
        modalLancamentoFinanceiro.addEventListener('hidden.bs.modal', event => {
            resetLancamentoFinanceiroInputs();
            const alertPlaceholder = document.getElementById('modal-alert-placeholder');
            alertPlaceholder.innerHTML = '';
        });
        modalLancamentoFinanceiro.addEventListener('show.bs.modal', event => {
            resetLancamentoFinanceiroInputs();
            const container = event.target;
            const postId = event.relatedTarget.dataset.id;
            const btnSubmit = container.querySelector('#btn-save-lancamento-financeiro');
            const btnDelete = container.querySelector('#btn-delete-lancamento');
            btnSubmit.disabled = true;
            btnDelete.disabled = true;
            const alertPlaceholder = document.getElementById('modal-alert-placeholder');
            alertPlaceholder.innerHTML = '';
            if (typeof postId && postId) {
                getLancamentoFinanceiroData(postId, container);
            } else {
                btnSubmit.disabled = false;
            }
        });
        const valorUnitarioLancamentoInput = document.querySelector('#valor-unitario-lancamento');
        const quantidadeLancamentoInput = document.querySelector('#quantidade-lancamento');
        valorUnitarioLancamentoInput.addEventListener('input', calcValorTotalLancamento);
        valorUnitarioLancamentoInput.addEventListener('blur', setDefaultValueOnBlur);
        quantidadeLancamentoInput.addEventListener('input', calcValorTotalLancamento);
        quantidadeLancamentoInput.addEventListener('blur', setDefaultValueOnBlur);

        const arquivoLancamentoInput = document.querySelector('#arquivo-lancamento');
        const arquivoLancamentoUrlInput = document.querySelector('#arquivo-lancamento-url');
        arquivoLancamentoInput.addEventListener('input', fileInputEvt);
        arquivoLancamentoUrlInput.addEventListener('input', urlInputEvt);
    }

    function getLancamentoFinanceiroData(postId, container) {
        const btnSubmit = container.querySelector('#btn-save-lancamento-financeiro');
        const btnDelete = container.querySelector('#btn-delete-lancamento');
        const alertPlaceholder = document.getElementById('modal-alert-placeholder');
        const ajaxUrl = ajax_object.ajax_url;
        const data = new FormData();
        data.append('action', 'pu_get_lancamento_financeiro_data');
        data.append('post_id', postId);
        fetch(ajaxUrl, {
            method: 'POST',
            body: data
        })
            .then((response) => response.json())
            .then((response) => {
                const status = response.success ? 'success' : 'danger';
                if (status === 'success') {
                    setLancamentoFinanceiroData(response.data.lancamento, container);
                }
            })
            .catch((error) => {
                showAlert(alertPlaceholder, error, 'danger');
            })
            .finally(() => {
                btnSubmit.disabled = false;
                btnDelete.disabled = false;
            });
    }

    function setLancamentoFinanceiroData(lancamento, container) {
        const fornecedorLancamentoSelect = container.querySelector('#fornecedor-lancamento');
        const codigoFaturaLancamentoInput = container.querySelector('#codigo-fatura-lancamento');
        const skuLancamentoInput = container.querySelector('#sku-lancamento');
        const dataLancamentoInput = container.querySelector('#data-lancamento');
        const tipoLancamentoSelect = container.querySelector('#tipo-lancamento');
        const estagioLancamentoSelect = container.querySelector('#estagio-lancamento');
        const titleLancamentoInput = container.querySelector('#title-lancamento');
        const valorUnitarioLancamentoInput = container.querySelector('#valor-unitario-lancamento');
        const quantidadeLancamentoInput = container.querySelector('#quantidade-lancamento');
        const quantidade = lancamento.quantidade ? lancamento.quantidade : 1;
        const valorLancamentoInput = container.querySelector('#valor-lancamento');
        const arquivoLancamentoUrlInput = container.querySelector('#arquivo-lancamento-url');
        const arquivoLancamentoBtn = container.querySelector('#btn-delete-lancamento');
        const postIdInput = container.querySelector('[name="post_id"]');

        setInputValue(fornecedorLancamentoSelect, lancamento.fornecedor_id);
        setInputValue(codigoFaturaLancamentoInput, lancamento.codigo_fatura);
        setInputValue(skuLancamentoInput, lancamento.sku);
        setDateInputValue(dataLancamentoInput, lancamento.date);
        setInputValue(tipoLancamentoSelect, lancamento.tipo);
        setInputValue(estagioLancamentoSelect, lancamento.estagio);
        setInputValue(titleLancamentoInput, lancamento.title);
        setInputValue(valorUnitarioLancamentoInput, lancamento.valor_unitario);
        setInputValue(quantidadeLancamentoInput, quantidade);
        setInputValue(valorLancamentoInput, lancamento.valor);
        if (lancamento.comprovante) {
            setInputValue(arquivoLancamentoUrlInput, lancamento.comprovante);
        }
        setInputValue(postIdInput, lancamento.id);
    }

    function calcValorTotalLancamento(e) {
        const valorUnitarioLancamentoInput = document.querySelector('#valor-unitario-lancamento');
        const quantidadeLancamentoInput = document.querySelector('#quantidade-lancamento');
        if (!valorUnitarioLancamentoInput.value) {
            return;
        }
        if (!quantidadeLancamentoInput.value) {
            return;
        }
        const valor = convertStringToNumber(valorUnitarioLancamentoInput.value);
        let quantidade = 1;
        quantidade = convertStringToNumber(quantidadeLancamentoInput.value);
        if (isNaN(valor)) {
            return;
        }
        if (isNaN(quantidade)) {
            return;
        }
        const valorTotalLancamentoInput = document.querySelector('#valor-lancamento');
        let result = 0;
        result = quantidade * valor;
        setInputValue(valorTotalLancamentoInput, result);
    }

    function setDefaultValueOnBlur(e) {
        const id = e.target.id;
        let defaultValue = 0;
        if (id === 'quantidade-lancamento') {
            defaultValue = 1;
        }
        const currValue = convertStringToNumber(e.target.value);
        if (!currValue || isNaN(currValue)) {
            e.target.value = defaultValue;
            e.target.dispatchEvent(new Event('input', { bubbles: true }));
        }
    }

    function fileInputEvt(e) {
        const arquivoLancamentoUrlInput = document.querySelector('#arquivo-lancamento-url');
        setInputValue(arquivoLancamentoUrlInput, e.target.value);
    }

    function urlInputEvt() {
        const arquivoLancamentoUrlInput = document.querySelector('#arquivo-lancamento-url');
        const arquivoLancamentoUrlDiv = document.querySelector('#arquivo-lancamento-url-text');
        if (arquivoLancamentoUrlInput.value) {
            const fileUrl = arquivoLancamentoUrlInput.value;
            arquivoLancamentoUrlDiv.innerHTML = fileUrl;
            const removeFileBtn = document.createElement('button');
            removeFileBtn.type = 'button';
            removeFileBtn.classList.add('remove-file-btn');
            removeFileBtn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-trash"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M4 7l16 0"></path><path d="M10 11l0 6"></path><path d="M14 11l0 6"></path><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path></svg>`;
            removeFileBtn.addEventListener('click', removeFileEvt);
            arquivoLancamentoUrlDiv.append(removeFileBtn);
        } else {
            arquivoLancamentoUrlDiv.innerHTML = '';
        }
    }

    function removeFileEvt() {
        const arquivoLancamentoInput = document.querySelector('#arquivo-lancamento');
        setInputValue(arquivoLancamentoInput);

    }

    function editLancamentoFinanceiroObraForm() {
        const form = document.querySelector('#form-edit-lancamento-financeiro-obra');
        const modalAlertPlaceholder = document.getElementById('modal-alert-placeholder');
        const tableAlertPlaceholder = document.getElementById('table-alert-placeholder');
        const newLancamentoSuccessMessage = sessionStorage.getItem('showSuccessAlert');
        if (newLancamentoSuccessMessage) {
            showAlert(modalAlertPlaceholder, newLancamentoSuccessMessage, 'success');
            sessionStorage.removeItem('showSuccessAlert');
        }
        form.addEventListener('submit', e => {
            e.preventDefault();
            if (typeof document.getElementById('form-alert') !== undefined && document.getElementById('form-alert')) {
                const formAlert = bootstrap.Alert.getOrCreateInstance('#form-alert');
                formAlert.close();
            }

            if (!form.checkValidity()) {
                return;
            }

            form.classList.add('was-validated');
            const btnSubmit = form.querySelector('#btn-save-lancamento-financeiro');

            if (typeof btnSubmit === undefined || !btnSubmit) {
                return;
            }

            if (btnSubmit.disabled) {
                return;
            }
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = `
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>   <span class="ml-5">Enviando...</span>`;
            tooglePreloader(true);

            const ajaxUrl = ajax_object.ajax_url;
            const data = new FormData(form);
            if ((e.submitter.name === 'delete-post')) {
                data.append('delete-post', true);
            } else {
                data.delete('delete-post');
            }
            fetch(ajaxUrl, {
                method: 'POST',
                body: data
            })
                .then((response) => response.json())
                .then((response) => {
                    const status = response.success ? 'success' : 'danger';
                    let showAlertContainer = modalAlertPlaceholder;
                    const msg = response.data.msg;
                    if (status === 'success') {
                        showAlertContainer = tableAlertPlaceholder;
                        resetLancamentoFinanceiroInputs();
                        lancamentosFinanceiros = response.data.lancamentos_financeiros;
                        filterLancamentos();
                        const modalLancamentoFinanceiro = document.getElementById('modal-editar-lancamento-financeiro-obra');
                        const modalInstance = bootstrap.Modal.getOrCreateInstance(modalLancamentoFinanceiro);
                        modalInstance.hide();
                    }
                    showAlert(showAlertContainer, msg, status);
                })
                .catch((error) => {
                    showAlert(modalAlertPlaceholder, error, 'danger');
                })
                .finally(() => {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = btnSubmit.dataset.originalText;
                    form.classList.remove('was-validated');
                    tooglePreloader(false);
                });

        });
    }

    // Diário da Obra

    function diarioDeObraInit() {
        if (!diariosDaObra) {
            return;
        }
        renderDiariosDeObraList(diariosDaObra);
        diarioDaObraFiltersEvts();
    }

    function renderDiariosDeObraList(list) {
        const container = document.querySelector('#list-diario-da-obra');
        if (typeof container === undefined || !container) {
            return;
        }
        container.innerHTML = '';
        const itemsArray = [];
        if (list.length > 0) {
            list.forEach(item => {
                const container = document.createElement('div');
                container.classList.add('projeto-item');
                container.classList.add('collapsed');
                container.classList.add('gap-0');

                const projetoItemContent = document.createElement('div');
                projetoItemContent.classList.add('projeto-item-content');
                projetoItemContent.innerHTML = `
                    <div class="projeto-item-info">
                        <h2 class="projeto-item-title">${item.title}</h2>
                        <div class="projeto-item-description">${item.description}</div>
                        <div class="badge text-bg-disabled mt-3 px-4 fw-bold">${item.week}</div>
                    </div>
                    <img class="projeto-item-img ms-auto" src="${item.thumbnail_url}" alt="${item.title}">`;
                container.append(projetoItemContent);

                const actions = document.createElement('div');
                actions.classList.add('projeto-item-actions');

                const btnToggle = document.createElement('button');
                btnToggle.type = 'button';
                btnToggle.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="8" viewBox="0 0 14 8" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M7 5.96408L12.8335 -5.60969e-07L14 1.19265L7.58326 7.75305C7.42856 7.91117 7.21876 8 7 8C6.78124 8 6.57144 7.91117 6.41673 7.75305L-2.97559e-07 1.19265L1.16653 -5.09906e-08L7 5.96408Z" fill="#7C8FAC"/></svg>`;
                btnToggle.classList.add('toggle-diario-content');
                btnToggle.classList.add('btn-no-style');
                btnToggle.dataset.postId = item.id;
                btnToggle.addEventListener('click', toggleDiarioContent);
                actions.append(btnToggle);

                const btnEdit = document.createElement('button');
                btnEdit.type = 'button';
                btnEdit.classList.add('edit-diario');
                btnEdit.dataset.id = item.id;
                btnEdit.dataset.bsToggle = 'modal';
                btnEdit.dataset.bsTarget = '#modal-editar-diario-da-obra';
                btnEdit.dataset.postId = item.id;
                btnEdit.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#5A6A85" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-pencil"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" /><path d="M13.5 6.5l4 4" /></svg>`;
                btnEdit.classList.add('btn-no-style');
                actions.append(btnEdit);

                container.append(actions);

                const filesContainer = document.createElement('ul');
                filesContainer.classList.add('files-container');
                filesContainer.classList.add('masonry-files-list');
                container.append(filesContainer);

                itemsArray.push(container);
            });
        } else {
            const itemDiv = document.createElement('div');
            itemDiv.classList.add('not-found-container');
            itemDiv.innerHTML = `<h2 class="not-found-message">Não há nada por aqui.</h2>`;
            itemsArray.push(itemDiv);
        }
        container.append(...itemsArray);
    }

    async function toggleDiarioContent(e) {
        e.preventDefault();
        const postId = e.target.dataset.postId;
        const container = e.target.closest('.projeto-item');
        container.classList.toggle('collapsed');
        const loadPhotos = !container.classList.contains('collapsed');
        const filesContainer = container.querySelector('.files-container');
        filesContainer.innerHTML = '';
        if (loadPhotos) {
            let diario = diariosDaObra.filter(diario => diario.id === parseInt(postId));
            const photosUrl = diario[0].photos_url;
            const videosUrl = diario[0].videos_url;
            const photosId = diario[0].photos_id;
            const videosId = diario[0].videos_id;
            const imgList = [];

            if (photosUrl) {
                for (const url of photosUrl) {
                    const listItem = document.createElement('li');
                    const img = await createCachedImage(url);
                    listItem.classList.add('list-item');
                    img.classList.remove('loading');
                    img.classList.add('file-item');
                    img.addEventListener('click', imageModal);
                    listItem.append(img);
                    imgList.push(listItem);
                }
                filesContainer.append(...imgList);
            }
            const videosList = [];
            if (videosUrl) {
                for (const url of videosUrl) {
                    const listItem = document.createElement('li');
                    listItem.classList.add('list-item');
                    listItem.classList.add('list-item-video');

                    const thumbnailUrl = await getAndCacheVideoFrame(url);
                    const img = document.createElement('img');
                    img.src = thumbnailUrl;
                    img.classList.remove('loading');
                    img.classList.add('file-item');
                    img.dataset.videoUrl = url;
                    img.addEventListener('click', videoModal);
                    listItem.append(img);

                    const divIcon = document.createElement('div');
                    divIcon.classList.add('list-item-icon');
                    divIcon.innerHTML = `<svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24.5884 48.7423C37.9292 48.7423 48.7433 37.9282 48.7433 24.5875C48.7433 11.2467 37.9292 0.432617 24.5884 0.432617C11.2477 0.432617 0.433594 11.2467 0.433594 24.5875C0.433594 37.9282 11.2477 48.7423 24.5884 48.7423Z" stroke="white" stroke-width="0.865248" stroke-linejoin="round"/><path d="M19.7598 24.5889V16.2217L27.0062 20.4053L34.2527 24.5889L27.0062 28.7725L19.7598 32.9562V24.5889Z" stroke="white" stroke-width="0.865248" stroke-linejoin="round"/></svg>`;
                    listItem.append(divIcon);
                    videosList.push(listItem);
                }
                filesContainer.append(...videosList);
            }
        }
    }

    function imageModal(e) {
        e.preventDefault();
        const image = e.target;
        const modalDiv = document.createElement('div');
        modalDiv.classList.add('modal');
        modalDiv.classList.add('fade');
        modalDiv.id = 'modal-image';
        modalDiv.tabIndex = '-1';

        modalDiv.innerHTML = `
        <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-xl-down">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="${image.src}" class="expanded-image" />
                </div>
            </div>
        </div>`;

        document.body.appendChild(modalDiv);

        // 4. Inicializar e mostrar usando a API do Bootstrap
        const modalBootstrap = new bootstrap.Modal(modalDiv);
        modalBootstrap.show();

        // 5. Remover o elemento do DOM após fechar para não poluir a página
        modalDiv.addEventListener('hidden.bs.modal', () => {
            modalDiv.remove();
        });
    }

    function videoModal(e) {
        e.preventDefault();
        const image = e.target;
        const videoUrl = image.dataset.videoUrl;
        const modalDiv = document.createElement('div');
        modalDiv.classList.add('modal');
        modalDiv.classList.add('fade');
        modalDiv.id = 'modal-video';
        modalDiv.tabIndex = '-1';

        const modalDialog = document.createElement('div');
        modalDialog.classList.add('modal-dialog');
        modalDialog.classList.add('modal-dialog-centered');
        modalDialog.classList.add('modal-xl');
        modalDialog.classList.add('modal-fullscreen-xl-down');
        modalDialog.classList.add('modal-video');

        const modalContent = document.createElement('div');
        modalContent.classList.add('modal-content');

        const modalHeader = document.createElement('div');
        modalHeader.classList.add('modal-header');
        modalHeader.innerHTML = `<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>`;

        const modalBody = document.createElement('div');
        modalBody.classList.add('modal-body');

        const video = document.createElement('video');
        video.controls = true;
        video.preload = 'metadata'; // Carrega apenas o necessário para o frame
        video.src = videoUrl;
        video.crossOrigin = 'anonymous'; // Evita erros de CORS ao desenhar no canvas

        modalBody.append(video);
        modalContent.append(modalHeader);
        modalContent.append(modalBody);
        modalDialog.append(modalContent);
        modalDiv.append(modalDialog);
        document.body.append(modalDiv);

        // 4. Inicializar e mostrar usando a API do Bootstrap
        const modalBootstrap = new bootstrap.Modal(modalDiv);
        modalBootstrap.show();

        // 5. Remover o elemento do DOM após fechar para não poluir a página
        modalDiv.addEventListener('hidden.bs.modal', () => {
            modalDiv.remove();
        });
    }

    function diarioDaObraFiltersEvts() {
        const estagiosLancamentosSelect = document.querySelector('#estagios-diario-da-obra');
        estagiosLancamentosSelect.addEventListener('input', filterDiarios);
    }

    function filterDiarios(e) {
        const selectedEstagio = document.querySelector('#estagios-diario-da-obra').value;
        let results = diariosDaObra;

        // Filtra estágio
        if (selectedEstagio) {
            results = results.filter(item => {
                if (item.estagio === selectedEstagio) {
                    return item;
                }
            });
        }
        renderDiariosDeObraList(results);
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
        calcProjecaoLucro();
        renderProjecaoResultados();
        renderPrevisaoLucro();
        projetoProjecaoForm();
        loadedProjecaoForm();
        removeProjeto();
        alertRemovedProjeto();
        checkPasswordsEvt();
        selectObraContentRedirect();
        financeiroObraTableInit();
        modalLancamentoFinanceiro();
        editLancamentoFinanceiroObraForm();
        diarioDeObraInit();
        cleanExpiredCache();
    });
})();