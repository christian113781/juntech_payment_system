<div class="w-full min-h-screen space-y-6 px-2 sm:px-4 lg:px-6" x-data="voucherDesignerPage()" x-cloak>
    <script>
        window.__vdLibErrors = [];
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"
            onerror="window.__vdLibErrors.push('PDF reader (pdf.js)')"></script>
    <script>
        if (window.pdfjsLib) {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        }
    </script>
    <script>
        (function () {
            function loadScript(url, timeoutMs) {
                return new Promise(function (resolve, reject) {
                    var s = document.createElement('script');
                    var done = false;
                    var timer = setTimeout(function () {
                        if (!done) { done = true; s.remove(); reject(new Error('timeout: ' + url)); }
                    }, timeoutMs || 6000);
                    s.onload = function () { if (!done) { done = true; clearTimeout(timer); resolve(); } };
                    s.onerror = function () { if (!done) { done = true; clearTimeout(timer); s.remove(); reject(new Error('404/blocked: ' + url)); } };
                    s.src = url;
                    document.head.appendChild(s);
                });
            }
            var candidates = [
                { url: 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js', variant: 'davidshimjs' },
                { url: 'https://cdnjs.cloudflare.com/ajax/libs/qrcode-generator/1.4.4/qrcode.min.js', variant: 'generator' },
            ];
            window.__vdQrReadyPromise = (async function () {
                for (var i = 0; i < candidates.length; i++) {
                    var c = candidates[i];
                    try {
                        await loadScript(c.url);
                        if (c.variant === 'davidshimjs' && window.QRCode && window.QRCode.CorrectLevel) {
                            window.__qrVariant = 'davidshimjs';
                            return true;
                        }
                        if (c.variant === 'generator' && typeof window.qrcode === 'function') {
                            window.__qrVariant = 'generator';
                            return true;
                        }
                    } catch (e) { /* try next candidate */ }
                }
                window.__qrVariant = null;
                return false;
            })();
        })();
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"
            onerror="window.__vdLibErrors.push('PDF writer (jsPDF)')"></script>
    <a href="{{ route('omada.index') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 hover:text-brand-600 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Partners
    </a>

    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Voucher Designer</h1>
        <p class="text-sm text-gray-500 mt-0.5 dark:text-gray-400">
            Import an Omada Cloud voucher export, then re-brand every code into a print-ready card.
        </p>
    </div>

    <div x-show="libIssue" class="flex items-start gap-3 bg-red-50 border border-red-100 rounded-2xl p-4 dark:bg-red-950/20 dark:border-red-900/50" style="display:none">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div class="min-w-0">
            <p class="text-sm font-semibold text-red-700 dark:text-red-300">Couldn't load: <span x-text="libIssue"></span></p>
            <p class="text-xs text-red-500 mt-0.5 dark:text-red-300/80">This usually means a required library is missing. Reload the page and try again.</p>
        </div>
    </div>

    <div class="flex items-center bg-white rounded-2xl shadow-card p-4 sm:p-5 dark:bg-gray-800 dark:border dark:border-gray-700">
        <div class="flex items-center">
            <div class="step-dot" :class="step === 1 ? 'active' : (step > 1 ? 'done' : 'upcoming')">
                <svg x-show="step > 1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span x-show="step === 1">1</span>
            </div>
            <span class="ms-2 text-xs sm:text-sm font-semibold text-gray-700 hidden sm:inline dark:text-gray-200">Import PDF</span>
        </div>
        <div class="step-line" :class="{ done: step > 1 }"></div>
        <div class="flex items-center">
            <div class="step-dot" :class="step === 2 ? 'active' : (step > 2 ? 'done' : 'upcoming')">
                <svg x-show="step > 2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span x-show="step <= 2">2</span>
            </div>
            <span class="ms-2 text-xs sm:text-sm font-semibold text-gray-700 hidden sm:inline dark:text-gray-200">Review &amp; Design</span>
        </div>
        <div class="step-line" :class="{ done: step > 2 }"></div>
        <div class="flex items-center">
            <div class="step-dot" :class="step === 3 ? 'active' : 'upcoming'">3</div>
            <span class="ms-2 text-xs sm:text-sm font-semibold text-gray-700 hidden sm:inline dark:text-gray-200">Download</span>
        </div>
    </div>

    <section x-show="step === 1" class="bg-white rounded-2xl shadow-card p-6 sm:p-8 dark:bg-gray-800 dark:border dark:border-gray-700">
        <label for="pdf-input" class="upload-zone block" :class="{ dragover: isDragging }" @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @drop.prevent="onDrop($event)">
            <input id="pdf-input" type="file" accept="application/pdf,.pdf" class="sr-only" @change="onFileInput($event)">
            <div class="flex flex-col items-center gap-3 py-6">
                <div class="w-14 h-14 rounded-2xl bg-brand-50 flex items-center justify-center" aria-hidden="true">
                    <svg class="w-7 h-7 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v9m0-9l-3 3m3-3l3 3"/></svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                        <span x-show="!isParsing">Drop your Omada voucher PDF here, or <span class="text-brand-600">browse</span></span>
                        <span x-show="isParsing">Reading codes from your PDF…</span>
                    </p>
                    <p class="text-xs text-gray-400 mt-1">Works with Omada Cloud voucher exports.</p>
                </div>
            </div>
        </label>

        <p class="text-xs text-gray-400 mt-4 flex items-start gap-1.5 dark:text-gray-500">
            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Everything runs in your browser — the PDF never leaves this device.
        </p>

        <div class="mt-6 pt-5 border-t border-gray-100 dark:border-gray-700">
            <button type="button" @click="step = 2" class="text-sm font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400">
                Skip upload — I'll paste codes manually →
            </button>
        </div>
    </section>

    <section x-show="step === 2" class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-card p-5 sm:p-6 dark:bg-gray-800 dark:border dark:border-gray-700">
                <div class="flex items-center justify-between mb-1">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white">Voucher codes</h2>
                    <span class="badge bg-brand-50 text-brand-600 dark:bg-brand-900/30 dark:text-brand-300" x-text="codeCount + ' code' + (codeCount === 1 ? '' : 's')"></span>
                </div>
                <p class="text-xs text-gray-400 mb-3 dark:text-gray-500" x-show="fileName">Imported from <strong class="text-gray-600 dark:text-gray-300" x-text="fileName"></strong></p>
                <p class="text-xs mb-3" :class="codeCount ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600 dark:text-amber-400'" x-show="parseNote" x-text="parseNote"></p>
                <textarea rows="5" class="field  text-xs resize-y dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700" x-model="rawCodesText" @input.debounce.400ms="refreshPreviewQr(); if (this.codeCount) this.step = 2;" placeholder="One code per line, e.g.&#10;87242140&#10;17611984&#10;38947117"></textarea>
                <div class="flex items-center justify-between mt-3">
                    <button type="button" @click="startOver()" class="text-xs font-semibold text-gray-500 hover:text-gray-700 dark:text-gray-400">← Import a different PDF</button>
                    <label for="pdf-input-2" class="text-xs font-semibold text-brand-600 hover:text-brand-700 dark:text-brand-400 cursor-pointer">
                        Add another PDF
                        <input id="pdf-input-2" type="file" accept="application/pdf,.pdf" class="sr-only" @change="onFileInput($event)">
                    </label>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-card p-5 sm:p-6 space-y-4 dark:bg-gray-800 dark:border dark:border-gray-700">
                <h2 class="text-sm font-bold text-gray-900 dark:text-white">Card details</h2>
                <p class="text-xs text-gray-400 -mt-2 dark:text-gray-500">These stay fixed on every voucher.</p>

                <div>
                    <label for="vd-brand" class="block text-xs font-semibold text-gray-600 mb-1.5 dark:text-gray-300">Store name</label>
                    <input id="vd-brand" type="text" class="field dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700" x-model.trim="brand" placeholder="JUN WIFI">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="vd-label" class="block text-xs font-semibold text-gray-600 mb-1.5 dark:text-gray-300">Code label</label>
                        <input id="vd-label" type="text" class="field dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700" x-model.trim="codeLabel" placeholder="VOUCHER CODE">
                    </div>
                    <div>
                        <label for="vd-validity" class="block text-xs font-semibold text-gray-600 mb-1.5 dark:text-gray-300">Validity</label>
                        <input id="vd-validity" type="text" class="field dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700" x-model.trim="validity" placeholder="24 HOURS">
                    </div>
                </div>
                <div>
                    <label for="vd-tagline" class="block text-xs font-semibold text-gray-600 mb-1.5 dark:text-gray-300">Tagline <span class="text-xs font-normal text-gray-400 dark:text-gray-500">(optional)</span></label>
                    <input id="vd-tagline" type="text" class="field dark:bg-gray-900 dark:text-gray-100 dark:border-gray-700" x-model.trim="tagline" placeholder="WI-FI VOUCHER">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <button type="button" @click="downloadPdf()" :disabled="isGenerating || !codeCount || step !== 2" class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-brand-600 hover:bg-brand-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-sm font-semibold rounded-xl transition-colors shadow-sm min-h-[48px]">
                    <svg x-show="!isGenerating" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H8a2 2 0 01-2-2V5a2 2 0 012-2h6l6 6v11a2 2 0 01-2 2z"/></svg>
                    <svg x-show="isGenerating" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4V2m0 20v-2m8-8h2M2 12h2m13.66-6.66l1.42-1.42M4.92 19.08l1.42-1.42M19.08 19.08l-1.42-1.42M4.92 4.92L6.34 6.34"/></svg>
                    <span x-text="isGenerating ? 'Generating… ' + genProgress + '%' : 'Download'"></span>
                </button>

                <button type="button" @click="openPdfPreview()" :disabled="isGenerating || !codeCount || step !== 2" class="w-full flex items-center justify-center gap-2 px-5 py-3 border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed text-sm font-semibold rounded-xl transition-colors min-h-[48px] dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17h10v4H7v-4zm0-9h10m-10 0V3h10v5M7 13h10v4H7v-4z"/></svg>
                    Print
                </button>
            </div>
        </div>

        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-card p-5 sm:p-6 h-full dark:bg-gray-800 dark:border dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-bold text-gray-900 dark:text-white">Preview</h2>
                    <span class="text-xs text-gray-400" x-show="codeCount">
                        1 design · applies to all <span x-text="codeCount"></span> code<span x-show="codeCount !== 1">s</span>
                    </span>
                </div>

                <div x-show="!codeCount" class="flex flex-col items-center justify-center text-center py-16 text-gray-400">
                    <svg class="w-10 h-10 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"/></svg>
                    <p class="text-sm">Add codes on the left to see the redesigned card here.</p>
                </div>

                <div x-show="codeCount" class="flex flex-col items-center">
                    <div class="relative flex rounded-xl border-2 overflow-hidden h-[130px] w-full max-w-sm" :style="'border-color:' + designColor + '; background:' + backgroundColor">
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="font-black tracking-[0.35rem] text-[26px] uppercase opacity-10 select-none"
                                  :style="'color:' + designColor" x-text="(brand || 'JUN WIFI').slice(0, 8)"></span>
                        </div>
                        <div class="flex-1 min-w-0 px-3.5 py-2.5 flex flex-col justify-between relative z-10">
                            <div>
                                <p class="text-base font-extrabold tracking-tight leading-none truncate" :style="'color:' + designColor" x-text="brand || 'JUN WIFI'"></p>
                                <p class="text-[9px] font-semibold text-gray-400 tracking-widest uppercase mt-1" x-text="tagline || 'WI-FI VOUCHER'"></p>
                            </div>
                            <div>
                                <p class="text-[9px] font-semibold text-gray-400 tracking-wide uppercase" x-text="codeLabel || 'VOUCHER CODE'"></p>
                                <p class=" font-bold text-gray-900 leading-tight truncate" style="font-size:1.2rem" x-text="sampleCode"></p>
                            </div>
                            <span class="inline-flex w-fit max-w-full items-center px-2.5 py-1 rounded-full text-white text-[10px] font-bold tracking-wide whitespace-nowrap" :style="'background:' + designColor">
                                <span x-text="('VALID ' + (validity || '24 HOURS')).toUpperCase()"></span>
                            </span>
                        </div>
                        <div class="flex-shrink-0 flex items-center justify-center px-2.5 border-s border-dashed border-gray-300 bg-white/30 relative z-10">
                            <img :src="sampleQr" width="72" height="72" alt="" class="w-[72px] h-[72px]" x-show="sampleQr">
                            <div class="w-[72px] h-[72px] bg-gray-100 rounded animate-pulse" x-show="!sampleQr"></div>
                        </div>
                    </div>

                    <div class="w-full max-w-sm space-y-3 mt-4">
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-3 dark:bg-gray-900 dark:border-gray-700">
                            <p class="text-[11px] font-semibold text-gray-600 mb-2 dark:text-gray-300">Design color</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="swatch in palette" :key="swatch.value">
                                    <button type="button" @click="designColor = swatch.value" class="flex items-center gap-2 px-2.5 py-2 rounded-lg border transition-colors" :class="designColor === swatch.value ? 'border-gray-900 bg-white dark:border-gray-300 dark:bg-gray-800' : 'border-gray-200 bg-white hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600'">
                                        <span class="w-4 h-4 rounded-full border border-gray-200" :style="'background:' + swatch.value"></span>
                                        <span class="text-[11px] font-semibold text-gray-600 dark:text-gray-200" x-text="swatch.name"></span>
                                    </button>
                                </template>
                            </div>
                            <div class="mt-3 flex items-center gap-3">
                                <label class="text-[11px] font-semibold text-gray-600 dark:text-gray-300">Accent</label>
                                <input type="color" x-model="designColor" class="h-9 w-12 rounded-lg border border-gray-200 bg-white p-1 cursor-pointer dark:bg-gray-900 dark:border-gray-700" aria-label="Choose voucher accent color">
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-3 dark:bg-gray-900 dark:border-gray-700">
                            <p class="text-[11px] font-semibold text-gray-600 mb-2 dark:text-gray-300">Background color</p>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="bgSwatch in backgroundPalette" :key="bgSwatch.value">
                                    <button type="button" @click="backgroundColor = bgSwatch.value" class="flex items-center gap-2 px-2.5 py-2 rounded-lg border transition-colors" :class="backgroundColor === bgSwatch.value ? 'border-gray-900 bg-white dark:border-gray-300 dark:bg-gray-800' : 'border-gray-200 bg-white hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600'">
                                        <span class="w-4 h-4 rounded-full border border-gray-200" :style="'background:' + bgSwatch.value"></span>
                                        <span class="text-[11px] font-semibold text-gray-600 dark:text-gray-200" x-text="bgSwatch.name"></span>
                                    </button>
                                </template>
                            </div>
                            <div class="mt-3 flex items-center gap-3">
                                <label class="text-[11px] font-semibold text-gray-600 dark:text-gray-300">Background</label>
                                <input type="color" x-model="backgroundColor" class="h-9 w-12 rounded-lg border border-gray-200 bg-white p-1 cursor-pointer dark:bg-gray-900 dark:border-gray-700" aria-label="Choose voucher background color">
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-400 mt-3 text-center dark:text-gray-500">
                        QR value = the voucher code shown above (<span class="font-semibold text-gray-500" x-text="sampleCode"></span>).
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section x-show="step === 3" class="bg-white rounded-2xl shadow-card p-8 sm:p-12 flex flex-col items-center text-center dark:bg-gray-800 dark:border dark:border-gray-700">
        <div class="w-16 h-16 rounded-2xl bg-emerald-50 flex items-center justify-center mb-4" aria-hidden="true">
            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h2 class="text-lg font-bold text-gray-900 dark:text-white">Your voucher sheet is ready</h2>
        <p class="text-sm text-gray-500 mt-1 max-w-sm dark:text-gray-400">
            <span x-text="codeCount"></span> redesigned <span x-text="brand"></span> vouchers were saved to your downloads folder.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-3 mt-6">
            <button type="button" @click="step = 2" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-gray-600 border border-gray-200 hover:bg-gray-50 min-h-[44px] dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-900">
                Back to editor
            </button>
            <button type="button" @click="startOver()" class="px-5 py-2.5 rounded-xl text-sm font-semibold bg-brand-600 text-white hover:bg-brand-700 min-h-[44px]">
                Design another batch
            </button>
        </div>
    </section>

    <script>
        function qrToDataUrl(text, sizePx) {
            if (window.__qrVariant === 'davidshimjs' && window.QRCode) {
                return new Promise((resolve, reject) => {
                    try {
                        const holder = document.createElement('div');
                        holder.style.cssText = 'position:absolute;left:-9999px;top:-9999px;';
                        document.body.appendChild(holder);
                        new window.QRCode(holder, { text, width: sizePx, height: sizePx, correctLevel: window.QRCode.CorrectLevel.M });
                        requestAnimationFrame(() => {
                            const canvas = holder.querySelector('canvas');
                            const dataUrl = canvas ? canvas.toDataURL('image/png') : null;
                            document.body.removeChild(holder);
                            if (dataUrl) resolve(dataUrl);
                            else reject(new Error('QR render failed'));
                        });
                    } catch (e) { reject(e); }
                });
            }

            if (window.__qrVariant === 'generator' && typeof window.qrcode === 'function') {
                const qr = window.qrcode(0, 'M');
                qr.addData(text);
                qr.make();
                return Promise.resolve(qr.createDataURL(8, 0));
            }

            return Promise.resolve(null);
        }

        function hexToRgb(hex) {
            const raw = (hex || '#0f172a').replace('#', '');
            const full = raw.length === 3 ? raw.split('').map(ch => ch + ch).join('') : raw;
            const value = parseInt(full, 16) || 0x0f172a;
            return {
                r: (value >> 16) & 255,
                g: (value >> 8) & 255,
                b: value & 255,
            };
        }

        function voucherDesignerPage() {
            return {
                ...window.appShell ? window.appShell() : {},
                async init() {
                    if (window.__vdQrReadyPromise) {
                        try { await window.__vdQrReadyPromise; } catch (e) {}
                    }
                    if (window.pdfjsLib) {
                        window.pdfjsLib.GlobalWorkerOptions = window.pdfjsLib.GlobalWorkerOptions || {};
                        if (!window.pdfjsLib.GlobalWorkerOptions.workerSrc) {
                            window.pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                        }
                    }
                    this.checkLibs();
                },
                step: 1,
                fileName: '',
                isDragging: false,
                isParsing: false,
                parseNote: '',
                rawCodesText: '',
                get codesList() {
                    const seen = new Set();
                    this.rawCodesText.split('\n').forEach(line => {
                        const c = line.trim();
                        if (/^\d{4,12}$/.test(c)) seen.add(c);
                    });
                    return [...seen];
                },
                get codeCount() { return this.codesList.length; },
                get sampleCode() { return this.codesList[0] || ''; },
                brand: 'JUN WIFI',
                codeLabel: 'VOUCHER CODE',
                validity: '24 HOURS',
                tagline: 'WI-FI VOUCHER',
                designColor: '#0f172a',
                backgroundColor: '#ffffff',
                palette: [
                    { name: 'Slate', value: '#0f172a' },
                    { name: 'Blue', value: '#2563eb' },
                    { name: 'Green', value: '#059669' },
                    { name: 'Orange', value: '#ea580c' },
                    { name: 'Red', value: '#dc2626' },
                    { name: 'Purple', value: '#7c3aed' },
                ],
                backgroundPalette: [
                    { name: 'White', value: '#ffffff' },
                    { name: 'Gray', value: '#f3f4f6' },
                    { name: 'Blue', value: '#e0f2fe' },
                    { name: 'Green', value: '#dcfce7' },
                    { name: 'Yellow', value: '#fef3c7' },
                    { name: 'Pink', value: '#fce7f3' },
                ],
                sampleQr: null,
                async refreshPreviewQr() {
                    if (window.__vdQrReadyPromise) {
                        try { await window.__vdQrReadyPromise; } catch (e) {}
                    }
                    if (!window.__qrVariant || !this.sampleCode) { this.sampleQr = null; return; }
                    try {
                        const dataUrl = await qrToDataUrl(this.sampleCode, 200);
                        if (this.sampleCode === this.codesList[0]) this.sampleQr = dataUrl;
                    } catch (e) {
                        console.error('QR preview failed:', e);
                    }
                },
                libIssue: '',
                checkLibs() {
                    const missing = [];
                    if (typeof window.pdfjsLib === 'undefined') missing.push('PDF reader (pdf.js)');
                    if (typeof window.jspdf === 'undefined' || typeof window.jspdf.jsPDF === 'undefined') missing.push('PDF writer (jsPDF)');
                    this.libIssue = [...new Set(missing)].join(', ');
                },
                onDrop(e) {
                    this.isDragging = false;
                    const file = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0];
                    if (file) this.parseFile(file);
                },
                onFileInput(e) {
                    const file = e.target.files && e.target.files[0];
                    if (file) this.parseFile(file);
                    e.target.value = '';
                },
                async parseFile(file) {
                    const looksLikePdf = file.type === 'application/pdf' || /\.pdf$/i.test(file.name);
                    if (!looksLikePdf) { this.showToast('Please upload a PDF file.'); return; }

                    this.fileName = file.name;
                    this.isParsing = true;
                    this.parseNote = '';
                    try {
                        const pdfLib = (window.pdfjsLib || globalThis.pdfjsLib);
                        if (!pdfLib || typeof pdfLib.getDocument !== 'function') {
                            throw new Error('PDF reader is unavailable.');
                        }

                        pdfLib.GlobalWorkerOptions = pdfLib.GlobalWorkerOptions || {};
                        if (!pdfLib.GlobalWorkerOptions.workerSrc) {
                            pdfLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
                        }

                        const buf = await file.arrayBuffer();
                        const pdf = await pdfLib.getDocument({ data: buf }).promise;
                        const lines = [];
                        for (let p = 1; p <= pdf.numPages; p++) {
                            const page = await pdf.getPage(p);
                            const content = await page.getTextContent();
                            content.items.forEach(item => {
                                if (item.str && item.str.trim()) lines.push(item.str.trim());
                            });
                        }
                        const found = [...new Set(lines.filter(line => /^\d{6,10}$/.test(line)))];
                        const merged = [...new Set([...this.codesList, ...found])];
                        this.rawCodesText = merged.join('\n');
                        this.parseNote = found.length
                            ? `${found.length} code${found.length === 1 ? '' : 's'} detected automatically. Review below.`
                            : `Couldn't auto-detect codes in this file. Paste them manually below.`;
                        this.step = 2;
                        await this.$nextTick();
                        this.refreshPreviewQr();
                        this.showToast(found.length ? `${found.length} codes imported from ${file.name}` : 'PDF imported — no codes auto-detected');
                    } catch (err) {
                        console.error('PDF read failed:', err);
                        this.parseNote = err?.message
                            ? `Could not read that PDF: ${err.message}. Paste codes manually below.`
                            : `Couldn't read that PDF. Paste codes manually below.`;
                        this.step = 2;
                        this.showToast(err?.message || 'Could not read that PDF.');
                    } finally {
                        this.isParsing = false;
                    }
                },
                startOver() {
                    this.step = 1;
                    this.fileName = '';
                    this.rawCodesText = '';
                    this.parseNote = '';
                    this.sampleQr = null;
                },
                async openPdfPreview() {
                    if (!this.codeCount) { this.showToast('Add at least one voucher code first.'); return; }
                    if (this.isGenerating) return;

                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF({ unit: 'mm', format: 'a4' });
                    const pageW = 210, pageH = 297, marginX = 4, marginTop = 10, marginBottom = 10, gap = 1.5, cols = 4, rows = 10;
                    const cardW = (pageW - marginX * 2 - gap * (cols - 1)) / cols;
                    const cardH = (pageH - marginTop - marginBottom - gap * (rows - 1)) / rows;
                    const perPage = cols * rows;
                    const codes = this.codesList;
                    let placed = 0;

                    for (let i = 0; i < codes.length; i++) {
                        const code = codes[i];
                        let qr = null;
                        try {
                            qr = await qrToDataUrl(code, 260);
                        } catch (e) { console.warn('QR preview failed:', code, e); }
                        const idx = placed % perPage;
                        if (placed > 0 && idx === 0) doc.addPage();
                        const col = idx % cols, row = Math.floor(idx / cols);
                        const x = margin + col * (cardW + gap);
                        const y = margin + row * (cardH + gap);
                        this.drawCard(doc, x, y, cardW, cardH, code, qr);
                        placed++;
                    }

                    const pdfBlob = doc.output('blob');
                    const url = URL.createObjectURL(pdfBlob);
                    const newTab = window.open(url, '_blank');
                    if (!newTab) {
                        this.showToast('Popup blocked — allow popups to open the PDF preview.');
                        URL.revokeObjectURL(url);
                        return;
                    }
                    this.showToast('PDF preview opened in a new tab.');
                },
                isGenerating: false,
                genProgress: 0,
                async downloadPdf() {
                    if (!this.codeCount) { this.showToast('Add at least one voucher code first.'); return; }
                    if (window.__vdQrReadyPromise) {
                        try { await window.__vdQrReadyPromise; } catch (e) {}
                    }
                    this.checkLibs();
                    if (this.libIssue) {
                        this.showToast(`Can't export — failed to load: ${this.libIssue}`);
                        return;
                    }

                    this.isGenerating = true;
                    this.genProgress = 0;
                    await this.$nextTick();

                    const skipped = [];
                    try {
                        const { jsPDF } = window.jspdf;
                        const doc = new jsPDF({ unit: 'mm', format: 'a4' });
                        const pageW = 210, pageH = 297, margin = 4, gap = 1.5, cols = 5, rows = 11;
                        const cardW = (pageW - margin * 2 - gap * (cols - 1)) / cols;
                        const cardH = (pageH - margin * 2 - gap * (rows - 1)) / rows;
                        const perPage = cols * rows;
                        const codes = this.codesList;
                        let placed = 0;

                        for (let i = 0; i < codes.length; i++) {
                            const code = codes[i];
                            try {
                                let qr = null;
                                try {
                                    qr = await qrToDataUrl(code, 260);
                                } catch (cardErr) {
                                    console.warn(`QR generation unavailable for code "${code}":`, cardErr);
                                }
                                const idx = placed % perPage;
                                if (placed > 0 && idx === 0) doc.addPage();
                                const col = idx % cols, row = Math.floor(idx / cols);
                                const x = margin + col * (cardW + gap);
                                const y = margin + row * (cardH + gap);
                                this.drawCard(doc, x, y, cardW, cardH, code, qr);
                                placed++;
                            } catch (cardErr) {
                                console.error(`Skipping code "${code}":`, cardErr);
                                skipped.push(code);
                            }
                            this.genProgress = Math.round(((i + 1) / codes.length) * 100);
                        }

                        if (placed === 0) {
                            throw new Error('No cards could be generated — check the codes list on the left.');
                        }

                        const safeBrand = (this.brand || 'Voucher').replace(/[^a-z0-9]+/gi, '-').replace(/^-+|-+$/g, '') || 'Voucher';
                        doc.save(`${safeBrand}-Vouchers-${new Date().toISOString().slice(0, 10)}.pdf`);
                        this.step = 3;
                        this.showToast(skipped.length ? `Downloaded ${placed} vouchers — skipped ${skipped.length} bad code(s).` : 'Voucher PDF downloaded!');
                    } catch (err) {
                        console.error('Voucher export failed:', err);
                        this.showToast(`Export failed: ${err && err.message ? err.message : 'unknown error.'}`);
                    } finally {
                        this.isGenerating = false;
                    }
                },
                drawCard(doc, x, y, w, h, code, qrDataUrl) {
                    const brand = this.brand || 'JUN WIFI';
                    const label = this.codeLabel || 'VOUCHER CODE';
                    const validity = (this.validity || '24 HOURS').toUpperCase();
                    const tagline = (this.tagline || 'WI-FI VOUCHER').toUpperCase();
                    const accent = this.designColor || '#0f172a';
                    const accentRgb = hexToRgb(accent);
                    const bg = this.backgroundColor || '#ffffff';
                    const bgRgb = hexToRgb(bg);

                    doc.setFillColor(bgRgb.r, bgRgb.g, bgRgb.b);
                    doc.roundedRect(x, y, w, h, 1.8, 1.8, 'F');
                    doc.setDrawColor(accentRgb.r, accentRgb.g, accentRgb.b);
                    doc.setLineWidth(0.35);
                    doc.roundedRect(x, y, w, h, 1.8, 1.8, 'S');

                    const watermark = (brand || 'JUN WIFI').substring(0, 8).toUpperCase();
                    const centerX = x + w / 2;
                    const centerY = y + h / 2;
                    doc.saveGraphicsState();
                    doc.setGState(new window.jspdf.GState({ opacity: 0.08 }));
                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(20);
                    doc.setTextColor(accentRgb.r, accentRgb.g, accentRgb.b);
                    doc.text(watermark, centerX, centerY, { angle: -35, align: 'center' });
                    doc.restoreGraphicsState();

                    const cX = x + 2.1;
                    const contentW = w - 13;

                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(7.5);
                    doc.setTextColor(accentRgb.r, accentRgb.g, accentRgb.b);
                    doc.text(brand, cX, y + 4.8);
                    doc.setFont('helvetica', 'normal');
                    doc.setFontSize(4.5);
                    doc.setTextColor(120, 120, 120);
                    doc.text(tagline, cX, y + 7.3);
                    doc.setDrawColor(220, 220, 220);
                    doc.setLineWidth(0.14);
                    doc.line(cX, y + 8.2, x + contentW, y + 8.2);

                    doc.setFontSize(4.8);
                    doc.setTextColor(0, 0, 0);
                    doc.text(label, cX, y + 12.2);
                    doc.setFont('courier', 'bold');
                    doc.setFontSize(14.5);
                    doc.setTextColor(0, 0, 0);
                    doc.text(code, cX, y + 17.8);

                    doc.setFont('helvetica', 'bold');
                    doc.setFontSize(4.6);
                    const badgeText = 'VALID ' + validity;
                    const badgeW = doc.getTextWidth(badgeText) + 3.2;
                    doc.setFillColor(accentRgb.r, accentRgb.g, accentRgb.b);
                    doc.roundedRect(cX, y + 20.1, badgeW, 4.4, 0.9, 0.9, 'F');
                    doc.setTextColor(255, 255, 255);
                    doc.text(badgeText, cX + 1.2, y + 22.9);

                    const qrSize = Math.min(Math.max(12, h * 0.38), 16);
                    const qrX = x + w - qrSize - 2.2;
                    const qrY = y + (h - qrSize) / 2 - 0.6;
                    if (qrDataUrl) {
                        doc.addImage(qrDataUrl, 'PNG', qrX, qrY, qrSize, qrSize);
                    } else {
                        doc.setDrawColor(220, 220, 220);
                        doc.setLineWidth(0.2);
                        doc.roundedRect(qrX, qrY, qrSize, qrSize, 0.8, 0.8, 'S');
                    }
                    doc.setFont('helvetica', 'normal');
                    doc.setFontSize(3.4);
                    doc.setTextColor(150, 150, 150);
                    doc.text('SCAN TO CONNECT', qrX + qrSize / 2, qrY + qrSize + 2.8, { align: 'center' });
                }
            };
        }
    </script>
</div>
