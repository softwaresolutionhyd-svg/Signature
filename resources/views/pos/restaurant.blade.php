@extends('layouts.admin')
@section('title', 'Restaurant POS — ' . config('app.name'))
@section('page-title', 'Restaurant POS')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/restaurant-pos.css') }}?v=24">
@endpush

@section('content')
@php
    $defaultCustomerType = old('customer_type', $posSettings['resume_customer_type'] ?? 'mess_use');
    if ($defaultCustomerType === 'booking') {
        $defaultCustomerType = 'mess_use';
    }
    $productJs = $products->map(function ($p) {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'sku' => $p->sku,
            'uom' => $p->uom,
            'price' => (float) $p->price,
            'cost' => (float) $p->cost,
            'gas_charges' => (float) $p->gasChargesAmount(),
            'for_pos' => (bool) ($p->for_pos ?? false),
            'for_purchase' => (bool) ($p->for_purchase ?? true),
            'category_id' => $p->category_id ? (int) $p->category_id : null,
            'category_parent_id' => $p->category?->parent_id ? (int) $p->category->parent_id : null,
            'uoms' => collect($p->uomsForForms())->map(fn ($row) => [
                'uom' => $row['uom'],
                'factor' => (float) $row['factor'],
            ])->values()->all(),
        ];
    })->values();

    $menuCategoryMap = [];
    foreach ($products as $p) {
        if (! $p->category_id || ! $p->category || ! $p->category->parent_id || ! $p->category->parent) {
            continue;
        }
        if (strcasecmp((string) $p->category->parent->name, 'Menu') !== 0) {
            continue;
        }
        $cat = $p->category;
        $menuCategoryMap[$cat->id] = [
            'id' => (int) $cat->id,
            'name' => (string) $cat->name,
            'parent_id' => (int) $cat->parent_id,
            'parent_name' => (string) $cat->parent->name,
            'is_sub' => true,
            'sort' => strtolower($cat->name),
        ];
    }
    $menuCategories = collect($menuCategoryMap)
        ->sortBy('sort')
        ->values()
        ->all();
    $resumeItems = collect($resumedOrder?->items ?? [])->map(fn ($i) => [
        'product_id' => $i->product_id,
        'uom' => $i->uom,
        'qty' => (float) $i->qty,
        'unit_price' => (float) $i->unit_price,
        'tax_percent' => (float) $i->tax_percent,
        'notes' => (string) ($i->notes ?? ''),
        'kitchen_served' => $i->isKitchenServed(),
        'kitchen_pending' => (bool) $i->kitchen_pending,
    ])->values();
    $resumeStub = str_replace('999999999', '__ID__', route('restaurant-pos.resume', ['order' => 999999999]));
@endphp

