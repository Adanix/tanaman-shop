/* ==========================================================================
   Plantify Official — app.js
   Responsive client-side interactions: Cart Drawer, Steppers, Filters,
   Gallery, Pot Variants, Accordions, Mobile Navigation, Toast Notifications.
   ========================================================================== */

(function () {
    'use strict';

    const STORE_KEY = 'plantify-cart';

    function $(s, r) {
        return (r || document).querySelector(s);
    }
    function $$(s, r) {
        return Array.prototype.slice.call((r || document).querySelectorAll(s));
    }

    // Currency Formatter (Indonesian Rupiah)
    function money(amount) {
        return 'Rp ' + Number(amount).toLocaleString('id-ID');
    }

    /* ------------------------------------------------------------------------
       Cart State Management (localStorage)
       ------------------------------------------------------------------------ */
    function readCart() {
        try {
            const raw = localStorage.getItem(STORE_KEY);
            const parsed = raw ? JSON.parse(raw) : [];
            return Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            return [];
        }
    }

    function writeCart(items) {
        try {
            localStorage.setItem(STORE_KEY, JSON.stringify(items));
        } catch (e) {}
        renderCart();
    }

    function addItem(item) {
        const items = readCart();
        const key = item.id + '::' + (item.variant || '');
        const existing = items.find((i) => i.id + '::' + (i.variant || '') === key);

        if (existing) {
            existing.qty += item.qty;
        } else {
            items.push(item);
        }

        writeCart(items);
        toast(item.name + ' berhasil ditambahkan ke keranjang!');
    }

    function setQty(index, qty) {
        const items = readCart();
        if (!items[index]) return;
        if (qty <= 0) {
            items.splice(index, 1);
        } else {
            items[index].qty = qty;
        }
        writeCart(items);
    }

    function subtotal(items) {
        return items.reduce((sum, i) => sum + i.price * i.qty, 0);
    }

    /* ------------------------------------------------------------------------
       Cart Render Logic
       ------------------------------------------------------------------------ */
    function renderCart() {
        const items = readCart();
        const count = items.reduce((n, i) => n + i.qty, 0);
        const sub = subtotal(items);
        // Free delivery threshold: Rp 250.000
        const threshold = 250000;
        const delivery = items.length === 0 || sub >= threshold ? 0 : 25000;

        // Badge counters
        $$('[data-cart-count]').forEach((node) => {
            node.textContent = count;
            node.hidden = count === 0;
        });

        // Totals
        $$('[data-cart-subtotal]').forEach((n) => {
            n.textContent = money(sub);
        });
        $$('[data-cart-delivery]').forEach((n) => {
            n.textContent = delivery === 0 ? 'Gratis' : money(delivery);
        });
        $$('[data-cart-total]').forEach((n) => {
            n.textContent = money(sub + delivery);
        });

        // Progress threshold bar
        $$('[data-threshold-bar]').forEach((bar) => {
            const percent = Math.min(100, Math.round((sub / threshold) * 100));
            bar.style.width = percent + '%';
        });
        $$('[data-threshold-text]').forEach((txt) => {
            if (sub >= threshold) {
                txt.textContent = '🎉 Selamat! Pesanan Anda memenuhi syarat Bebas Ongkir!';
            } else {
                const diff = threshold - sub;
                txt.textContent = `Tambah ${money(diff)} lagi untuk mendapatkan Gratis Ongkir!`;
            }
        });

        // Empty vs Filled states
        $$('[data-cart-empty]').forEach((n) => {
            n.hidden = items.length > 0;
        });
        $$('[data-cart-filled]').forEach((n) => {
            n.hidden = items.length === 0;
        });

        // Render line items in Drawer & Cart Page
        $$('[data-cart-lines]').forEach((host) => {
            host.innerHTML = '';
            items.forEach((item, index) => {
                host.appendChild(createLineItemNode(item, index, host.dataset.cartLines === 'page'));
            });
        });
    }

    function createLineItemNode(item, index, isPage) {
        const row = document.createElement('div');
        row.className = isPage
            ? 'flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 bg-paper border border-hairline rounded-2xl'
            : 'flex items-center gap-3 pb-3 border-b border-hairline-soft';

        // Image
        const img = document.createElement('img');
        img.src = item.image;
        img.alt = item.name;
        img.className = isPage
            ? 'w-20 h-24 object-cover rounded-xl bg-glass flex-shrink-0'
            : 'w-16 h-20 object-cover rounded-lg bg-glass flex-shrink-0';

        // Details
        const details = document.createElement('div');
        details.className = 'flex-1 min-w-0';
        details.innerHTML = `
            <h4 class="font-semibold text-forest text-sm sm:text-base truncate">${item.name}</h4>
            <p class="text-xs text-forest-3 mt-0.5">${item.variant || 'Ukuran Standar'}</p>
            <p class="text-xs font-mono font-medium text-ochre-deep mt-1">${money(item.price)}</p>
        `;

        // Stepper
        const stepper = document.createElement('div');
        stepper.className = 'flex items-center border border-hairline rounded-full bg-paper px-2 py-1 gap-2 flex-shrink-0';
        stepper.innerHTML = `
            <button type="button" class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-glass text-forest text-sm font-bold" aria-label="Kurangi jumlah">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14"/></svg>
            </button>
            <span class="font-mono text-sm font-semibold w-6 text-center">${item.qty}</span>
            <button type="button" class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-glass text-forest text-sm font-bold" aria-label="Tambah jumlah">
                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
            </button>
        `;

        const [minusBtn, plusBtn] = stepper.querySelectorAll('button');
        minusBtn.addEventListener('click', () => setQty(index, item.qty - 1));
        plusBtn.addEventListener('click', () => setQty(index, item.qty + 1));

        // Right side: Subtotal & Delete
        const right = document.createElement('div');
        right.className = isPage ? 'text-right flex sm:flex-col items-center sm:items-end justify-between w-full sm:w-auto gap-2' : 'text-right flex-shrink-0';
        right.innerHTML = `
            <span class="font-mono font-bold text-forest text-sm sm:text-base">${money(item.price * item.qty)}</span>
            <button type="button" class="text-xs text-sale hover:underline block cursor-pointer min-h-[36px] flex items-center" aria-label="Hapus tanaman">
                Hapus
            </button>
        `;
        right.querySelector('button').addEventListener('click', () => setQty(index, 0));

        row.appendChild(img);
        row.appendChild(details);
        row.appendChild(stepper);
        row.appendChild(right);
        return row;
    }

    /* ------------------------------------------------------------------------
       Cart Drawer Open / Close
       ------------------------------------------------------------------------ */
    let drawerNode, scrimNode;

    function openDrawer() {
        if (!drawerNode) return;
        drawerNode.dataset.open = 'true';
        if (scrimNode) scrimNode.dataset.open = 'true';
        document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
        if (!drawerNode) return;
        drawerNode.dataset.open = 'false';
        if (scrimNode) scrimNode.dataset.open = 'false';
        document.body.style.overflow = '';
    }

    function initDrawer() {
        drawerNode = $('.drawer');
        scrimNode = $('#drawerScrim');

        $$('[data-action="open-drawer"]').forEach((b) => b.addEventListener('click', openDrawer));
        $$('[data-action="close-drawer"]').forEach((b) => b.addEventListener('click', closeDrawer));
        if (scrimNode) scrimNode.addEventListener('click', closeDrawer);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && drawerNode && drawerNode.dataset.open === 'true') {
                closeDrawer();
            }
        });
    }

    /* ------------------------------------------------------------------------
       Product Card Add-To-Basket
       ------------------------------------------------------------------------ */
    function initAddButtons() {
        $$('[data-add]').forEach((btn) => {
            btn.addEventListener('click', (event) => {
                event.preventDefault();
                const scope = btn.closest('[data-product]') || document;
                const variantBtn = $('.variant[aria-pressed="true"]', scope);
                const qtyInput = $('[data-qty-input]', scope);

                addItem({
                    id: btn.dataset.add,
                    name: btn.dataset.name,
                    price: parseInt(variantBtn ? variantBtn.dataset.price : btn.dataset.price, 10),
                    image: btn.dataset.image,
                    variant: variantBtn ? variantBtn.dataset.variant : btn.dataset.variant || 'Pot 12cm',
                    qty: qtyInput ? Math.max(1, parseInt(qtyInput.value, 10) || 1) : 1,
                });

                if (btn.dataset.openDrawer !== 'false') {
                    openDrawer();
                }
            });
        });
    }

    /* ------------------------------------------------------------------------
       Product Detail Page: Variants, Gallery, Stepper, Accordions
       ------------------------------------------------------------------------ */
    function initProductDetails() {
        // Variants
        $$('.variants').forEach((group) => {
            const buttons = $$('.variant', group);
            buttons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    buttons.forEach((b) => b.setAttribute('aria-pressed', String(b === btn)));
                    const priceNode = $('[data-variant-price]');
                    if (priceNode) {
                        priceNode.textContent = money(parseInt(btn.dataset.price, 10));
                    }
                });
            });
        });

        // Quantity Stepper on PDP
        $$('[data-qty]').forEach((stepper) => {
            const input = $('input', stepper);
            const [minusBtn, plusBtn] = $$('button', stepper);
            if (!input || !minusBtn || !plusBtn) return;

            minusBtn.addEventListener('click', () => {
                input.value = Math.max(1, (parseInt(input.value, 10) || 1) - 1);
            });
            plusBtn.addEventListener('click', () => {
                input.value = (parseInt(input.value, 10) || 1) + 1;
            });
        });

        // Gallery Switcher
        const mainGallery = $('[data-gallery-main]');
        $$('[data-gallery-thumb]').forEach((thumb) => {
            thumb.addEventListener('click', () => {
                if (!mainGallery) return;
                mainGallery.src = thumb.dataset.galleryThumb;
                mainGallery.alt = thumb.dataset.alt || '';
                $$('[data-gallery-thumb]').forEach((t) => {
                    t.setAttribute('aria-current', String(t === thumb));
                });
            });
        });

        // Accordion
        $$('.acc__btn').forEach((btn) => {
            btn.addEventListener('click', () => {
                const isOpen = btn.getAttribute('aria-expanded') === 'true';
                btn.setAttribute('aria-expanded', String(!isOpen));
                const icon = btn.querySelector('.acc__icon');
                if (icon) {
                    icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
                }
                const panel = document.getElementById(btn.getAttribute('aria-controls'));
                if (panel) {
                    panel.hidden = isOpen;
                }
            });
        });
    }

    /* ------------------------------------------------------------------------
       Shop Catalog Filtering & Sorting
       ------------------------------------------------------------------------ */
    function initCatalogFilters() {
        const grid = $('[data-product-grid]');
        if (!grid) return;

        const cards = $$('[data-tags]', grid);
        const countNode = $('[data-result-count]');
        const emptyNode = $('[data-no-results]');

        function activeTags() {
            const tags = [];
            $$('.chip[aria-pressed="true"][data-filter]').forEach((c) => tags.push(c.dataset.filter));
            $$('[data-filter-check]:checked').forEach((c) => tags.push(c.dataset.filterCheck));
            return tags;
        }

        function applyFilter() {
            const tags = activeTags();
            let count = 0;
            cards.forEach((card) => {
                const ownTags = (card.dataset.tags || '').split(' ');
                const match = tags.every((t) => ownTags.includes(t));
                card.hidden = !match;
                if (match) count++;
            });
            if (countNode) countNode.textContent = count;
            if (emptyNode) emptyNode.hidden = count > 0;
        }

        $$('.chip[data-filter]').forEach((chip) => {
            chip.addEventListener('click', () => {
                const isPressed = chip.getAttribute('aria-pressed') === 'true';
                chip.setAttribute('aria-pressed', isPressed ? 'false' : 'true');
                applyFilter();
            });
        });

        $$('[data-filter-check]').forEach((box) => {
            box.addEventListener('change', applyFilter);
        });

        const clearBtn = $('[data-clear-filters]');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => {
                $$('.chip[data-filter]').forEach((c) => c.setAttribute('aria-pressed', 'false'));
                $$('[data-filter-check]').forEach((c) => (c.checked = false));
                applyFilter();
            });
        }

        // Sorting
        const sortSelect = $('[data-sort]');
        if (sortSelect) {
            sortSelect.addEventListener('change', () => {
                const mode = sortSelect.value;
                const sorted = cards.slice().sort((a, b) => {
                    const pa = parseInt(a.dataset.price, 10);
                    const pb = parseInt(b.dataset.price, 10);
                    if (mode === 'price-asc') return pa - pb;
                    if (mode === 'price-desc') return pb - pa;
                    if (mode === 'name') return (a.dataset.name || '').localeCompare(b.dataset.name || '');
                    return parseInt(a.dataset.order || '0', 10) - parseInt(b.dataset.order || '0', 10);
                });
                sorted.forEach((card) => grid.appendChild(card));
            });
        }

        applyFilter();
    }

    /* ------------------------------------------------------------------------
       Mobile Navigation Burger & Wishlist
       ------------------------------------------------------------------------ */
    function initMobileNav() {
        const burger = $('.burger');
        const nav = $('.mobile-nav');
        if (burger && nav) {
            burger.addEventListener('click', () => {
                const isOpen = nav.dataset.open === 'true';
                nav.dataset.open = String(!isOpen);
                burger.setAttribute('aria-expanded', String(!isOpen));
            });
        }

        // Wishlist hearts
        $$('.product__wish').forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const isOn = btn.getAttribute('aria-pressed') === 'true';
                btn.setAttribute('aria-pressed', String(!isOn));
                toast(isOn ? 'Dihapus dari daftar impian' : 'Disimpan ke daftar impian!');
            });
        });

        // Demo Forms
        $$('form[data-demo-form]').forEach((form) => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                toast(form.dataset.demoForm);
                form.reset();
            });
        });

        // Toast triggers
        $$('[data-toast]').forEach((btn) => {
            btn.addEventListener('click', () => toast(btn.dataset.toast));
        });
    }

    /* ------------------------------------------------------------------------
       Toast System
       ------------------------------------------------------------------------ */
    function toast(message) {
        const host = $('.toasts');
        if (!host) return;

        const node = document.createElement('div');
        node.className = 'toast';
        node.setAttribute('role', 'status');
        node.innerHTML = `
            <svg class="w-5 h-5 text-ochre flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="m20 6-11 11-5-5"/>
            </svg>
            <span class="font-medium">${message}</span>
        `;

        host.appendChild(node);
        setTimeout(() => {
            node.style.transition = 'opacity 200ms ease, transform 200ms ease';
            node.style.opacity = '0';
            node.style.transform = 'translateY(8px)';
            setTimeout(() => node.remove(), 220);
        }, 2800);
    }

    /* ------------------------------------------------------------------------
       Initialize on DOMContentLoaded
       ------------------------------------------------------------------------ */
    document.addEventListener('DOMContentLoaded', () => {
        initDrawer();
        initAddButtons();
        initProductDetails();
        initCatalogFilters();
        initMobileNav();
        renderCart();
    });

    // Global Object for template calls
    window.Plantify = {
        toast,
        addItem,
        readCart,
        openDrawer,
        closeDrawer,
    };
})();
