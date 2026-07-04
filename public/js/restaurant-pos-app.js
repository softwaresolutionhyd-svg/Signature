(() => {
    const boot = window.RESTAURANT_POS_BOOTSTRAP || {};
    const products = boot.products || [];
    const menuCategories = boot.menuCategories || [];
    const contacts = boot.contacts || [];
    const settings = boot.settings || {};
    const routes = boot.routes || {};
    const csrf = boot.csrf || '';
    const posMessBillLabel = boot.messBillLabel || 'Mess Bill';
    const posTaxMode = settings.tax_mode || 'line';
    const posDefaultLineTax = Number(settings.default_tax_rate || 0);
    const posShowDiscount = settings.show_discount !== false;
    const posTablesEnabled = !!settings.enable_tables;
    const posGasRatePercent = Number(boot.gasRatePercent || 0);

    let cart = [];
    let payments = [{ method: 'cash', amount: 0 }];
    let orderType = 'sale';
    let saleMode = settings.resume_sale_mode || 'customer';
    let staffIncludeGas = false;
    let isCreditMode = false;
    let selectedContactId = null;
    let resumeOrderId = boot.resumeOrderId || null;
    let selectedMenuCategoryId = null;

    const $ = (sel) => document.querySelector(sel);
    const $$ = (sel) => Array.from(document.querySelectorAll(sel));

    function escHtml(s) {
        return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function fmtMoney(n) {
        const v = Number(n);
        return Number.isFinite(v) ? v.toFixed(2) : '0.00';
    }

    function fmtQty(n) {
        const v = Number(n);
        if (!Number.isFinite(v)) return '0';
        if (Math.abs(v - Math.round(v)) < 1e-6) return String(Math.round(v));
        return parseFloat(v.toFixed(3)).toString();
    }

    function selectedCustomerType() {
        return $('#rpCustomerType')?.value || 'mess_use';
    }

    function setCustomerType(type) {
        const input = $('#rpCustomerType');
        if (input) input.value = type;
        $$('.rp-customer-type').forEach((btn) => {
            btn.classList.toggle('is-active', btn.dataset.type === type);
        });
        syncCustomerFields();
    }

    function isMessBill() {
        return selectedCustomerType() === 'ast_offr';
    }

    function factorForUom(p, uomCode) {
        const u = String(uomCode ?? '').trim();
        if (!p || !u) return 1;
        if (String(p.uom).toLowerCase() === u.toLowerCase()) return 1;
        const row = (p.uoms || []).find((x) => String(x.uom).toLowerCase() === u.toLowerCase());
        return row && Number(row.factor) > 0 ? Number(row.factor) : 1;
    }

    function gasChargeForProduct(p) {
        const direct = Number(p.gas_charges || 0);
        if (direct > 0) return direct;
        const cost = Number(p.cost || 0);
        return cost > 0 && posGasRatePercent > 0 ? Math.round(cost * posGasRatePercent / 100 * 100) / 100 : 0;
    }

    function unitPriceForProduct(p, uomCode) {
        const factor = factorForUom(p, uomCode);
        if (isMessBill()) {
            const cost = Number(p.cost || 0) * factor;
            return Math.round(cost * 100) / 100;
        }
        if (saleMode === 'staff') {
            const cost = Number(p.cost || 0) * factor;
            const gas = gasChargeForProduct(p) * factor;
            return Math.round((cost + gas) * 100) / 100;
        }
        return Math.round(Number(p.price || 0) * factor * 100) / 100;
    }

    function isProductVisible(p) {
        if (isMessBill()) return !!(p.for_purchase || p.for_pos);
        return !!p.for_pos;
    }

    function getBillDiscountPercent() {
        return posShowDiscount ? Number($('#rpBillDiscount')?.value || 0) : 0;
    }

    function calcCartTotals() {
        let subtotal = 0;
        const lineSubs = [];
        cart.forEach((r) => {
            const s = Number(r.qty) * Number(r.unit_price);
            lineSubs.push(s);
            subtotal += s;
        });
        subtotal = Math.round(subtotal * 100) / 100;
        const billDiscPct = getBillDiscountPercent();
        let discount = posShowDiscount && billDiscPct > 0 ? Math.round(subtotal * billDiscPct / 100 * 100) / 100 : 0;
        const tax = 0;
        const grand = Math.round((subtotal - discount) * 100) / 100;
        return { subtotal, discount, tax, grand, lineSubs, billDiscPct };
    }

    function lineRowTotal(r, totals, idx) {
        const lineSub = totals.lineSubs[idx] ?? (Number(r.qty) * Number(r.unit_price));
        let lineDisc = 0;
        if (totals.discount > 0 && totals.subtotal > 0) {
            lineDisc = Math.round(totals.discount * (lineSub / totals.subtotal) * 100) / 100;
        }
        const lineNet = lineSub - lineDisc;
        return Math.round(lineNet * 100) / 100;
    }

    function cartQtyForProduct(productId) {
        return cart.filter((r) => Number(r.product_id) === Number(productId)).reduce((s, r) => s + Number(r.qty), 0);
    }

    function cartLockedQtyForProduct(productId) {
        return cart
            .filter((r) => Number(r.product_id) === Number(productId))
            .reduce((s, r) => s + (Number(r.kitchen_locked_qty) || 0), 0);
    }

    function kitchenLockedFromResume(ri) {
        const qty = Number(ri.qty) || 0;
        return (ri.kitchen_served || ri.kitchen_pending) ? qty : 0;
    }

    function addOrIncrementProduct(id) {
        const p = products.find((x) => Number(x.id) === Number(id));
        if (!p || !isProductVisible(p)) return;
        const existing = cart.find((r) => Number(r.product_id) === Number(id) && String(r.uom) === String(p.uom));
        if (existing) {
            existing.qty = Math.round((Number(existing.qty) + 1) * 1000) / 1000;
            existing.unit_price = unitPriceForProduct(p, existing.uom);
        } else {
            cart.push({
                product_id: p.id,
                name: p.name,
                uom: p.uom,
                qty: 1,
                unit_price: unitPriceForProduct(p, p.uom),
                tax_percent: 0,
                notes: '',
                kitchen_served: false,
                kitchen_pending: false,
                kitchen_locked_qty: 0,
            });
        }
        renderAll();
    }

    function changeCartQty(productId, delta) {
        const p = products.find((x) => Number(x.id) === Number(productId));
        if (delta > 0) {
            addOrIncrementProduct(productId);
            return;
        }
        const locked = cartLockedQtyForProduct(productId);
        const totalQty = cartQtyForProduct(productId);
        const next = Math.round((totalQty + delta) * 1000) / 1000;
        if (next < locked) {
            alert('Kitchen me bheji hui quantity kam nahi ho sakti.');
            return;
        }
        if (next <= 0) {
            cart = cart.filter((r) => Number(r.product_id) !== Number(productId));
            renderAll();
            return;
        }
        let remaining = Math.abs(delta);
        for (let i = cart.length - 1; i >= 0 && remaining > 0; i--) {
            const row = cart[i];
            if (Number(row.product_id) !== Number(productId)) continue;
            const rowLocked = Number(row.kitchen_locked_qty) || 0;
            const reducible = Math.max(0, Number(row.qty) - rowLocked);
            const take = Math.min(reducible, remaining);
            if (take <= 0) continue;
            row.qty = Math.round((Number(row.qty) - take) * 1000) / 1000;
            remaining -= take;
            if (p) row.unit_price = unitPriceForProduct(p, row.uom);
        }
        cart = cart.filter((r) => Number(r.qty) > 0.0005);
        renderAll();
    }

    function applySaleModePricing() {
        cart.forEach((r) => {
            const p = products.find((x) => Number(x.id) === Number(r.product_id));
            if (p) r.unit_price = unitPriceForProduct(p, r.uom);
        });
    }

    function productMatchesMenuCategory(p) {
        if (!selectedMenuCategoryId) return true;
        return Number(p.category_id) === Number(selectedMenuCategoryId);
    }

    function renderMenuCategories() {
        const wrap = $('#rpMenuCats');
        if (!wrap) return;

        if (!menuCategories.length) {
            wrap.innerHTML = '';
            wrap.classList.add('d-none');
            return;
        }

        wrap.classList.remove('d-none');
        const allActive = !selectedMenuCategoryId;
        let html = `<button type="button" class="rp-menu-cat${allActive ? ' is-active' : ''}" data-cat-id="">All</button>`;
        html += menuCategories.map((c) => {
            const active = String(selectedMenuCategoryId) === String(c.id);
            return `<button type="button" class="rp-menu-cat${active ? ' is-active' : ''}" data-cat-id="${c.id}">${escHtml(c.name)}</button>`;
        }).join('');
        wrap.innerHTML = html;
    }

    function setMenuCategory(catId) {
        selectedMenuCategoryId = catId ? String(catId) : null;
        renderMenuCategories();
        renderMenuGrid();
    }

    function displayProductName(name) {
        const s = String(name || '').trim();
        if (!s) return '';
        const letters = s.replace(/[^a-zA-Z]/g, '');
        if (letters.length >= 4 && letters === letters.toUpperCase()) {
            return s.toLowerCase().replace(/\b\w/g, (c) => c.toUpperCase());
        }
        return s;
    }

    function renderMenuGrid() {
        const grid = $('#rpMenuGrid');
        const q = ($('#rpProductSearch')?.value || '').trim().toLowerCase();
        if (!grid) return;
        const list = products.filter((p) => isProductVisible(p) && productMatchesMenuCategory(p) && (
            !q || String(p.name).toLowerCase().includes(q) || String(p.sku || '').toLowerCase().includes(q)
        ));
        if (!list.length) {
            grid.innerHTML = `<div class="rp-empty rp-empty--menu">
                <span class="rp-empty-icon"><i class="bi bi-search"></i></span>
                <span>${selectedMenuCategoryId ? 'Is category mein koi product nahi.' : 'Koi product nahi mili.'}</span>
            </div>`;
            return;
        }
        grid.innerHTML = list.map((p) => {
            const qty = cartQtyForProduct(p.id);
            const locked = cartLockedQtyForProduct(p.id);
            const canDec = qty > locked;
            const price = unitPriceForProduct(p, p.uom);
            const label = displayProductName(p.name);
            return `<div class="rp-menu-item${qty > 0 ? ' has-qty' : ''}" data-product-id="${p.id}">
                <div class="rp-mi-name">${escHtml(label)}</div>
                <div class="rp-mi-price">${fmtMoney(price)}</div>
                <div class="rp-mi-qty">
                    <button type="button" data-action="dec" data-id="${p.id}"${canDec ? '' : ' disabled'} aria-label="Decrease">−</button>
                    <span class="rp-mi-qty-val">${qty > 0 ? fmtQty(qty) : '0'}</span>
                    <button type="button" data-action="inc" data-id="${p.id}" aria-label="Increase">+</button>
                </div>
            </div>`;
        }).join('');
    }

    function renderCart() {
        const wrap = $('#rpCartLines');
        if (!wrap) return;
        if (!cart.length) {
            wrap.innerHTML = `<div class="rp-empty">
                <span class="rp-empty-icon"><i class="bi bi-bag"></i></span>
                <span>Cart khali hai — menu se item add karein.</span>
            </div>`;
            return;
        }
        const totals = calcCartTotals();
        wrap.innerHTML = cart.map((r, i) => {
            const total = lineRowTotal(r, totals, i);
            const locked = Number(r.kitchen_locked_qty) || 0;
            const kitchenBadge = locked > 0
                ? `<span class="rp-kitchen-pill ${r.kitchen_served ? 'rp-kitchen-pill--served' : 'rp-kitchen-pill--pending'}" title="Kitchen me bheja hua">
                    <i class="bi ${r.kitchen_served ? 'bi-check-circle-fill' : 'bi-fire'}"></i>
                    ${r.kitchen_served ? 'Served' : 'Kitchen'}
                   </span>`
                : '';
            return `<div class="rp-cart-line${locked > 0 ? ' is-kitchen-locked' : ''}">
                <div class="rp-cl-main">
                    <span class="rp-cl-qty">${fmtQty(r.qty)}×</span>
                    <span class="rp-cl-name">${escHtml(r.name)}</span>
                    ${kitchenBadge}
                </div>
                <div class="rp-cl-total">${fmtMoney(total)}</div>
            </div>`;
        }).join('');
    }

    function renderTotals() {
        const { subtotal, discount, tax, grand } = calcCartTotals();
        const el = (id, v) => { const n = $(id); if (n) n.textContent = typeof v === 'number' ? fmtMoney(v) : String(v); };
        const itemQty = cart.reduce((s, r) => s + Number(r.qty), 0);
        el('#rpSumItems', cart.length ? `${fmtQty(itemQty)} (${cart.length})` : '0');
        el('#rpSumSubtotal', subtotal);
        el('#rpSumDiscount', discount);
        el('#rpSumGrand', grand);
        const countEl = $('#rpCartCount');
        if (countEl) countEl.textContent = String(cart.length);
    }

    let orderListMode = null;
    let panelView = 'split';

    function setPanelView(view) {
        const app = document.querySelector('.restaurant-pos-app');
        if (!app) return;

        panelView = view;
        app.classList.remove('rp-view-menu', 'rp-view-cart');
        if (view === 'menu') app.classList.add('rp-view-menu');
        if (view === 'cart') app.classList.add('rp-view-cart');
        if (view === 'cart' && orderListMode) {
            setOrderListMode(orderListMode);
        }

        $('#rpTabMenu')?.classList.toggle('is-active', view === 'menu');
        $('#rpTabCart')?.classList.toggle('is-active', view === 'cart');

        const expandBtn = $('#rpToggleCartView');
        const icon = expandBtn?.querySelector('i');
        if (icon) {
            icon.className = view === 'cart' ? 'bi bi-layout-sidebar-reverse' : 'bi bi-arrows-fullscreen';
        }
        if (expandBtn) {
            expandBtn.title = view === 'cart' ? 'Menu dikhayen' : 'Cart full view';
        }
    }

    function togglePanelView(view) {
        setPanelView(panelView === view ? 'split' : view);
    }

    function updateOrderTabCounts() {
        const pendingCount = (boot.pendingBillsDetail || []).length;
        const paidCount = (boot.paidBillsDetail || []).length;
        const pendingEl = $('#rpPendingCount');
        const paidEl = $('#rpPaidCount');
        if (pendingEl) pendingEl.textContent = String(pendingCount);
        if (paidEl) paidEl.textContent = String(paidCount);
    }

    function setOrderListMode(mode) {
        const panel = $('#rpOrderLinePanel');
        const tabPending = $('#rpTabPending');
        const tabPaid = $('#rpTabPaid');
        if (orderListMode === mode) {
            orderListMode = null;
            panel?.classList.add('d-none');
            tabPending?.classList.remove('is-active');
            tabPaid?.classList.remove('is-active');
            return;
        }
        orderListMode = mode;
        panel?.classList.remove('d-none');
        tabPending?.classList.toggle('is-active', mode === 'pending');
        tabPaid?.classList.toggle('is-active', mode === 'paid');
        renderOrderCards();
    }

    function renderOrderCards() {
        const wrap = $('#rpOrderLine');
        if (!wrap || !orderListMode) return;

        if (orderListMode === 'pending') {
            const orders = boot.pendingBillsDetail || [];
            if (!orders.length) {
                wrap.innerHTML = `<div class="rp-empty" style="min-height:0;padding:0.5rem;">
                    <span class="text-secondary small">Koi pending order nahi.</span>
                </div>`;
                return;
            }
            wrap.innerHTML = orders.map((o) => {
                const table = [o.table_name, o.room_no].filter(Boolean).join(' / ') || '—';
                const resumeUrl = (routes.resume || '').replace('__ID__', String(o.id));
                return `<a class="rp-order-card" href="${escHtml(resumeUrl)}">
                    <div class="rp-oc-no">${escHtml(o.order_no)}</div>
                    <div class="rp-oc-meta">${escHtml(o.guest_name || 'Guest')} · ${escHtml(table)}</div>
                    <div class="rp-oc-meta">${escHtml(fmtMoney(o.grand_total))} · ${o.items_count || 0} items</div>
                </a>`;
            }).join('');
            return;
        }

        const paid = boot.paidBillsDetail || [];
        if (!paid.length) {
            wrap.innerHTML = `<div class="rp-empty" style="min-height:0;padding:0.5rem;">
                <span class="text-secondary small">Aaj koi paid order nahi.</span>
            </div>`;
            return;
        }
        wrap.innerHTML = paid.map((o) => {
            const table = [o.table_name, o.room_no].filter(Boolean).join(' / ') || '—';
            const receiptUrl = (routes.receipt || '').replace('__ID__', String(o.id));
            const paidAt = o.paid_at_full || o.paid_at || '';
            return `<a class="rp-order-card rp-order-card-paid" href="${escHtml(receiptUrl)}" target="_blank" rel="noopener">
                <div class="rp-oc-no">${escHtml(o.order_no)}</div>
                <div class="rp-oc-meta">${escHtml(o.guest_name || 'Guest')} · ${escHtml(table)}</div>
                <div class="rp-oc-meta">${escHtml(fmtMoney(o.grand_total))} · ${o.payment_label || 'Paid'}</div>
                ${paidAt ? `<div class="rp-oc-pay">${escHtml(paidAt)}</div>` : ''}
            </a>`;
        }).join('');
    }

    function syncSaleModeUi() {
        const type = selectedCustomerType();
        const walkInOrInHouse = type === 'mess_use' || type === 'booking';
        const isStaff = saleMode === 'staff';

        $('#rpStaffGasCol')?.classList.toggle('d-none', type !== 'ast_offr');

        if (walkInOrInHouse && isStaff) {
            staffIncludeGas = true;
        } else if (!isMessBill()) {
            staffIncludeGas = false;
            if ($('#rpStaffGas')) $('#rpStaffGas').checked = false;
        }
    }

    function syncCustomerFields() {
        const type = selectedCustomerType();
        const walkIn = type === 'mess_use';
        const booking = type === 'booking';
        $('#rpWalkInGuestCol')?.classList.toggle('d-none', !walkIn);
        $('#rpWalkInWaiterCol')?.classList.toggle('d-none', !walkIn);
        $('#rpWalkInServeCol')?.classList.toggle('d-none', !walkIn);
        $('#rpBookingFields')?.classList.toggle('d-none', !booking);
        $('#rpTableBlock')?.classList.toggle('d-none', booking || !posTablesEnabled);
        $('#rpCreditBlock')?.classList.toggle('d-none', type === 'ast_offr');
        if (type === 'ast_offr') {
            isCreditMode = true;
            saleMode = 'staff';
            $('#rpCreditToggle') && ($('#rpCreditToggle').checked = true);
        }
        $('#rpStaffBlock')?.classList.toggle('d-none', type === 'ast_offr');
        syncSaleModeUi();
        applySaleModePricing();
        renderMenuGrid();
    }

    function setCreditMode(on) {
        const type = selectedCustomerType();
        if (type === 'ast_offr') on = true;
        isCreditMode = on;
        const toggle = $('#rpCreditToggle');
        if (toggle) {
            toggle.checked = on;
            toggle.disabled = type === 'ast_offr';
        }
        $('#rpPaymentsBlock')?.classList.toggle('d-none', on);
        $('#rpPayBtn')?.classList.toggle('btn-rp-primary', !on);
        $('#rpPayBtn')?.classList.toggle('btn-danger', on);
        if ($('#rpPayBtn')) $('#rpPayBtn').textContent = on ? 'Record Credit' : 'Pay Now';
    }

    function renderAll() {
        renderMenuCategories();
        renderMenuGrid();
        renderCart();
        renderTotals();
        if (autoPaymentAmount && payments.length === 1) {
            payments[0].amount = calcCartTotals().grand;
        }
    }

    let autoPaymentAmount = true;

    function cartItemsForSubmit() {
        const totals = calcCartTotals();
        return cart.map((r, idx) => ({
            product_id: r.product_id,
            uom: r.uom,
            qty: r.qty,
            unit_price: r.unit_price,
            discount_percent: 0,
            tax_percent: 0,
            notes: String(r.notes || '').trim(),
            line_total: lineRowTotal(r, totals, idx),
        }));
    }

    function prepareSubmit(mode) {
        if (!cart.length) {
            alert('Pehle item add karein.');
            return false;
        }
        const type = selectedCustomerType();
        if (type === 'mess_use') {
            if (!($('#rpGuestName')?.value || '').trim()) {
                alert('Guest name required.');
                return false;
            }
            if (!($('#rpWaiter')?.value || '').trim()) {
                alert('Waiter select karein.');
                return false;
            }
        } else if (type === 'booking' && !($('#rpRoom')?.value || '').trim()) {
            alert('Room select karein.');
            return false;
        }
        if ((isCreditMode || type === 'ast_offr') && !selectedContactId) {
            alert(type === 'ast_offr' ? posMessBillLabel + ' ke liye contact select karein.' : 'Credit sale ke liye contact select karein.');
            return false;
        }
        if (mode === 'checkout' && !isCreditMode && orderType === 'sale') {
            const grand = calcCartTotals().grand;
            const paySum = payments.reduce((s, p) => s + Number(p.amount || 0), 0);
            if (Math.abs(paySum - grand) > 0.02) {
                alert('Payment total match nahi kar raha.');
                return false;
            }
        }
        applySaleModePricing();
        const form = $('#rpSubmitForm');
        if (!form) return false;
        form.querySelector('[name="type"]').value = orderType;
        form.querySelector('[name="sale_mode"]').value = isMessBill() ? 'staff' : saleMode;
        form.querySelector('[name="staff_include_gas"]').value =
            (saleMode === 'staff' && !isMessBill()) || (isMessBill() && staffIncludeGas) ? '1' : '0';
        form.querySelector('[name="customer_type"]').value = type;
        form.querySelector('[name="is_credit"]').value = (isCreditMode || type === 'ast_offr') ? '1' : '0';
        form.querySelector('[name="contact_id"]').value = selectedContactId || '';
        form.querySelector('[name="table_id"]').value = (posTablesEnabled && type !== 'booking') ? ($('#rpTable')?.value || '') : '';
        form.querySelector('[name="guest_name"]').value = type === 'booking'
            ? ($('#rpRoom')?.selectedOptions?.[0]?.dataset?.guestName || '')
            : ($('#rpGuestName')?.value || '');
        form.querySelector('[name="room_no"]').value = type === 'booking' ? ($('#rpRoom')?.value || '') : '';
        form.querySelector('[name="waiter_name"]').value = type === 'mess_use' ? ($('#rpWaiter')?.value || '') : '';
        form.querySelector('[name="order_notes"]').value = ($('#rpOrderNotes')?.value || '').trim();
        form.querySelector('[name="serve_time"]').value = type === 'mess_use' ? ($('#rpServeTime')?.value || '') : '';
        form.querySelector('[name="items"]').value = JSON.stringify(cartItemsForSubmit());
        form.querySelector('[name="payments"]').value = JSON.stringify(
            (isCreditMode || type === 'ast_offr') ? [] : (mode === 'hold' ? [{ method: 'cash', amount: 0 }] : payments)
        );
        form.querySelector('[name="bill_tax_percent"]').value = '0';
        form.querySelector('[name="bill_discount_percent"]').value = posShowDiscount ? String(getBillDiscountPercent()) : '0';
        form.querySelector('[name="resume_order_id"]').value = resumeOrderId ? String(resumeOrderId) : '';
        form.action = mode === 'hold' ? routes.hold : routes.checkout;
        return true;
    }

    function upsertPendingBill(order, updated) {
        const list = Array.isArray(boot.pendingBillsDetail) ? [...boot.pendingBillsDetail] : [];
        const idx = list.findIndex((o) => Number(o.id) === Number(order.id));
        if (idx >= 0) {
            list[idx] = order;
        } else if (!updated) {
            list.unshift(order);
        } else {
            list.unshift(order);
        }
        boot.pendingBillsDetail = list;
        updateOrderTabCounts();
        if (orderListMode === 'pending') {
            renderOrderCards();
        }
    }

    function resetForNewBill() {
        cart.length = 0;
        resumeOrderId = null;
        selectedContactId = null;
        payments = [{ method: $('#rpPayMethod')?.value || 'cash', amount: 0 }];
        autoPaymentAmount = true;

        const form = $('#rpSubmitForm');
        if (form) {
            form.querySelector('[name="resume_order_id"]').value = '';
        }

        if ($('#rpGuestName')) $('#rpGuestName').value = '';
        if ($('#rpWaiter')) $('#rpWaiter').value = '';
        if ($('#rpServeTime')) $('#rpServeTime').value = '';
        if ($('#rpTable')) $('#rpTable').value = '';
        if ($('#rpRoom')) $('#rpRoom').selectedIndex = 0;
        $('#rpSelectedContactWrap')?.classList.add('d-none');
        if ($('#rpContactSearch')) $('#rpContactSearch').value = '';

        document.querySelector('.rp-badge-order')?.remove();

        const url = new URL(window.location.href);
        if (url.searchParams.has('resume_order')) {
            url.searchParams.delete('resume_order');
            window.history.replaceState({}, '', url.pathname + url.search);
        }

        syncCustomerFields();
        setCreditMode(false);
        if (isMessBill()) setCreditMode(true);
        renderAll();
        $('#rpProductSearch')?.focus();
    }

    async function submitHoldOrder() {
        if (!prepareSubmit('hold')) return;

        const holdBtn = $('#rpHoldBtn');
        const form = $('#rpSubmitForm');
        if (!form) return;

        const totals = calcCartTotals();
        const formData = new FormData(form);
        formData.set('items', JSON.stringify(cartItemsForSubmit()));
        formData.set('client_grand_total', String(totals.grand));
        formData.set('client_subtotal', String(totals.subtotal));
        formData.set('client_discount_total', String(totals.discount));
        formData.set('client_tax_total', String(totals.tax));

        if (holdBtn) holdBtn.disabled = true;
        try {
            const res = await fetch(routes.hold, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                const validationMsg = data.errors ? Object.values(data.errors).flat()[0] : null;
                throw new Error(data.message || validationMsg || 'Hold failed.');
            }

            if (data.order) {
                upsertPendingBill(data.order, !!data.updated);
            } else if (typeof data.held_count === 'number') {
                updateOrderTabCounts();
            }

            resetForNewBill();
        } catch (e) {
            alert(e.message || 'Hold failed.');
        } finally {
            if (holdBtn) holdBtn.disabled = false;
        }
    }

    function submitOrder(mode) {
        if (!prepareSubmit(mode)) return;
        $('#rpSubmitForm')?.submit();
    }

    function filterContacts(q) {
        const needle = q.toLowerCase();
        return contacts.filter((c) =>
            String(c.name || '').toLowerCase().includes(needle) || String(c.phone || '').toLowerCase().includes(needle)
        ).slice(0, 12);
    }

    function bindEvents() {
        $('#rpProductSearch')?.addEventListener('input', renderMenuGrid);
        $('#rpMenuCats')?.addEventListener('click', (e) => {
            const btn = e.target.closest('.rp-menu-cat');
            if (!btn) return;
            setMenuCategory(btn.dataset.catId || null);
        });
        $('#rpMenuGrid')?.addEventListener('click', (e) => {
            const btn = e.target.closest('button[data-action]');
            if (!btn) return;
            const id = Number(btn.dataset.id);
            if (btn.dataset.action === 'inc') addOrIncrementProduct(id);
            if (btn.dataset.action === 'dec') changeCartQty(id, -1);
        });
        $('#rpCustomerTypes')?.addEventListener('click', (e) => {
            const btn = e.target.closest('.rp-customer-type');
            if (!btn?.dataset.type) return;
            setCustomerType(btn.dataset.type);
        });
        $('#rpSaleMode')?.addEventListener('change', () => {
            saleMode = $('#rpSaleMode')?.value || 'customer';
            syncSaleModeUi();
            applySaleModePricing();
            renderAll();
        });
        $('#rpStaffGas')?.addEventListener('change', () => {
            if (!isMessBill()) return;
            staffIncludeGas = !!$('#rpStaffGas')?.checked;
            applySaleModePricing();
            renderAll();
        });
        $('#rpCreditToggle')?.addEventListener('change', (e) => setCreditMode(e.target.checked));
        $('#rpHoldBtn')?.addEventListener('click', () => submitHoldOrder());
        $('#rpPayBtn')?.addEventListener('click', () => submitOrder('checkout'));
        $('#rpTabPending')?.addEventListener('click', () => setOrderListMode('pending'));
        $('#rpTabPaid')?.addEventListener('click', () => setOrderListMode('paid'));
        $('#rpTabMenu')?.addEventListener('click', () => togglePanelView('menu'));
        $('#rpTabCart')?.addEventListener('click', () => togglePanelView('cart'));
        $('#rpToggleCartView')?.addEventListener('click', () => togglePanelView('cart'));
        $('#rpBillDiscount')?.addEventListener('input', renderTotals);

        const contactSearch = $('#rpContactSearch');
        const contactDrop = $('#rpContactDropdown');
        contactSearch?.addEventListener('input', () => {
            const q = contactSearch.value.trim();
            if (q.length < 1) {
                contactDrop?.classList.add('d-none');
                return;
            }
            const rows = filterContacts(q);
            contactDrop.innerHTML = rows.map((c) =>
                `<button type="button" class="dropdown-item" data-id="${c.id}" data-name="${escHtml(c.name)}" data-phone="${escHtml(c.phone || '')}">${escHtml(c.name)} <span class="text-secondary">${escHtml(c.phone || '')}</span></button>`
            ).join('') || '<div class="dropdown-item-text text-secondary small">No contact</div>';
            contactDrop.classList.remove('d-none');
        });
        contactDrop?.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-id]');
            if (!btn) return;
            selectedContactId = btn.dataset.id;
            $('#rpSelectedContact').textContent = btn.dataset.name + (btn.dataset.phone ? ' · ' + btn.dataset.phone : '');
            $('#rpSelectedContactWrap')?.classList.remove('d-none');
            contactDrop.classList.add('d-none');
            contactSearch.value = '';
        });
        $('#rpClearContact')?.addEventListener('click', () => {
            selectedContactId = null;
            $('#rpSelectedContactWrap')?.classList.add('d-none');
        });

        $('#rpPayMethod')?.addEventListener('change', () => {
            payments = [{ method: $('#rpPayMethod')?.value || 'cash', amount: calcCartTotals().grand }];
        });
    }

    function loadResumeItems() {
        const items = boot.resumeItems || [];
        items.forEach((ri) => {
            const p = products.find((x) => Number(x.id) === Number(ri.product_id));
            if (!p) return;
            cart.push({
                product_id: ri.product_id,
                name: p.name,
                uom: ri.uom || p.uom,
                qty: Number(ri.qty) || 1,
                unit_price: Number(ri.unit_price) || unitPriceForProduct(p, ri.uom || p.uom),
                tax_percent: Number(ri.tax_percent) || 0,
                notes: ri.notes || '',
                kitchen_served: !!ri.kitchen_served,
                kitchen_pending: !!ri.kitchen_pending,
                kitchen_locked_qty: kitchenLockedFromResume(ri),
            });
        });
    }

    async function pollSync() {
        if (!routes.sync) return;
        try {
            const res = await fetch(routes.sync, { headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) return;
            const data = await res.json();
            if (Array.isArray(data.pending)) {
                boot.pendingBillsDetail = data.pending;
                updateOrderTabCounts();
                if (orderListMode === 'pending') {
                    renderOrderCards();
                }
            }
        } catch (_) { /* ignore */ }
    }

    function init() {
        if (settings.resume_sale_mode) saleMode = settings.resume_sale_mode;
        loadResumeItems();
        bindEvents();
        syncCustomerFields();
        setCreditMode(false);
        if (isMessBill()) setCreditMode(true);
        updateOrderTabCounts();
        renderAll();
        payments = [{ method: 'cash', amount: 0 }];
        setInterval(pollSync, 20000);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