<div class="restaurant-pos-app">
    <header class="rp-topbar">
        <div class="rp-topbar-brand">
            <span class="rp-brand-mark" aria-hidden="true"><i class="bi bi-cup-hot-fill"></i></span>
            <div class="rp-brand-text">
                <span class="rp-brand-title">Restaurant</span>
                <span class="rp-brand-sub">{{ $session->business_date?->format('d M Y') ?? now()->format('d M Y') }}</span>
            </div>
        </div>
        <div class="rp-search">
            <i class="bi bi-search rp-search-icon" aria-hidden="true"></i>
            <input type="search" id="rpProductSearch" class="form-control form-control-sm" placeholder="Search menu…" autocomplete="off">
        </div>
        <div class="rp-topbar-actions">
            @if($resumedOrder)
                <span class="badge rp-badge-order">{{ $resumedOrder->order_no }}</span>
            @endif
            <button type="button" class="btn btn-sm rp-order-tab" id="rpTabMenu" data-mode="menu">
                <i class="bi bi-grid-3x3-gap-fill"></i> Menu
            </button>
            <button type="button" class="btn btn-sm rp-order-tab" id="rpTabCart" data-mode="cart">
                <i class="bi bi-bag-check"></i> Cart
            </button>
            <button type="button" class="btn btn-sm rp-order-tab" id="rpTabPending" data-mode="pending">
                <i class="bi bi-hourglass-split"></i> Pending
                <span class="badge rp-badge-count rp-badge-pending" id="rpPendingCount">{{ $heldOrders->count() }}</span>
            </button>
            <button type="button" class="btn btn-sm rp-order-tab" id="rpTabPaid" data-mode="paid">
                <i class="bi bi-check-circle"></i> Paid
                <span class="badge rp-badge-count rp-badge-paid" id="rpPaidCount">{{ $paidOrders->count() }}</span>
            </button>
            <a href="{{ route('pos.index') }}" class="btn btn-sm btn-outline-secondary rp-link-cafe">
                <i class="bi bi-shop"></i> POS Restaurant
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary rp-link-exit" title="Dashboard">
                <i class="bi bi-box-arrow-left"></i>
            </a>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success py-2 mx-3 mt-2 mb-0">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2 mx-3 mt-2 mb-0">{{ session('error') }}</div>
    @endif

    <div class="rp-order-zone">
        <div class="rp-order-fields" id="rpOrderFieldsPanel">
            <div class="row g-1 align-items-end rp-fields-grid">
                <div class="col-12 col-md-auto rp-field-cell rp-customer-type-cell">
                    <input type="hidden" id="rpCustomerType" value="{{ $defaultCustomerType }}">
                    <div class="rp-customer-types" id="rpCustomerTypes" role="group" aria-label="Customer type">
                        <button type="button" class="rp-customer-type{{ $defaultCustomerType === 'mess_use' ? ' is-active' : '' }}" data-type="mess_use">Walk-In</button>
                        <button type="button" class="rp-customer-type{{ $defaultCustomerType === 'ast_offr' ? ' is-active' : '' }}" data-type="ast_offr">{{ \App\Models\PosOrder::MESS_BILL_LABEL }}</button>
                    </div>
                </div>
                @if($posSettings['enable_tables'] ?? false)
                    <div class="col-6 col-md-2 col-lg-auto rp-field-cell" id="rpTableBlock">
                        <label class="form-label" for="rpTable">Table</label>
                        <select id="rpTable" class="form-select form-select-sm">
                            <option value="">No table</option>
                            @foreach($tables as $t)
                                <option value="{{ $t->id }}" @selected(($posSettings['resume_table_id'] ?? null) === (int) $t->id)>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                <div class="col-6 col-md-2 col-lg-auto rp-field-cell" id="rpWalkInGuestCol">
                    <label class="form-label" for="rpGuestName">Guest</label>
                    <input type="text" id="rpGuestName" class="form-control form-control-sm" maxlength="120"
                           value="{{ old('guest_name', $posSettings['resume_guest_name'] ?? '') }}">
                </div>
                <div class="col-6 col-md-2 col-lg-auto rp-field-cell" id="rpWalkInWaiterCol">
                    <label class="form-label" for="rpWaiter">Waiter</label>
                    <select id="rpWaiter" class="form-select form-select-sm">
                        <option value="">Select…</option>
                        @foreach($waiters as $waiter)
                            <option value="{{ $waiter->name }}" @selected(($posSettings['resume_waiter_name'] ?? '') === $waiter->name)>{{ $waiter->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-md-2 col-lg-auto rp-field-cell" id="rpWalkInServeCol">
                    <label class="form-label" for="rpServeTime">Serve time</label>
                    <input type="time" id="rpServeTime" class="form-control form-control-sm"
                           value="{{ old('serve_time', $posSettings['resume_serve_time'] ?? '') }}">
                </div>
                <div class="col-6 col-md-2 col-lg-auto rp-field-cell" id="rpStaffBlock">
                    <label class="form-label" for="rpSaleMode">Sale for</label>
                    <select id="rpSaleMode" class="form-select form-select-sm">
                        <option value="customer" @selected(($posSettings['resume_sale_mode'] ?? 'customer') === 'customer')>Customer</option>
                        <option value="staff" @selected(($posSettings['resume_sale_mode'] ?? '') === 'staff')>Staff rate</option>
                    </select>
                </div>
                <div class="col-6 col-md-2 col-lg-auto rp-field-cell d-flex align-items-end" id="rpStaffGasCol">
                    <div class="form-check mb-0 py-1">
                        <input class="form-check-input" type="checkbox" id="rpStaffGas">
                        <label class="form-check-label small" for="rpStaffGas">Include gas</label>
                    </div>
                </div>
                <div class="col-6 col-md-3 col-lg-auto rp-field-cell rp-field-notes">
                    <label class="form-label" for="rpOrderNotes">Notes</label>
                    <input type="text" id="rpOrderNotes" class="form-control form-control-sm" maxlength="1000"
                           value="{{ old('order_notes', $posSettings['resume_order_notes'] ?? '') }}"
                           placeholder="Sirf record ke liye">
                </div>
                @if($posSettings['show_customer_section'] ?? true)
                    <div class="col-12 col-md-auto rp-field-cell" id="rpCreditBlock">
                        <div class="d-flex flex-wrap align-items-center gap-1">
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="rpCreditToggle">
                                <label class="form-check-label small text-danger fw-semibold" for="rpCreditToggle">Credit sale</label>
                            </div>
                            <input type="text" id="rpContactSearch" class="form-control form-control-sm" style="max-width:14rem;" placeholder="Search contact…" autocomplete="off">
                            <div id="rpSelectedContactWrap" class="d-none small px-2 py-1 rounded border bg-light d-inline-flex align-items-center gap-2">
                                <span id="rpSelectedContact"></span>
                                <button type="button" class="btn btn-sm btn-link text-danger p-0" id="rpClearContact">×</button>
                            </div>
                        </div>
                        <div id="rpContactDropdown" class="dropdown-menu show d-none border shadow-sm mt-1"></div>
                    </div>
                @endif
            </div>
        </div>

        <div class="rp-order-line-wrap d-none" id="rpOrderLinePanel">
            <div class="rp-order-line" id="rpOrderLine"></div>
        </div>
    </div>

    <div class="rp-body">
        <div class="rp-menu-panel">
            <div class="rp-menu-head">
                <div class="rp-menu-cats" id="rpMenuCats" role="tablist" aria-label="Menu categories"></div>
            </div>
            <div class="rp-menu-grid" id="rpMenuGrid"></div>
        </div>

        <aside class="rp-checkout">
            <div class="rp-checkout-head">
                <div class="rp-checkout-head-main">
                    <i class="bi bi-receipt-cutoff" aria-hidden="true"></i>
                    <span>Your order</span>
                    <span class="rp-cart-count" id="rpCartCount">0</span>
                </div>
                <button type="button" class="btn btn-sm rp-cart-view-btn" id="rpToggleCartView" title="Cart full view">
                    <i class="bi bi-arrows-fullscreen"></i>
                </button>
            </div>

            <div class="rp-cart-lines" id="rpCartLines"></div>
        </aside>
    </div>

    <div class="rp-pay-dock">
        <div class="rp-checkout-foot">
            <div class="rp-bill-summary">
                <div class="rp-bill-summary-head">Bill Summary</div>
                <div class="rp-total-row"><span>Items</span><span id="rpSumItems">0</span></div>
                <div class="rp-total-row"><span>Subtotal</span><span id="rpSumSubtotal">0.00</span></div>
                @if($posSettings['show_discount'] ?? true)
                    <div class="rp-total-row rp-total-row-adjust" id="rpDiscountRow">
                        <span class="rp-adjust-label">
                            Discount
                            <input type="number" id="rpBillDiscount" class="form-control form-control-sm rp-summary-pct"
                                   min="0" step="0.01" title="Bill discount %"
                                   value="{{ $posSettings['resume_bill_discount_percent'] ?? 0 }}">
                            <span class="rp-pct-sym">%</span>
                        </span>
                        <span id="rpSumDiscount">0.00</span>
                    </div>
                @endif
                <div class="rp-total-row grand"><span>Total</span><span id="rpSumGrand">0.00</span></div>
            </div>

            <div id="rpPaymentsBlock" class="rp-pay-method">
                <label class="form-label small mb-0">Payment</label>
                <select id="rpPayMethod" class="form-select form-select-sm">
                    <option value="cash">Cash</option>
                    <option value="card">Card</option>
                    <option value="bank">Bank</option>
                </select>
            </div>

            <div class="rp-actions">
                @if($posSettings['show_hold_button'] ?? true)
                    <button type="button" class="btn btn-outline-warning btn-sm" id="rpHoldBtn">
                        <i class="bi bi-send"></i> Hold &amp; Kitchen
                    </button>
                @endif
                <button type="button" class="btn btn-sm btn-rp-primary" id="rpPayBtn">
                    <i class="bi bi-credit-card"></i> Pay Now
                </button>
            </div>
        </div>
    </div>
</div>

<form id="rpSubmitForm" method="POST" action="{{ route('restaurant-pos.checkout') }}" class="d-none">
    @csrf
    <input type="hidden" name="type" value="sale">
    <input type="hidden" name="sale_mode" value="customer">
    <input type="hidden" name="staff_include_gas" value="0">
    <input type="hidden" name="customer_type" value="{{ $defaultCustomerType }}">
    <input type="hidden" name="resume_order_id" value="{{ $resumedOrder?->id ?? '' }}">
    <input type="hidden" name="is_credit" value="0">
    <input type="hidden" name="contact_id" value="">
    <input type="hidden" name="table_id" value="">
    <input type="hidden" name="guest_name" value="">
    <input type="hidden" name="room_no" value="">
    <input type="hidden" name="waiter_name" value="">
    <input type="hidden" name="order_notes" value="">
    <input type="hidden" name="serve_time" value="">
    <input type="hidden" name="items" value="">
    <input type="hidden" name="payments" value="">
    <input type="hidden" name="bill_tax_percent" value="0">
    <input type="hidden" name="bill_discount_percent" value="0">
</form>
@endsection

@section('scripts')
@php
    $receiptStub = str_replace('999999999', '__ID__', route('restaurant-pos.receipt', ['order' => 999999999]));
    $restaurantBootstrap = [
        'csrf' => csrf_token(),
        'products' => $productJs,
        'menuCategories' => $menuCategories,
        'contacts' => $contacts->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'phone' => $c->phone])->values(),
        'settings' => $posSettings,
        'resumeItems' => $resumeItems,
        'resumeOrderId' => $resumedOrder?->id,
        'pendingBillsDetail' => $pendingBillsDetail ?? [],
        'paidBillsDetail' => $paidBillsDetail ?? [],
        'messBillLabel' => \App\Models\PosOrder::MESS_BILL_LABEL,
        'gasRatePercent' => \App\Models\InventoryProduct::gasChargesRatePercent(),
        'routes' => [
            'checkout' => route('restaurant-pos.checkout'),
            'hold' => route('restaurant-pos.hold'),
            'sync' => route('restaurant-pos.sync'),
            'resume' => $resumeStub . '?ui=restaurant',
            'receipt' => $receiptStub,
        ],
    ];
@endphp
<script>
window.RESTAURANT_POS_BOOTSTRAP = @json($restaurantBootstrap);
</script>
<script src="{{ asset('js/restaurant-pos-app.js') }}?v=19"></script>
@endsection
