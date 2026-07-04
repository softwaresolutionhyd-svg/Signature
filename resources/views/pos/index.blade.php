@extends('layouts.admin')

@section('title', 'POS Restaurant')
@section('page-title', 'POS Restaurant')

@section('content')
    <style>
        #saleModeCustomer:checked + label {
            color: #fff !important;
            background-color: var(--bs-primary) !important;
            border-color: var(--bs-primary) !important;
            box-shadow: none;
        }

        #saleModeStaff:checked + label {
            color: #212529 !important;
            background-color: var(--bs-warning) !important;
            border-color: var(--bs-warning) !important;
            box-shadow: none;
        }

        #saleModeCustomer:not(:checked) + label,
        #saleModeStaff:not(:checked) + label {
            background-color: #fff;
        }

        .pos-app .pos-sale-for-row {
            display: flex;
            gap: 0.35rem;
            align-items: stretch;
            max-width: 420px;
        }

        .pos-app .pos-sale-for-row .btn {
            flex: 1 1 0;
            min-width: 0;
            padding: 0.35rem 0.45rem;
            font-size: 0.9rem;
            line-height: 1.25;
            font-weight: 600;
            text-align: center;
            white-space: normal;
        }

        .pos-app {
            font-size: 1.05rem;
        }

        .pos-app .pos-main-tabs > .tab-pane.active {
            height: calc(100vh - 118px);
            min-height: 0;
            overflow: hidden;
        }

        .pos-app #posHeldTabPane,
        .pos-app #posPaidTabPane {
            height: calc(100vh - 118px);
            min-height: 0;
            overflow-y: auto;
        }

        .pos-app .pos-main-tabs .nav-link {
            padding: 0.45rem 0.9rem;
            font-size: 0.92rem;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .pos-app .pos-compact .form-label {
            font-size: 0.92rem;
            margin-bottom: 0.2rem;
            font-weight: 600;
        }

        .pos-app .pos-compact .form-control,
        .pos-app .pos-compact .form-select,
        .pos-app #posSidebar .form-control,
        .pos-app #posSidebar .form-select {
            font-size: 0.98rem;
            min-height: calc(1.5em + 0.55rem + 2px);
            padding: 0.35rem 0.55rem;
        }

        .pos-app .pos-session-bar {
            font-size: 0.95rem;
        }

        .pos-app .pos-session-bar .btn {
            font-size: 0.9rem;
            padding: 0.28rem 0.55rem;
            line-height: 1.35;
        }

        .pos-app #posCartPanel > .card-body {
            padding: 0.55rem 0.75rem;
        }

        .pos-app #posCartTop .row {
            --bs-gutter-y: 0.4rem;
            --bs-gutter-x: 0.5rem;
        }

        .pos-app #posCartTop .mb-2 {
            margin-bottom: 0.4rem !important;
        }

        .pos-app .btn-group-sm > .btn {
            font-size: 0.9rem;
            padding: 0.28rem 0.55rem;
        }

        .pos-app #posCartTableWrap .table {
            font-size: 0.95rem;
            margin-bottom: 0;
            table-layout: fixed;
            width: 100%;
        }

        .pos-app #posCartTableWrap .pos-cart-col-product {
            width: 24%;
            min-width: 6.5rem;
            white-space: normal;
            word-break: break-word;
            line-height: 1.3;
        }

        .pos-app #posCartTableWrap .pos-cart-col-notes {
            width: 14%;
            min-width: 4.5rem;
        }

        .pos-app #posCartTableWrap .pos-cart-col-notes .form-control {
            font-size: 0.9rem;
            padding: 0.25rem 0.4rem;
        }

        .pos-app #posCartTableWrap .pos-cart-col-served {
            width: 9%;
            min-width: 4.75rem;
            white-space: nowrap;
        }

        .pos-app #posCartTableWrap .pos-cart-col-served .badge {
            font-size: 0.7rem;
            font-weight: 600;
        }

        .pos-app #posCartTableWrap .pos-cart-col-uom {
            width: 11%;
            min-width: 4.25rem;
        }

        .pos-app #posCartTableWrap .pos-cart-col-qty {
            width: 14%;
            min-width: 5.25rem;
        }

        .pos-app #posCartTableWrap .pos-cart-col-price,
        .pos-app #posCartTableWrap .pos-cart-col-total {
            width: 9%;
            white-space: nowrap;
        }

        .pos-app #posCartTableWrap .pos-cart-col-tax {
            width: 8%;
            min-width: 3.5rem;
        }

        .pos-app #posCartTableWrap .pos-cart-col-action {
            width: 1.75rem;
            padding-left: 0.15rem !important;
            padding-right: 0.15rem !important;
        }

        .pos-app #posCartTableWrap .table th,
        .pos-app #posCartTableWrap .table td {
            padding: 0.35rem 0.4rem;
            vertical-align: middle;
        }

        .pos-app #posCartTableWrap .table th {
            font-size: 0.92rem;
            font-weight: 700;
        }

        .pos-app #posSidebar .card {
            margin-bottom: 0.45rem !important;
        }

        .pos-app #posSidebar .card-body {
            padding: 0.55rem 0.7rem;
        }

        .pos-app #posSidebar .card-body h6 {
            font-size: 0.98rem;
            margin-bottom: 0.35rem;
        }

        .pos-app #posSidebar .small,
        .pos-app #posSidebar .form-check-label {
            font-size: 0.95rem !important;
        }

        .pos-app #posSidebarActions {
            flex-shrink: 0;
            padding-top: 0;
            margin-top: 0.45rem;
            border-top: none;
            background: transparent;
            gap: 0.5rem !important;
        }

        .pos-app .pos-act-btn {
            font-size: 0.98rem;
            font-weight: 700;
            padding: 0.58rem 0.75rem;
            border-radius: 0.65rem;
            border: none;
            color: #fff !important;
            letter-spacing: 0.01em;
            line-height: 1.25;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
            transition: transform 0.12s ease, box-shadow 0.12s ease, filter 0.12s ease;
        }

        .pos-app .pos-act-btn:hover:not(:disabled) {
            transform: translateY(-1px);
            filter: brightness(1.04);
        }

        .pos-app .pos-act-btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .pos-app .pos-act-btn:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .pos-app .pos-act-print {
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            color: #4338ca !important;
            border: 2px solid #a5b4fc !important;
            box-shadow: 0 2px 10px rgba(99, 102, 241, 0.12);
        }

        .pos-app .pos-act-print:hover:not(:disabled) {
            background: linear-gradient(180deg, #eef2ff 0%, #e0e7ff 100%);
            border-color: #818cf8 !important;
            box-shadow: 0 4px 14px rgba(99, 102, 241, 0.18);
        }

        .pos-app .pos-act-hold {
            background: linear-gradient(135deg, #fcd34d 0%, #f59e0b 50%, #d97706 100%);
            box-shadow: 0 6px 16px rgba(217, 119, 6, 0.28);
        }

        .pos-app .pos-act-hold:hover:not(:disabled) {
            box-shadow: 0 8px 20px rgba(217, 119, 6, 0.34);
        }

        .pos-app .pos-order-timeline {
            border: 1px solid var(--app-border, #dee2e6);
            border-radius: 0.65rem;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        .pos-app .pos-order-timeline-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.55rem 0.75rem;
            border-bottom: 1px solid var(--app-border, #dee2e6);
            font-size: 0.88rem;
        }
        .pos-app .pos-order-timeline-row:last-child {
            border-bottom: 0;
        }
        .pos-app .pos-order-timeline-row.is-done .pos-order-timeline-value {
            color: #198754;
            font-weight: 600;
        }
        .pos-app .pos-order-timeline-label {
            color: #6c757d;
            min-width: 9rem;
        }
        .pos-app .pos-order-timeline-value {
            text-align: right;
            font-weight: 600;
        }

        .pos-app .pos-act-pay {
            background: linear-gradient(135deg, #6ee7b7 0%, #10b981 45%, #047857 100%);
            box-shadow: 0 6px 18px rgba(5, 150, 105, 0.32);
        }

        .pos-app .pos-act-pay:hover:not(:disabled) {
            box-shadow: 0 10px 24px rgba(5, 150, 105, 0.38);
        }

        .pos-app .pos-act-credit {
            background: linear-gradient(135deg, #fda4af 0%, #e11d48 50%, #9f1239 100%);
            box-shadow: 0 6px 18px rgba(225, 29, 72, 0.28);
        }

        .pos-app .pos-act-credit:hover:not(:disabled) {
            box-shadow: 0 10px 24px rgba(225, 29, 72, 0.34);
        }

        .pos-app #posSidebar #addPaymentBtn {
            font-size: 0.92rem;
            padding: 0.28rem 0.55rem;
        }

        .pos-app #posSidebar hr {
            margin: 0.45rem 0;
        }

        .pos-contact-suggestions {
            position: fixed;
            z-index: 1065;
            background: #fff;
            border: 1px solid var(--bs-border-color, #dee2e6);
            border-radius: 0.375rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.12);
            max-height: 220px;
            overflow-y: auto;
            min-width: 12rem;
        }

        .pos-contact-suggestions .contact-option:hover {
            background: #f8f9fa;
        }

        .pos-app #posSidebar .fs-6 {
            font-size: 1.1rem !important;
        }

        #posCartTop {
            flex-shrink: 0;
        }

        #posSidebar {
            overflow: visible;
            align-items: stretch;
            justify-content: flex-start;
        }

        #posSidebarBody {
            flex: 0 0 auto;
            width: 100%;
            overflow: visible;
        }

        #posSaleTabPane.active {
            display: flex;
            flex-direction: column;
        }

        #posSaleTabPane.active > .pos-workspace {
            flex: 1 1 auto;
            min-height: 0;
        }

        .pos-workspace {
            height: 100%;
        }

        .pos-workspace > .col-md-8 {
            height: 100%;
        }

        #posCartPanel {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        #posSidebar {
            height: auto;
            display: flex;
            flex-direction: column;
        }

        .pos-workspace > .col-md-4 {
            height: auto;
            align-self: flex-start;
        }

        #posCartPanel > .card-body,
        #posSidebarBody > .card {
            min-height: 0;
        }

        #posCartPanel > .card-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .pos-app #posCartTableWrap {
            flex: 1 1 auto;
            min-height: 80px;
            overflow-y: auto;
            --pos-cart-ctrl-h: 1.75rem;
        }

        .pos-app #posCartTableWrap .form-control,
        .pos-app #posCartTableWrap .form-select {
            font-size: 0.92rem;
            height: var(--pos-cart-ctrl-h);
            min-height: var(--pos-cart-ctrl-h);
            padding: 0 0.35rem;
            line-height: 1.25;
        }

        .pos-app .pos-qty-stepper {
            width: 5.75rem;
            flex-wrap: nowrap;
            height: var(--pos-cart-ctrl-h);
        }

        .pos-app .pos-qty-stepper .btn {
            padding: 0;
            font-size: 0.9rem;
            line-height: 1;
            min-width: 1.25rem;
            width: 1.25rem;
            height: var(--pos-cart-ctrl-h);
            min-height: var(--pos-cart-ctrl-h);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .pos-app .pos-qty-stepper .pos-qty-input {
            padding: 0 0.12rem;
            font-size: 0.9rem;
            text-align: center;
            min-width: 0;
            height: var(--pos-cart-ctrl-h);
            min-height: var(--pos-cart-ctrl-h);
            line-height: 1.25;
            -moz-appearance: textfield;
        }

        .pos-app .pos-qty-stepper .pos-qty-input::-webkit-outer-spin-button,
        .pos-app .pos-qty-stepper .pos-qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .pos-app .pos-cart-rm-btn {
            width: var(--pos-cart-ctrl-h);
            height: var(--pos-cart-ctrl-h);
            min-height: var(--pos-cart-ctrl-h);
            padding: 0;
            font-size: 0.92rem;
            line-height: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .pos-app #posSidebar #paymentRows .row {
            --bs-gutter-y: 0.3rem;
            margin-bottom: 0.3rem !important;
        }

        .pos-app #posSidebar #paymentRows .btn {
            font-size: 0.92rem;
            padding: 0.2rem 0.45rem;
        }

        .pos-app .small {
            font-size: 0.92rem !important;
        }

        .pos-app #productSearchInput.form-control-sm {
            font-size: 1rem;
            padding: 0.45rem 0.6rem;
            min-height: calc(1.5em + 0.65rem + 2px);
        }

        .pos-app #productSearchDropdown .list-group-item,
        .pos-app #productSearchDropdown button {
            font-size: 0.95rem;
            padding: 0.45rem 0.65rem;
        }
    </style>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Checkout failed:</div>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            @if(session('last_pos_order_id') && ($posSettings['allow_bill_print'] ?? true))
                @php $lastReceiptId = session('last_pos_order_id'); @endphp
                <div class="mt-2 small">
                    <a href="{{ route('pos.receipt', $lastReceiptId) }}" class="alert-link fw-semibold">Open receipt / print</a>
                    <span class="text-secondary mx-1">·</span>
                    <a href="{{ route('pos.receipt', $lastReceiptId) }}?noprint=1" class="alert-link">View without auto-print</a>
                </div>
            @endif
        </div>
    @endif
    @if(session('error'))
        <div class="alert {{ session('pos_shortage_product_url') ? 'alert-warning' : 'alert-danger' }}">
            {{ session('error') }}
            @if(session('pos_shortage_product_url'))
                <div class="mt-2 small text-secondary">Kam stock wala component inventory mein adjust karne ke liye (optional):</div>
                <a href="{{ session('pos_shortage_product_url') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-dark mt-1">Inventory — product kholo</a>
            @endif
        </div>
    @endif

    @php
        $heldCount = (int) ($sessionPosStats['held_count'] ?? $heldOrders->count());
        $paidCount = (int) ($sessionPosStats['sales_count'] ?? $paidOrders->where('type', 'sale')->count());
        $todayLabel = now()->format('d M Y');
        $cashierName = auth()->user()->name ?? 'User';
    @endphp

    <div class="pos-app">
        <ul class="nav nav-tabs mb-1" id="posMainTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pos-sale-tab" data-bs-toggle="tab" data-bs-target="#posSaleTabPane" type="button" role="tab" aria-controls="posSaleTabPane" aria-selected="true">POS</button>
            </li>
            @if($posSettings['show_held_orders'] ?? true)
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pos-held-tab" data-bs-toggle="tab" data-bs-target="#posHeldTabPane" type="button" role="tab" aria-controls="posHeldTabPane" aria-selected="false">
                        PENDING ORDERS (<span id="posHeldTabCount">{{ $heldCount }}</span>)
                    </button>
                </li>
            @endif
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pos-paid-tab" data-bs-toggle="tab" data-bs-target="#posPaidTabPane" type="button" role="tab" aria-controls="posPaidTabPane" aria-selected="false">
                    PAID ORDERS TODAY (<span id="posPaidTabCount">{{ $paidCount }}</span>)
                </button>
            </li>
        </ul>

        <div class="tab-content pos-main-tabs" id="posMainTabContent">
            <div class="tab-pane fade show active" id="posSaleTabPane" role="tabpanel" aria-labelledby="pos-sale-tab" tabindex="0">
        <div class="row g-2 mb-0 pos-workspace">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm h-100" id="posCartPanel">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-1 mb-1 pos-session-bar">
                            <div class="small">
                                <span class="fw-semibold">{{ $todayLabel }}</span>
                                <span class="text-secondary">· {{ $cashierName }}</span>
                            </div>
                            <div class="d-flex flex-wrap gap-1">
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="collapse" data-bs-target="#sessionStatsBox" aria-expanded="false">
                                    Summary
                                </button>
                                @if($posSettings['show_cash_movements'] ?? true)
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse" data-bs-target="#posCashMovementBox" aria-expanded="false">
                                        Cash
                                    </button>
                                @endif
                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="collapse" data-bs-target="#closeSessionBox">Daily Closing</button>
                            </div>
                        </div>


                        @if(!empty($sessionPosStats))
                            @php $st = $sessionPosStats; @endphp
                            <div class="collapse mb-3" id="sessionStatsBox">
                                <div class="border rounded-3 p-3 bg-white small">
                                    <div class="fw-semibold mb-2">Is session ki activity (paid orders)</div>
                                    <div class="row g-2">
                                        <div class="col-sm-6 col-lg-4 text-secondary">Sales (count)</div>
                                        <div class="col-sm-6 col-lg-8 fw-semibold">{{ $st['sales_count'] }}</div>
                                        <div class="col-sm-6 col-lg-4 text-secondary">Sales total</div>
                                        <div class="col-sm-6 col-lg-8 fw-semibold">{{ fmt_num($st['sales_total'], 2) }}</div>
                                        <div class="col-sm-6 col-lg-4 text-secondary">Refunds / returns (count)</div>
                                        <div class="col-sm-6 col-lg-8 fw-semibold">{{ $st['refunds_count'] }}</div>
                                        <div class="col-sm-6 col-lg-4 text-secondary">Refunds total</div>
                                        <div class="col-sm-6 col-lg-8 fw-semibold">{{ fmt_num($st['refunds_total'], 2) }}</div>
                                        <div class="col-sm-6 col-lg-4 text-secondary">Credit sales (count)</div>
                                        <div class="col-sm-6 col-lg-8 fw-semibold text-danger">{{ $st['credit_sales_count'] }}</div>
                                        <div class="col-sm-6 col-lg-4 text-secondary">Credit sales amount</div>
                                        <div class="col-sm-6 col-lg-8 fw-semibold text-danger">{{ fmt_num($st['credit_sales_total'], 2) }}</div>
                                        <div class="col-12"><hr class="my-1"></div>
                                        <div class="col-sm-6 col-lg-4 text-secondary">Net cash (sales − refunds)</div>
                                        <div class="col-sm-6 col-lg-8">{{ fmt_num($st['payments_cash'], 2) }}</div>
                                        <div class="col-sm-6 col-lg-4 text-secondary">Net card</div>
                                        <div class="col-sm-6 col-lg-8">{{ fmt_num($st['payments_card'], 2) }}</div>
                                        <div class="col-sm-6 col-lg-4 text-secondary">Net bank</div>
                                        <div class="col-sm-6 col-lg-8">{{ fmt_num($st['payments_bank'], 2) }}</div>
                                        <div class="col-sm-6 col-lg-4 text-secondary">Pending bills</div>
                                        <div class="col-sm-6 col-lg-8 fw-semibold {{ $st['held_count'] > 0 ? 'text-warning' : 'text-success' }}">{{ $st['held_count'] }} @if(!$st['can_close_session'])<span class="text-danger">— close block</span>@endif</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="collapse" id="closeSessionBox">
                            <form method="POST" action="{{ route('pos.session.close') }}" class="row g-2 mb-3 p-3 bg-light rounded">
                                @csrf
                                @php
                                    $st = $sessionPosStats ?? [];
                                    $cashBreakdown = $sessionCashExpected ?? [];
                                    $todayCash = (float) ($st['payments_cash'] ?? 0);
                                    $todayBank = (float) ($st['payments_bank'] ?? 0);
                                    $todayCard = (float) ($st['payments_card'] ?? 0);
                                    $collectFromUser = round(
                                        $todayCash + (float) ($cashBreakdown['cash_in'] ?? 0) - (float) ($cashBreakdown['cash_out'] ?? 0),
                                        2
                                    );
                                @endphp
                                <div class="col-12">
                                    <div class="bg-white border rounded-3 p-3 mb-2 shadow-sm">
                                        <div class="fw-semibold mb-1">Daily Closing — {{ $todayLabel }}</div>
                                        <div class="small text-secondary mb-3">{{ $cashierName }} se aaj collect karna hai</div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="small text-secondary">Cash (aaj)</div>
                                                <div class="fs-4 fw-bold text-success">{{ fmt_num($todayCash, 2) }}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-secondary">Bank (aaj)</div>
                                                <div class="fs-4 fw-bold text-primary">{{ fmt_num($todayBank, 2) }}</div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="small text-secondary">Card (aaj)</div>
                                                <div class="fs-4 fw-bold text-info">{{ fmt_num($todayCard, 2) }}</div>
                                            </div>
                                        </div>
                                        <hr class="my-3">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                            <span class="fw-bold">{{ $cashierName }} se collect</span>
                                            <span class="fs-3 fw-bold text-danger">{{ fmt_num($collectFromUser, 2) }}</span>
                                        </div>
                                        <p class="text-muted small mb-0 mt-2">Cash sales + cash in − cash out. Day close par yeh entry date-wise save ho jati hai.</p>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label small fw-semibold">Note (optional)</label>
                                    <input type="text" name="note" class="form-control" value="{{ old('note') }}" placeholder="e.g. handover to manager">
                                </div>
                                <div class="col-md-4 d-grid align-items-end">
                                    <label class="form-label small d-none d-md-block">&nbsp;</label>
                                    @if(!empty($sessionPosStats) && !$sessionPosStats['can_close_session'])
                                        <button type="button" class="btn btn-secondary" disabled title="Pending bills clear karein">Save daily closing</button>
                                        <div class="small text-danger mt-1">Pehle sab pending bills Resume/Discard karein.</div>
                                    @else
                                        <button type="submit" class="btn btn-danger">Save daily closing</button>
                                    @endif
                                </div>
                            </form>
                            @if(!empty($recentDailyClosings) && $recentDailyClosings->isNotEmpty())
                                <div class="table-responsive mb-3">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th class="text-end">Cash</th>
                                                <th class="text-end">Bank</th>
                                                <th class="text-end">Collect</th>
                                                <th>Note</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentDailyClosings as $closing)
                                                <tr>
                                                    <td>{{ optional($closing->business_date ?? $closing->closed_at)->format('d M Y') }}</td>
                                                    <td class="text-end">{{ fmt_num($closing->closing_cash, 2) }}</td>
                                                    <td class="text-end">{{ fmt_num($closing->closing_bank, 2) }}</td>
                                                    <td class="text-end fw-semibold">{{ fmt_num($closing->amount_to_collect, 2) }}</td>
                                                    <td class="small text-secondary">{{ $closing->note }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        @if($posSettings['show_cash_movements'] ?? true)
                            <div class="collapse mb-3" id="posCashMovementBox">
                                <form method="POST" action="{{ route('pos.cash-movement') }}" class="row g-2 p-3 bg-light rounded">
                                    @csrf
                                    <div class="col-md-3">
                                        <select name="type" class="form-select form-select-sm">
                                            <option value="in">Cash In</option>
                                            <option value="out">Cash Out</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="amount" class="form-control form-control-sm" step="0.01" min="0.01" placeholder="Amount" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="reason" class="form-control form-control-sm" placeholder="Reason">
                                    </div>
                                    <div class="col-md-2 d-grid">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        @php
                            $defaultCustomerType = old('customer_type');
                            if (!$defaultCustomerType || $defaultCustomerType === 'booking') {
                                $defaultCustomerType = $posSettings['resume_customer_type'] ?? 'mess_use';
                                if ($defaultCustomerType === 'booking') {
                                    $defaultCustomerType = 'mess_use';
                                }
                            }
                            $isDefaultWalkIn = $defaultCustomerType === 'mess_use';
                            $isDefaultBooking = false;
                            $isDefaultMessBill = $defaultCustomerType === 'ast_offr';
                        @endphp

                        <div id="posCartTop" class="pos-compact">
                            <div class="row g-2 mb-2">
                                <div class="col-6 col-md-3">
                                    <label class="form-label" for="customerTypeSelect">Type of customer</label>
                                    <select id="customerTypeSelect" class="form-select form-select-sm">
                                        <option value="mess_use" @selected($defaultCustomerType === 'mess_use')>Walk-In</option>
                                        <option value="ast_offr" @selected($defaultCustomerType === 'ast_offr')>{{ \App\Models\PosOrder::MESS_BILL_LABEL }}</option>
                                    </select>
                                </div>
                                @if($posSettings['enable_tables'] ?? false)
                                    <div class="col-6 col-md-3" id="tableNoBlock">
                                        <label class="form-label" for="tableSelect">Table No</label>
                                        <select id="tableSelect" class="form-select form-select-sm">
                                            <option value="">Walk-in / No table</option>
                                            @foreach($tables as $t)
                                                <option value="{{ $t->id }}" @selected(($posSettings['resume_table_id'] ?? null) === (int) $t->id)>{{ $t->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-12">
                                    <label class="form-label" for="orderNotesInput">Notes</label>
                                    <input
                                        type="text"
                                        id="orderNotesInput"
                                        class="form-control form-control-sm"
                                        maxlength="1000"
                                        value="{{ old('order_notes', $posSettings['resume_order_notes'] ?? '') }}"
                                        placeholder="Sirf record ke liye — bill / kitchen par nahi jata"
                                        autocomplete="off"
                                    >
                                </div>
                            </div>

                            <div id="messUseFields" class="row g-2 mb-2 @if($isDefaultBooking || $isDefaultMessBill) d-none @endif">
                                <div class="col-md-3 @if(!$isDefaultWalkIn) d-none @endif" id="guestNameCol">
                                    <label class="form-label" for="guestNameInput" id="guestNameLabel">Guest Name</label>
                                    <input type="text" id="guestNameInput" class="form-control form-control-sm"
                                           maxlength="120"
                                           value="{{ old('guest_name', $posSettings['resume_guest_name'] ?? '') }}"
                                           placeholder="e.g. Ali">
                                </div>
                                <div class="col-md-3 @if(!$isDefaultWalkIn) d-none @endif" id="orderDateTimeCol">
                                    <label class="form-label" for="orderDateTimeInput">Date & Time</label>
                                    <input type="text" id="orderDateTimeInput" class="form-control form-control-sm" readonly
                                           value="{{ now()->format('d/m/Y, h:i:s A') }}">
                                </div>
                                <div class="col-md-3 @if(!$isDefaultWalkIn) d-none @endif" id="waiterNameCol">
                                    <label class="form-label" for="waiterNameInput">Waiter Select</label>
                                    <select id="waiterNameInput" class="form-select form-select-sm">
                                        <option value="">Select waiter...</option>
                                        @foreach($waiters as $waiter)
                                            <option value="{{ $waiter->name }}" @selected((string) old('waiter_name', $posSettings['resume_waiter_name'] ?? '') === (string) $waiter->name)>
                                                {{ $waiter->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 @if(!$isDefaultWalkIn) d-none @endif" id="serveTimeCol">
                                    <label class="form-label" for="serveTimeInput">Serve Time</label>
                                    <input
                                        type="time"
                                        id="serveTimeInput"
                                        class="form-control form-control-sm"
                                        value="{{ old('serve_time', $posSettings['resume_serve_time'] ?? '') }}"
                                    >
                                </div>
                            </div>

                            <script>
                            (function () {
                                function posSetVisible(el, visible) {
                                    if (!el) return;
                                    el.classList.toggle('d-none', !visible);
                                }

                                function posSyncCustomerTypeFields() {
                                    const sel = document.getElementById('customerTypeSelect');
                                    if (!sel) return;

                                    const type = sel.value || 'mess_use';
                                    const walkIn = type === 'mess_use';
                                    const booking = type === 'booking';
                                    const messBill = type === 'ast_offr';

                                    const hidden = document.getElementById('customerTypeHidden');
                                    if (hidden) hidden.value = type;

                                    posSetVisible(document.getElementById('bookingFields'), booking);
                                    posSetVisible(document.getElementById('messUseFields'), walkIn);
                                    posSetVisible(document.getElementById('guestNameCol'), walkIn);
                                    posSetVisible(document.getElementById('orderDateTimeCol'), walkIn);
                                    posSetVisible(document.getElementById('waiterNameCol'), walkIn);
                                    posSetVisible(document.getElementById('serveTimeCol'), walkIn);

                                    const tableBlock = document.getElementById('tableNoBlock');
                                    if (tableBlock) posSetVisible(tableBlock, !booking);

                                    const staffGas = document.getElementById('staffGasChargeBlock');
                                    if (staffGas) posSetVisible(staffGas, messBill);
                                }

                                function posOnCustomerTypeChange() {
                                    posSyncCustomerTypeFields();
                                    window.dispatchEvent(new CustomEvent('pos-customer-type-change'));
                                }

                                window.posSyncCustomerTypeFields = posSyncCustomerTypeFields;
                                window.posOnCustomerTypeChange = posOnCustomerTypeChange;

                                const sel = document.getElementById('customerTypeSelect');
                                if (sel) {
                                    sel.addEventListener('change', posOnCustomerTypeChange);
                                    posSyncCustomerTypeFields();
                                }
                            })();
                            </script>

                            @php
                                $posSaleModeDefault = old('sale_mode', $posSettings['resume_sale_mode'] ?? 'customer');
                            @endphp
                            <div class="mb-2">
                                <label class="form-label mb-1 d-block">Sale For</label>
                                <div class="pos-sale-for-row">
                                    <input type="radio" class="btn-check" name="saleModeToggle" id="saleModeCustomer" value="customer" autocomplete="off" @checked($posSaleModeDefault === 'customer')>
                                    <label class="btn btn-sm btn-outline-primary" for="saleModeCustomer">Customer Sale</label>

                                    <input type="radio" class="btn-check" name="saleModeToggle" id="saleModeStaff" value="staff" autocomplete="off" @checked($posSaleModeDefault === 'staff')>
                                    <label class="btn btn-sm btn-outline-warning" for="saleModeStaff">Staff Rate</label>

                                    @if($posSettings['show_refund_toggle'] ?? true)
                                        <button class="btn btn-sm btn-outline-secondary" id="toggleRefundBtn" type="button">Refund</button>
                                    @endif
                                </div>
                                <div id="staffGasChargeBlock" class="d-none mt-1">
                                    <div class="form-check mb-0 small">
                                        <input class="form-check-input" type="checkbox" id="staffIncludeGasCheckbox" @checked(old('staff_include_gas'))>
                                        <label class="form-check-label" for="staffIncludeGasCheckbox" id="staffIncludeGasLabel">Include Gas</label>
                                    </div>
                                    <div class="form-text small text-secondary" id="staffPurchaseItemsHelp" style="display:none;">Purchase items list dikhata hai — {{ \App\Models\PosOrder::MESS_BILL_LABEL }} price hamesha sirf cost par hoti hai (gas nahi).</div>
                                </div>
                            </div>

                            <div class="position-relative mb-2">
                                <label class="form-label" for="productSearchInput">Product</label>
                                <input type="text" id="productSearchInput" class="form-control form-control-sm" placeholder="Search product (↑↓ Enter · Esc)" autocomplete="off" spellcheck="false">
                                <div id="productSearchDropdown" class="position-absolute w-100 bg-white border rounded shadow-sm mt-1 d-none" role="listbox" style="z-index:1000;max-height:220px;overflow-y:auto;top:100%;left:0;"></div>
                            </div>
                        </div>
                        @php
                            $posShowDiscountCart = $posSettings['show_discount'] ?? true;
                            $posTaxModeCart = $posSettings['tax_mode'] ?? 'line';
                            $posBillTaxDefault = (float) ($posSettings['default_tax_rate'] ?? 0);
                            if (($posSettings['resume_bill_tax_percent'] ?? null) !== null) {
                                $posBillTaxDefault = (float) $posSettings['resume_bill_tax_percent'];
                            }
                            $posBillDiscountDefault = 0;
                            if (($posSettings['resume_bill_discount_percent'] ?? null) !== null) {
                                $posBillDiscountDefault = (float) $posSettings['resume_bill_discount_percent'];
                            }
                        @endphp
                        <div class="table-responsive mb-0" id="posCartTableWrap">
                            <table class="table table-sm align-middle mb-0">
                                <thead>
                                <tr>
                                    <th class="pos-cart-col-product">Product</th>
                                    <th class="pos-cart-col-uom">UOM</th>
                                    <th class="pos-cart-col-qty">Qty</th>
                                    <th class="pos-cart-col-price">Price</th>
                                    @if($posTaxModeCart === 'line')
                                        <th class="pos-cart-col-tax" title="Tax % on this line (after bill discount share).">Tax %</th>
                                    @endif
                                    <th class="pos-cart-col-total">Total</th>
                                    <th class="pos-cart-col-notes">Notes</th>
                                    <th class="pos-cart-col-served">Served</th>
                                    <th class="pos-cart-col-action"></th>
                                </tr>
                                </thead>
                                <tbody id="cartBody"></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-4" id="posSidebar">
                <div id="posSidebarBody">
                @if($posSettings['show_customer_section'] ?? true)
                {{-- Customer / Credit toggle --}}
                <div class="card border-0 shadow-sm mb-1">
                    <div class="card-body py-2">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="mb-0" id="posCustomerCardTitle">Customer</h6>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="creditToggle" role="switch">
                                <label class="form-check-label small fw-semibold text-danger" for="creditToggle">Credit Sale</label>
                            </div>
                        </div>
                        {{-- Contact search --}}
                        <div class="position-relative">
                            <input type="text" id="contactSearch" class="form-control form-control-sm"
                                placeholder="Search contact name or phone…" autocomplete="off">
                        </div>
                        <div id="selectedContactBox" class="mt-2 d-none">
                            <div class="d-flex align-items-center justify-content-between p-2 rounded"
                                style="background:#7c3aed12;border:1px solid #7c3aed30;">
                                <div>
                                    <div class="small fw-semibold" id="selContactName"></div>
                                    <div class="text-secondary" style="font-size:13px;" id="selContactPhone"></div>
                                </div>
                                <button type="button" class="btn btn-sm btn-link text-danger p-0" id="clearContact" title="Remove">✕</button>
                            </div>
                        </div>
                        {{-- Quick add contact inline --}}
                        <div id="quickAddContact" class="mt-2 d-none">
                            <div class="small text-secondary mb-1">Not found? Add quickly:</div>
                            <div class="row g-1">
                                <div class="col-7">
                                    <input type="text" id="newContactName" class="form-control form-control-sm" placeholder="Customer name *">
                                </div>
                                <div class="col-5">
                                    <input type="text" id="newContactPhone" class="form-control form-control-sm" placeholder="Phone">
                                </div>
                                <div class="col-12 mt-1">
                                    <button type="button" id="saveQuickContact" class="btn btn-sm btn-outline-primary w-100">Save & Select</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Payments --}}
                <div class="card border-0 shadow-sm mb-1" id="paymentCard">
                    <div class="card-body py-2">
                        <h6 class="mb-1">Payments</h6>
                        <div id="paymentRows"></div>
                        <button class="btn btn-sm btn-outline-primary mt-1" type="button" id="addPaymentBtn">Add Payment</button>
                    </div>
                </div>
                <div class="card border-0 shadow-sm mb-1">
                    <div class="card-body py-1">
                        <h6 class="mb-1">Summary</h6>
                        <div class="small text-secondary">Subtotal: <span id="sumSubtotal">0.00</span></div>
                        @if($posShowDiscountCart)
                            <div class="mt-1 mb-1" id="billDiscountInputRow">
                                <label class="form-label small mb-0" for="billDiscountPercentInput">Bill discount % <span class="text-secondary fw-normal">(on subtotal)</span></label>
                                <input type="number" class="form-control form-control-sm" id="billDiscountPercentInput" step="0.01" min="0" max="100" value="{{ $posBillDiscountDefault }}">
                            </div>
                            <div class="small text-secondary" id="sumDiscountRow">Discount: <span id="sumDiscount">0.00</span></div>
                        @endif
                        @if($posTaxModeCart === 'bill')
                            <div class="mt-2 mb-1" id="billTaxInputRow">
                                <label class="form-label small mb-0" for="billTaxPercentInput">Bill tax % <span class="text-secondary fw-normal">(on net after discounts)</span></label>
                                <input type="number" class="form-control form-control-sm" id="billTaxPercentInput" step="0.01" min="0" max="100" value="{{ $posBillTaxDefault }}">
                            </div>
                        @endif
                        @if($posTaxModeCart !== 'off')
                            <div class="small text-secondary" id="sumTaxRow">Tax: <span id="sumTax">0.00</span></div>
                        @endif
                        <hr class="my-2">
                        <h6 class="mb-0 fw-bold">Grand Total: <span id="sumGrand">0.00</span></h6>
                    </div>
                </div>

                <div id="posSidebarActions" class="d-grid gap-1">
                    @if($posSettings['allow_bill_print'] ?? true)
                        <button class="pos-act-btn pos-act-print" id="printDraftBillBtn" type="button">Print Bill</button>
                    @endif
                    @if(($posSettings['show_hold_button'] ?? true) || ($posSettings['hold_only'] ?? false))
                        <button class="pos-act-btn pos-act-hold" id="holdBtn" type="button">Order Hold &amp; Prepare</button>
                    @endif
                    <button class="pos-act-btn pos-act-pay" id="checkoutBtn" type="button">Pay Now</button>
                </div>
                </div>
                <form id="submitForm" method="POST" action="{{ route('pos.checkout') }}" class="d-none">
                    @csrf
                    <input type="hidden" name="type"       id="orderType"    value="sale">
                    <input type="hidden" name="sale_mode"  id="saleModeInput" value="{{ $posSaleModeDefault }}">
                    <input type="hidden" name="staff_include_gas" id="staffIncludeGasHidden" value="{{ old('staff_include_gas') ? '1' : '0' }}">
                    <input type="hidden" name="customer_type" id="customerTypeHidden" value="{{ $defaultCustomerType }}">
                    <input type="hidden" name="resume_order_id" id="resumeOrderIdInput" value="{{ old('resume_order_id', $resumedOrder?->id ?? '') }}">
                    <input type="hidden" name="table_id"   id="tableIdInput" value="">
                    <input type="hidden" name="guest_name" id="guestNameHidden" value="">
                    <input type="hidden" name="room_no" id="roomNoHidden" value="">
                    <input type="hidden" name="waiter_name" id="waiterNameHidden" value="">
                    <input type="hidden" name="order_notes" id="orderNotesHidden" value="">
                    <input type="hidden" name="serve_time" id="serveTimeHidden" value="">
                    <input type="hidden" name="items"      id="itemsJson">
                    <input type="hidden" name="payments"   id="paymentsJson">
                    <input type="hidden" name="bill_tax_percent" id="billTaxPercentHidden" value="0">
                    <input type="hidden" name="bill_discount_percent" id="billDiscountPercentHidden" value="0">
                    <input type="hidden" name="is_credit"  id="isCreditInput" value="0">
                    <input type="hidden" name="contact_id" id="contactIdInput" value="">
                    <input type="hidden" name="cash_tendered" id="cashTenderedInput" value="">
                    <input type="hidden" name="cash_change" id="cashChangeInput" value="">
                </form>
            </div>
        </div>
            </div>

        @if($posSettings['show_held_orders'] ?? true)
            <div class="tab-pane fade" id="posHeldTabPane" role="tabpanel" aria-labelledby="pos-held-tab" tabindex="0">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h6 class="mb-1">Pending Bills</h6>
                        @if($heldCount > 0)
                            <p class="small text-secondary mb-2 pos-held-count-note">{{ $heldCount }} {{ $heldCount === 1 ? 'pending bill hai' : 'pending bills hain' }}</p>
                        @else
                            <p class="small text-secondary mb-2 pos-held-count-note d-none"></p>
                        @endif
                        @if(!empty($sessionPosStats) && $sessionPosStats['held_count'] > 0)
                            <div class="alert alert-warning py-2 small mb-3">
                                Jab tak <strong>{{ $sessionPosStats['held_count'] }}</strong> pending bill(s) maujood hain, daily closing <strong>nahi</strong> ho sakti. Resume kar ke pay karein ya trash se hata dein.
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead><tr><th>Order</th><th>Type</th><th>Table / Room</th><th>Guest</th><th>Waiter</th><th>Status</th><th>Items</th><th>Total</th><th>Created</th><th class="text-end">Actions</th></tr></thead>
                                <tbody id="posPendingBillsBody">
                                @forelse($heldOrders as $h)
                                    @php
                                        $tableRoomParts = [];
                                        if (($posSettings['enable_tables'] ?? false) && $h->table) {
                                            $tableRoomParts[] = $h->table->name;
                                        }
                                        if ($h->room_no) {
                                            $tableRoomParts[] = $h->room_no;
                                        }
                                        $isBooking = $h->customerTypeKey() === 'booking';
                                        $isAstOffr = $h->customerTypeKey() === 'ast_offr';
                                    @endphp
                                    <tr data-pending-order-id="{{ $h->id }}">
                                        <td>
                                            {{ $h->order_no }}
                                            @if($h->isFromOrderTaker())
                                                <span class="badge text-bg-info ms-1">Order Taker</span>
                                                @php $orderAt = $h->ready_for_pos_at ?? $h->created_at; @endphp
                                                @if($orderAt)
                                                    <div class="small text-secondary">Order {{ $orderAt->format('H:i') }}</div>
                                                @endif
                                                @if(trim((string) ($h->serve_time ?? '')) !== '')
                                                    <div class="small text-secondary">Serve {{ $h->serve_time }}</div>
                                                @endif
                                            @endif
                                            @if($h->kitchen_completed_at)
                                                <div class="small text-success">Served {{ $h->kitchen_completed_at->format('H:i') }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($isBooking)
                                                <span class="badge text-bg-primary">In-House</span>
                                            @elseif($isAstOffr)
                                                <span class="badge text-bg-info">{{ \App\Models\PosOrder::MESS_BILL_LABEL }}</span>
                                            @else
                                                <span class="badge text-bg-secondary">Walk-In</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($tableRoomParts !== [])
                                                {{ implode(' / ', $tableRoomParts) }}
                                            @else
                                                <span class="text-secondary">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $h->guest_name ?: '—' }}</td>
                                        <td>{{ $h->waiter_name ?: '—' }}</td>
                                        <td>
                                            <span class="badge {{ $h->pendingKitchenStatusBadgeClass() }}">{{ $h->pendingKitchenStatusLabel() }}</span>
                                        </td>
                                        <td>{{ $h->items_count }}</td>
                                        <td>{{ fmt_num((float) $h->grand_total, 2) }}</td>
                                        <td>{{ optional($h->created_at)->format('Y-m-d H:i') }}</td>
                                        <td class="text-end text-nowrap">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-order-details="{{ $h->id }}" title="Order details">
                                                <i class="bi bi-list-check"></i>
                                            </button>
                                            <a href="{{ route('pos.resume', $h) }}" class="btn btn-sm btn-outline-secondary">Resume</a>
                                            <form method="POST" action="{{ route('pos.hold.discard', $h) }}" class="d-inline ms-1" onsubmit="return confirm('Discard this pending bill? It cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Discard hold"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="text-secondary">No pending bills.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

            <div class="tab-pane fade" id="posPaidTabPane" role="tabpanel" aria-labelledby="pos-paid-tab" tabindex="0">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                            <div>
                                <h6 class="mb-0">Paid / Complete Bills</h6>
                                <p class="small text-secondary mb-0">Is session ki paid orders — receipt dubara dekh sakte hain.</p>
                            </div>
                            @if(!empty($sessionPosStats))
                                <div class="small fw-semibold text-success">
                                    Total sales: {{ fmt_num($sessionPosStats['sales_total'], 2) }}
                                </div>
                            @endif
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th>Order</th>
                                        <th>Type</th>
                                        <th>Table / Room</th>
                                        <th>Guest</th>
                                        <th>Payment</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Paid</th>
                                        <th class="text-end">Receipt / Details</th>
                                    </tr>
                                </thead>
                                <tbody id="posPaidBillsBody">
                                @forelse($paidOrders as $p)
                                    @php
                                        $tableRoomParts = [];
                                        if (($posSettings['enable_tables'] ?? false) && $p->table) {
                                            $tableRoomParts[] = $p->table->name;
                                        }
                                        if ($p->room_no) {
                                            $tableRoomParts[] = $p->room_no;
                                        }
                                        $isBooking = $p->customerTypeKey() === 'booking';
                                        $isAstOffr = $p->customerTypeKey() === 'ast_offr';
                                        $isRefund = $p->type === 'refund';
                                        $payMethods = $p->payments
                                            ->pluck('method')
                                            ->map(fn ($m) => ucfirst((string) $m))
                                            ->unique()
                                            ->values();
                                    @endphp
                                    <tr data-paid-order-id="{{ $p->id }}" @if((int) session('last_pos_order_id') === (int) $p->id) class="table-success"@endif>
                                        <td class="fw-semibold">{{ $p->order_no }}</td>
                                        <td>
                                            @if($isRefund)
                                                <span class="badge text-bg-danger">Refund</span>
                                            @elseif($isAstOffr)
                                                <span class="badge text-bg-info">{{ \App\Models\PosOrder::MESS_BILL_LABEL }}</span>
                                            @elseif($p->is_credit)
                                                <span class="badge text-bg-warning text-dark">Credit</span>
                                            @elseif($isBooking)
                                                <span class="badge text-bg-primary">In-House</span>
                                            @else
                                                <span class="badge text-bg-secondary">Walk-In</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($tableRoomParts !== [])
                                                {{ implode(' / ', $tableRoomParts) }}
                                            @else
                                                <span class="text-secondary">—</span>
                                            @endif
                                        </td>
                                        <td>{{ $p->guest_name ?: '—' }}</td>
                                        <td>
                                            @if($isAstOffr)
                                                {{ \App\Models\PosOrder::MESS_BILL_LABEL }}
                                            @elseif($p->is_credit)
                                                Credit
                                            @elseif($payMethods->isNotEmpty())
                                                {{ $payMethods->implode(', ') }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>{{ $p->items_count }}</td>
                                        <td class="fw-semibold">{{ fmt_num((float) $p->grand_total, 2) }}</td>
                                        <td class="text-nowrap small">{{ optional($p->paid_at)->format('H:i') }}</td>
                                        <td class="text-end text-nowrap">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-order-details="{{ $p->id }}" title="Order details">
                                                <i class="bi bi-list-check"></i>
                                            </button>
                                            <a href="{{ route('pos.receipt', $p) }}" class="btn btn-sm btn-outline-primary" target="_blank" title="Receipt">
                                                <i class="bi bi-receipt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" class="text-secondary">Abhi is session mein koi paid bill nahi.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="contactDropdown" class="pos-contact-suggestions d-none" role="listbox" aria-label="Contact suggestions"></div>

        {{-- Cash tender: received amount & change (single cash payment) --}}
        <div class="modal fade" id="posPayModal" tabindex="-1" aria-labelledby="posPayModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="posPayModalLabel">Payment</h5>
                        <button type="button" class="btn-close" data-pay-modal-close aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-2">
                        <div class="rounded-3 p-3 mb-3" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                            <div class="small text-secondary">Amount due / کل رقم</div>
                            <div class="fs-3 fw-bold text-success" id="payModalDue">0.00</div>
                        </div>
                        <label class="form-label fw-semibold" for="payModalReceived">Received from customer / گاہک سے ملے</label>
                        <input type="number" class="form-control form-control-lg" id="payModalReceived" step="0.01" min="0" placeholder="0.00" autocomplete="off">
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary pay-quick" data-add="exact">Exact</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary pay-quick" data-add="50">+50</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary pay-quick" data-add="100">+100</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary pay-quick" data-add="500">+500</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary pay-quick" data-add="1000">+1000</button>
                        </div>
                        <div class="rounded-3 p-3 mt-3" style="background:#fffbeb;border:1px solid #fde68a;">
                            <div class="small text-secondary mb-1">Change to return / واپس دینا ہے</div>
                            <div class="fs-4 fw-bold text-warning" id="payModalChange">0.00</div>
                        </div>
                        <div id="payModalErr" class="alert alert-danger py-2 small mb-0 mt-3 d-none"></div>
                    </div>
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-outline-secondary" data-pay-modal-close>Cancel</button>
                        <button type="button" class="btn btn-success btn-lg px-4" id="payModalConfirm">Confirm &amp; Pay</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="posPaidBillModal" tabindex="-1" aria-labelledby="posPaidBillModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold" id="posPaidBillModalLabel">Order Details</h5>
                        <button type="button" class="btn-close" data-paid-bill-modal-close aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-2" id="posPaidBillModalBody"></div>
                    <div class="modal-footer border-0 pt-0">
                        <a href="#" class="btn btn-outline-primary d-none" id="posPaidBillReceiptLink" target="_blank" rel="noopener">
                            <i class="bi bi-receipt me-1"></i> Open Receipt
                        </a>
                        <button type="button" class="btn btn-secondary" data-paid-bill-modal-close>Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
        @php
            $productJs = $products->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'sku' => $p->sku,
                    'barcode' => $p->barcode,
                    'uom' => $p->uom,
                    'price' => (float) $p->price,
                    'cost' => (float) $p->cost,
                    'gas_charges' => (float) $p->gasChargesAmount(),
                    'qty_on_hand' => (float) $p->qty_on_hand,
                    'reorder_level' => (float) $p->reorder_level,
                    'for_pos' => (bool) ($p->for_pos ?? false),
                    'for_purchase' => (bool) ($p->for_purchase ?? true),
                    'has_active_bom' => (bool) ($p->manufacturing_boms_exists ?? false),
                    'uoms' => collect($p->uomsForForms())->map(fn ($row) => [
                        'uom' => $row['uom'],
                        'factor' => (float) $row['factor'],
                    ])->values()->all(),
                ];
            })->values();
            $resumeItems = collect($resumedOrder?->items ?? [])->map(function ($i) {
                return [
                    'product_id' => $i->product_id,
                    'uom' => $i->uom,
                    'qty' => (float) $i->qty,
                    'unit_price' => (float) $i->unit_price,
                    'discount_percent' => (float) $i->discount_percent,
                    'tax_percent' => (float) $i->tax_percent,
                    'notes' => (string) ($i->notes ?? ''),
                    'kitchen_served' => $i->isKitchenServed(),
                    'kitchen_pending' => (bool) $i->kitchen_pending,
                ];
            })->values();
            $checkoutRetry = session('pos_checkout_retry');
            $posGasRatePercent = \App\Models\InventoryProduct::gasChargesRatePercent();
            $posSaleModeDefault = old('sale_mode', $posSettings['resume_sale_mode'] ?? 'customer');
            $pendingBillsJs = $heldOrders->map(fn ($h) => [
                'id' => $h->id,
                'order_no' => $h->order_no,
                'customer_type' => $h->customerTypeKey(),
                'guest_name' => $h->guest_name,
                'room_no' => $h->room_no,
            ])->values();
            $posRouteResumeStub = str_replace('999999999', '__ID__', route('pos.resume', ['order' => 999999999]));
            $posRouteDiscardStub = str_replace('999999999', '__ID__', route('pos.hold.discard', ['order' => 999999999]));
            $posReceiptRouteStub = str_replace('999999999', '__ID__', route('pos.receipt', ['order' => 999999999]));
            $posSyncRoute = route('pos.sync');
        @endphp
        <script>
            const products = @json($productJs);
            const resumeItems = @json($resumeItems);
            const checkoutRetry = @json($checkoutRetry);
            const pendingBills = @json($pendingBillsJs);
            const paidBillsDetail = @json($paidBillsDetail ?? collect());
            const pendingBillsDetail = @json($pendingBillsDetail ?? collect());
            const ordersDetailById = {};
            [...paidBillsDetail, ...pendingBillsDetail].forEach((entry) => {
                if (entry && entry.id != null) ordersDetailById[Number(entry.id)] = entry;
            });
            const posContacts = @json($contacts->map(fn ($c) => ['id' => $c->id, 'name' => $c->name, 'phone' => $c->phone])->values());
            const posMessBillLabel = @json(\App\Models\PosOrder::MESS_BILL_LABEL);
            const posGasRatePercent = @json($posGasRatePercent);
            const posTablesEnabled = @json($posSettings['enable_tables'] ?? false);
            const resumeTableId = @json($posSettings['resume_table_id'] ?? null);
            const posShowDiscount = @json($posSettings['show_discount'] ?? true);
            const posTaxMode = @json($posSettings['tax_mode'] ?? 'line');
            const posDefaultLineTax = @json((float) ($posSettings['default_tax_rate'] ?? 0));
            const posAllowBillPrint = @json($posSettings['allow_bill_print'] ?? true);
            const posResumeRouteStub = @json($posRouteResumeStub);
            const posDiscardRouteStub = @json($posRouteDiscardStub);
            const posReceiptRouteStub = @json($posReceiptRouteStub);
            const posSyncRoute = @json($posSyncRoute);
            const resumeSaleMode = @json($posSettings['resume_sale_mode'] ?? null);
            const cart = [];
            const payments = [];
            let orderType = 'sale';
            let saleMode = @json($posSaleModeDefault);
            let staffIncludeGas = document.getElementById('staffIncludeGasCheckbox')?.checked || false;
            let autoPaymentAmount = true;

            const productSearchInput = document.getElementById('productSearchInput');
            const productSearchDropdown = document.getElementById('productSearchDropdown');
            const cartBody = document.getElementById('cartBody');
            const paymentRows = document.getElementById('paymentRows');
            const tableNoBlock = document.getElementById('tableNoBlock');
            const tableSelect = document.getElementById('tableSelect');
            const saleModeToggleInputs = document.querySelectorAll('input[name="saleModeToggle"]');
            const saleModeInput = document.getElementById('saleModeInput');
            const staffGasChargeBlock = document.getElementById('staffGasChargeBlock');
            const staffIncludeGasCheckbox = document.getElementById('staffIncludeGasCheckbox');
            const staffIncludeGasLabel = document.getElementById('staffIncludeGasLabel');
            const staffIncludeGasHidden = document.getElementById('staffIncludeGasHidden');
            const staffPurchaseItemsHelp = document.getElementById('staffPurchaseItemsHelp');
            const customerTypeSelect = document.getElementById('customerTypeSelect');
            const customerTypeHidden = document.getElementById('customerTypeHidden');
            const messUseFields = document.getElementById('messUseFields');
            const bookingFields = document.getElementById('bookingFields');
            const posTimezone = @json(config('app.timezone', 'Asia/Karachi'));
            const orderDateTimeInput = document.getElementById('orderDateTimeInput');
            const orderDateTimeCol = document.getElementById('orderDateTimeCol');
            const checkedInRoomSelect = document.getElementById('checkedInRoomSelect');
            const guestNameInput = document.getElementById('guestNameInput');
            const waiterNameInput = document.getElementById('waiterNameInput');
            const orderNotesInput = document.getElementById('orderNotesInput');
            const waiterNameCol = document.getElementById('waiterNameCol');
            const serveTimeInput = document.getElementById('serveTimeInput');
            const serveTimeCol = document.getElementById('serveTimeCol');
            const guestNameCol = document.getElementById('guestNameCol');
            const guestNameLabel = document.getElementById('guestNameLabel');

            function setElVisible(el, visible) {
                if (!el) return;
                el.classList.toggle('d-none', !visible);
            }

            function selectedCustomerType() {
                const value = customerTypeSelect?.value || 'mess_use';
                if (value === 'booking' || value === 'ast_offr' || value === 'mess_use') {
                    return value;
                }
                return 'mess_use';
            }

            function isWalkInCustomerType(customerType) {
                return customerType === 'mess_use' || customerType === 'ast_offr';
            }

            function customerTypeLabel(customerType) {
                if (customerType === 'booking') return 'In-House';
                if (customerType === 'ast_offr') return posMessBillLabel;
                return 'Walk-In';
            }

            function formatPosDateTime(date = new Date()) {
                try {
                    return date.toLocaleString('en-PK', {
                        timeZone: posTimezone,
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true,
                    });
                } catch (_) {
                    const pad = (n) => String(n).padStart(2, '0');
                    return `${pad(date.getDate())}/${pad(date.getMonth() + 1)}/${date.getFullYear()}, ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
                }
            }

            function updateOrderDateTime() {
                if (!orderDateTimeInput) return;
                orderDateTimeInput.value = formatPosDateTime();
            }

            function syncCustomerTypeUi() {
                if (typeof window.posSyncCustomerTypeFields === 'function') {
                    window.posSyncCustomerTypeFields();
                    return;
                }

                const customerType = selectedCustomerType();
                const walkIn = customerType === 'mess_use';
                const booking = customerType === 'booking';

                if (customerTypeHidden) customerTypeHidden.value = customerType;

                setElVisible(bookingFields, booking);
                if (tableNoBlock) setElVisible(tableNoBlock, !booking);
                setElVisible(messUseFields, walkIn);
                setElVisible(guestNameCol, walkIn);
                setElVisible(orderDateTimeCol, walkIn);
                setElVisible(waiterNameCol, walkIn);
                setElVisible(serveTimeCol, walkIn);

                if (guestNameLabel) {
                    guestNameLabel.textContent = walkIn ? 'Guest Name' : 'Guest name';
                }

                if (walkIn) {
                    updateOrderDateTime();
                }
            }

            function officerDisplayName() {
                return (selName?.textContent || '').trim();
            }

            function resolvedWalkInGuestName(customerType) {
                if (customerType === 'ast_offr') {
                    return officerDisplayName();
                }
                return (guestNameInput?.value || '').trim();
            }

            function parseRoomNos(roomNo) {
                return String(roomNo || '').split(',').map(s => s.trim()).filter(Boolean);
            }

            function roomNumbersOverlap(left, right) {
                const leftRooms = parseRoomNos(left);
                const rightRooms = parseRoomNos(right);
                if (!leftRooms.length || !rightRooms.length) return false;
                return leftRooms.some(leftRoom => rightRooms.some(rightRoom =>
                    leftRoom.localeCompare(rightRoom, undefined, { sensitivity: 'accent' }) === 0
                ));
            }

            function findGuestPendingBill(customerType, guestName, roomNo) {
                const resumeId = Number(document.getElementById('resumeOrderIdInput')?.value || 0);
                const guest = String(guestName || '').trim();
                const room = String(roomNo || '').trim();

                return pendingBills.find((pending) => {
                    if (resumeId && Number(pending.id) === resumeId) return false;
                    const pendingType = pending.customer_type === 'booking'
                        ? 'booking'
                        : (pending.customer_type === 'ast_offr' ? 'ast_offr' : 'mess_use');
                    if (customerType === 'booking' && pendingType === 'booking') {
                        return roomNumbersOverlap(room, pending.room_no);
                    }
                    if (isWalkInCustomerType(customerType) && customerType === pendingType) {
                        return guest !== '' && guest.localeCompare(String(pending.guest_name || '').trim(), undefined, { sensitivity: 'accent' }) === 0;
                    }
                    return false;
                }) || null;
            }

            function fmtMoney(n) {
                if (!Number.isFinite(n)) return '0';
                let s = n.toFixed(2);
                if (s.includes('.')) s = s.replace(/\.?0+$/, '');
                return s === '-0' ? '0' : s;
            }
            function fmtQty(n) {
                if (!Number.isFinite(n)) return '0';
                let s = n.toFixed(4);
                if (s.includes('.')) s = s.replace(/\.?0+$/, '');
                return s === '-0' ? '0' : s;
            }

            function gasChargeForProduct(product) {
                const cost = Number(product?.cost || 0);
                if (posGasRatePercent > 0 && cost > 0) {
                    return Math.round(cost * (posGasRatePercent / 100) * 100) / 100;
                }
                return Math.max(0, Number(product?.gas_charges || 0));
            }

            function staffUnitPrice(product) {
                const cost = Number(product?.cost || 0);
                if (isMessBillCustomer()) {
                    return Math.round((cost + Number.EPSILON) * 100) / 100;
                }
                const gas = gasChargeForProduct(product);
                return Math.round((cost + gas + Number.EPSILON) * 100) / 100;
            }

            function isMessBillCustomer() {
                return selectedCustomerType() === 'ast_offr';
            }

            function isMessBillPurchaseItems() {
                return isMessBillCustomer() && (staffIncludeGasCheckbox?.checked ?? staffIncludeGas);
            }

            function baseUnitPriceForProduct(product) {
                if (isMessBillCustomer()) {
                    return Math.round((Number(product?.cost || 0) + Number.EPSILON) * 100) / 100;
                }
                return saleMode === 'staff'
                    ? staffUnitPrice(product)
                    : Number(product?.price || 0);
            }

            function unitPriceForProductUom(product, uomCode) {
                const strict = unitPriceForProductUomStrict(product, uomCode);
                if (strict != null) {
                    return strict;
                }
                const basePrice = baseUnitPriceForProduct(product);
                return Math.round(basePrice * 100) / 100;
            }

            function convertUnitPriceForUomChange(product, currentPrice, fromUom, toUom) {
                const oldFactor = factorForUom(product, fromUom);
                const newFactor = factorForUom(product, toUom);
                const price = Number(currentPrice);
                if (oldFactor != null && newFactor != null && oldFactor > 0 && Number.isFinite(price)) {
                    return Math.round(price * (newFactor / oldFactor) * 100) / 100;
                }
                return unitPriceForProductUom(product, toUom);
            }

            function currentDefaultUnitPrice(product) {
                return unitPriceForProductUom(product, product?.uom);
            }

            function syncSaleModeUi() {
                const isStaff = saleMode === 'staff';
                const messBill = isMessBillCustomer();
                staffGasChargeBlock?.classList.toggle('d-none', !messBill);
                if (staffPurchaseItemsHelp) {
                    staffPurchaseItemsHelp.style.display = messBill ? '' : 'none';
                }
                if (staffIncludeGasLabel) {
                    staffIncludeGasLabel.textContent = messBill ? 'Purchase Items' : 'Include Gas';
                }
                if (messBill) {
                    staffIncludeGas = staffIncludeGasCheckbox?.checked ?? staffIncludeGas;
                } else if (isStaff) {
                    staffIncludeGas = true;
                    if (staffIncludeGasCheckbox) staffIncludeGasCheckbox.checked = true;
                } else if (staffIncludeGasCheckbox) {
                    staffIncludeGasCheckbox.checked = false;
                    staffIncludeGas = false;
                }
                if (staffIncludeGasHidden) {
                    if (messBill) {
                        staffIncludeGasHidden.value = '0';
                    } else if (isStaff) {
                        staffIncludeGasHidden.value = '1';
                    } else {
                        staffIncludeGasHidden.value = '0';
                    }
                }
                const billDiscInp = document.getElementById('billDiscountPercentInput');
                if (billDiscInp) {
                    billDiscInp.readOnly = isStaff;
                    if (isStaff) billDiscInp.value = '0';
                }
            }

            function lineQtyBase(p, r) {
                if (!p || !r) return null;
                const f = factorForUom(p, r.uom);
                const q = Number(r.qty);
                if (f == null || !Number.isFinite(q) || q <= 0) return null;
                return q * f;
            }

            function totalBaseForProductExcludingRow(productId, excludeIdx) {
                let sum = 0;
                cart.forEach((r, idx) => {
                    if (excludeIdx !== undefined && idx === excludeIdx) return;
                    if (Number(r.product_id) !== Number(productId)) return;
                    const p = products.find(x => Number(x.id) === Number(productId));
                    const b = lineQtyBase(p, r);
                    if (b != null) sum += b;
                });
                return sum;
            }

            /** @returns {string|null} Block message or low-stock warning; null if OK */
            function purchaseStockCheckMessage(p, row, excludeIdx) {
                if (!p || !p.for_purchase || p.has_active_bom) return null;
                const avail = Number(p.qty_on_hand);
                if (!Number.isFinite(avail)) return null;
                const need = lineQtyBase(p, row);
                if (need == null) return null;
                const totalNeed = totalBaseForProductExcludingRow(p.id, excludeIdx) + need;
                if (totalNeed > avail + 1e-6) {
                    return 'Stock nahi: ' + p.name + ' — zaroorat ~' + fmtQty(totalNeed) + ' ' + (p.uom || '') + ' (base), maujood ' + fmtQty(avail) + '.';
                }
                const rl = Number(p.reorder_level);
                if (rl > 0 && avail <= rl) {
                    return 'Low stock: ' + p.name + ' — maujood ' + fmtQty(avail) + ' ' + (p.uom || '') + ', reorder ' + fmtQty(rl) + '+.';
                }
                return null;
            }

            function productStockBadgeHtml(p) {
                if (!p.for_purchase || p.has_active_bom) return '';
                const q = Number(p.qty_on_hand);
                if (!Number.isFinite(q)) return '';
                if (q <= 0) return ' <span class="text-danger fw-semibold">· Out of stock</span>';
                const rl = Number(p.reorder_level);
                if (rl > 0 && q <= rl) return ' <span class="text-warning fw-semibold">· Low stock</span>';
                return '';
            }

            let productSearchFiltered = [];
            let productSearchHighlight = -1;

            function escHtml(s) {
                return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
            }

            function isProductVisibleInPos(product) {
                if (isMessBillCustomer()) {
                    if (isMessBillPurchaseItems()) {
                        return !!product.for_purchase;
                    }
                    return !!product.for_pos;
                }
                if (selectedCustomerType() === 'mess_use' || selectedCustomerType() === 'booking') {
                    return !!product.for_pos;
                }
                if (saleMode === 'staff') {
                    return !!(product.for_pos || product.for_purchase);
                }

                return !!product.for_pos;
            }

            function filterProducts(filter) {
                const q = filter.toLowerCase().trim();
                if (!q) {
                    return [];
                }
                return products.filter(p =>
                    isProductVisibleInPos(p) && (
                        (p.name || '').toLowerCase().includes(q)
                        || (p.sku || '').toLowerCase().includes(q)
                        || (p.barcode || '').toLowerCase().includes(q)
                    )
                );
            }

            function hideProductDropdown() {
                if (!productSearchDropdown) return;
                productSearchDropdown.classList.add('d-none');
                productSearchDropdown.innerHTML = '';
                productSearchFiltered = [];
                productSearchHighlight = -1;
            }

            function renderProductDropdown() {
                if (!productSearchDropdown || !productSearchInput) return;
                if (!productSearchFiltered.length) {
                    hideProductDropdown();
                    return;
                }
                productSearchDropdown.innerHTML = productSearchFiltered.map((p, i) => {
                    const active = i === productSearchHighlight ? ' bg-primary text-white' : '';
                    const subStyle = i === productSearchHighlight
                        ? 'font-size:13px;opacity:.9'
                        : 'font-size:13px';
                    const subCls = i === productSearchHighlight ? '' : ' text-secondary';
                    return `<div class="pos-product-option px-3 py-2 small border-bottom${active}" role="option" data-product-id="${p.id}" data-index="${i}" style="cursor:pointer;">
                        <div class="fw-semibold">${escHtml(p.name)}</div>
                        <div class="${subCls}" style="${subStyle}">${escHtml(p.sku || '—')} · Stock ${escHtml(fmtQty(p.qty_on_hand))} ${escHtml(p.uom || '')}${productStockBadgeHtml(p)}</div>
                    </div>`;
                }).join('');
                productSearchDropdown.classList.remove('d-none');
                const activeEl = productSearchDropdown.querySelector(`[data-index="${productSearchHighlight}"]`);
                activeEl?.scrollIntoView({ block: 'nearest' });
                productSearchDropdown.querySelectorAll('.pos-product-option').forEach(el => {
                    el.addEventListener('mousedown', (ev) => {
                        ev.preventDefault();
                        const id = el.getAttribute('data-product-id');
                        if (id) addProductById(Number(id));
                    });
                });
            }

            function openProductSearchFromInput() {
                const q = productSearchInput.value;
                productSearchFiltered = filterProducts(q);
                productSearchHighlight = productSearchFiltered.length ? 0 : -1;
                renderProductDropdown();
            }

            function addProductById(id) {
                addProduct(id);
                if (productSearchInput) productSearchInput.value = '';
                hideProductDropdown();
                queueMicrotask(() => focusLastCartQty());
            }

            function focusLastCartQty() {
                const rows = cartBody?.querySelectorAll('tr');
                if (!rows || !rows.length) return;
                const inp = rows[rows.length - 1].querySelector('td:nth-child(3) input[type="number"]');
                if (inp) {
                    inp.focus();
                    inp.select();
                }
            }


            /** Match saved line UOM to this product’s dropdown codes (case-insensitive) so resume + pay does not send a stale code. */
            function canonicalUomForLine(savedUom, p) {
                const want = String(savedUom ?? '').trim();
                const list = [p.uom, ...(p.uoms || []).map(x => x.uom)];
                for (const code of list) {
                    if (String(code).toLowerCase() === want.toLowerCase()) return code;
                }
                return want || p.uom;
            }

            /** factor_to_base for 1 unit of uomCode (same as server). */
            function factorForUom(p, uomCode) {
                const u = String(uomCode ?? '').trim();
                if (!p || u === '') return null;
                const baseUom = String(p.uom ?? '').trim();
                if (baseUom !== '' && baseUom.toLowerCase() === u.toLowerCase()) return 1;
                const row = (p.uoms || []).find(x => String(x.uom).toLowerCase() === u.toLowerCase());
                if (row) {
                    const f = Number(row.factor);
                    return Number.isFinite(f) && f > 0 ? f : null;
                }
                return null;
            }

            function unitPriceForProductUomStrict(product, uomCode) {
                const factor = factorForUom(product, uomCode);
                if (factor == null) {
                    return null;
                }
                const basePrice = baseUnitPriceForProduct(product);
                return Math.round(basePrice * factor * 100) / 100;
            }

            function lineExtendedCost(p, r) {
                const qty = Number(r.qty);
                const cost = Number(p?.cost ?? 0);
                const f = factorForUom(p, r.uom);
                if (f == null || !Number.isFinite(qty) || qty <= 0) return 0;
                return qty * f * cost;
            }

            function getBillTaxPercent() {
                const inp = document.getElementById('billTaxPercentInput');
                if (!inp) return 0;
                let n = parseFloat(inp.value);
                if (!Number.isFinite(n)) n = 0;
                return Math.max(0, Math.min(100, n));
            }

            function getBillDiscountPercent() {
                const inp = document.getElementById('billDiscountPercentInput');
                if (!inp || !posShowDiscount || saleMode === 'staff') return 0;
                let n = parseFloat(inp.value);
                if (!Number.isFinite(n)) n = 0;
                return Math.max(0, Math.min(100, n));
            }

            function calcCartTotals() {
                const lineSubs = cart.map(r => Number(r.qty) * Number(r.unit_price));
                let subtotal = lineSubs.reduce((sum, n) => sum + n, 0);
                subtotal = Math.round(subtotal * 100) / 100;

                const billDiscPct = getBillDiscountPercent();
                const discount = posShowDiscount
                    ? Math.round(subtotal * (billDiscPct / 100) * 100) / 100
                    : 0;

                let tax = 0;
                if (posTaxMode === 'bill') {
                    const net = Math.round((subtotal - discount) * 100) / 100;
                    tax = Math.round(net * (getBillTaxPercent() / 100) * 100) / 100;
                } else if (posTaxMode === 'line') {
                    let allocatedDisc = 0;
                    cart.forEach((r, idx) => {
                        const lineSub = Math.round(lineSubs[idx] * 100) / 100;
                        let lineDisc = 0;
                        if (posShowDiscount && discount > 0) {
                            if (idx === cart.length - 1) {
                                lineDisc = Math.round((discount - allocatedDisc) * 100) / 100;
                            } else {
                                lineDisc = subtotal > 0
                                    ? Math.round(discount * (lineSub / subtotal) * 100) / 100
                                    : 0;
                                allocatedDisc += lineDisc;
                            }
                        }
                        const lineNet = lineSub - lineDisc;
                        tax += Math.round(lineNet * ((Number(r.tax_percent) || 0) / 100) * 100) / 100;
                    });
                    tax = Math.round(tax * 100) / 100;
                }

                const grand = Math.round((subtotal - discount + tax) * 100) / 100;
                return { subtotal, discount, tax, grand, lineSubs, billDiscPct };
            }

            function lineRowTotal(p, r, totals) {
                const idx = cart.indexOf(r);
                const lineSub = totals?.lineSubs?.[idx] ?? (Number(r.qty) * Number(r.unit_price));
                const subtotal = totals?.subtotal ?? lineSub;
                const discount = totals?.discount ?? 0;
                let lineDisc = 0;
                if (posShowDiscount && discount > 0 && subtotal > 0) {
                    if (idx === cart.length - 1) {
                        let prior = 0;
                        cart.forEach((row, j) => {
                            if (j === idx) return;
                            const s = totals?.lineSubs?.[j] ?? (Number(row.qty) * Number(row.unit_price));
                            prior += Math.round(discount * (s / subtotal) * 100) / 100;
                        });
                        lineDisc = Math.round((discount - prior) * 100) / 100;
                    } else {
                        lineDisc = Math.round(discount * (lineSub / subtotal) * 100) / 100;
                    }
                }
                const lineNet = lineSub - lineDisc;
                const lineTax = posTaxMode === 'line'
                    ? Math.round(lineNet * ((Number(r.tax_percent) || 0) / 100) * 100) / 100
                    : 0;
                return Math.round((lineNet + lineTax) * 100) / 100;
            }

            function cartItemsForSubmit() {
                const totals = calcCartTotals();
                return cart.map(r => {
                    const p = products.find(x => Number(x.id) === Number(r.product_id));
                    return {
                        product_id: r.product_id,
                        uom: r.uom,
                        qty: r.qty,
                        unit_price: r.unit_price,
                        discount_percent: 0,
                        tax_percent: posTaxMode === 'line' ? r.tax_percent : 0,
                        notes: String(r.notes || '').trim(),
                        line_total: lineRowTotal(p, r, totals),
                    };
                });
            }

            function syncCartPricingBeforeSubmit() {
                applySaleModePricingToCart(true);
            }

            function addProduct(id) {
                const p = products.find(x => Number(x.id) === Number(id));
                if (!p) return;
                if (orderType === 'sale') {
                    const draft = { product_id: p.id, uom: p.uom, qty: 1 };
                    const msg = purchaseStockCheckMessage(p, draft, undefined);
                    if (msg && msg.startsWith('Stock nahi')) {
                        alert(msg);
                        return;
                    }
                    if (msg) alert(msg);
                }
                cart.push({
                    product_id: p.id,
                    name: p.name,
                    uom: p.uom,
                    uoms: [p.uom, ...(p.uoms || []).map(x => x.uom).filter(u => u !== p.uom)],
                    qty: 1,
                    unit_price: currentDefaultUnitPrice(p),
                    discount_percent: 0,
                    tax_percent: posTaxMode === 'line' ? posDefaultLineTax : 0,
                    notes: '',
                    kitchen_served: false,
                    kitchen_pending: false,
                    kitchen_locked_qty: 0,
                });
                renderCart();
            }

            function kitchenLockedQty(row) {
                return Number(row?.kitchen_locked_qty) || 0;
            }

            function kitchenLockedFromResume(ri) {
                const qty = Number(ri.qty) || 0;
                return (ri.kitchen_served || ri.kitchen_pending) ? qty : 0;
            }

            function qtyStep(i, delta) {
                const current = Number(cart[i]?.qty) || 1;
                const locked = kitchenLockedQty(cart[i]);
                const next = Math.max(locked || 0.001, Math.round((current + delta) * 1000) / 1000);
                if (delta < 0 && next <= locked) {
                    alert('Kitchen me bheji hui quantity kam nahi ho sakti.');
                    return;
                }
                upd(i, 'qty', next);
            }

            function cartServedStatusBadge(row) {
                if (kitchenLockedQty(row) > 0) {
                    return row.kitchen_served
                        ? '<span class="badge text-bg-success">Served</span>'
                        : '<span class="badge text-bg-warning">Kitchen</span>';
                }
                return '';
            }

            function renderCart() {
                const totals = calcCartTotals();
                cartBody.innerHTML = cart.map((r, i) => {
                    const p = products.find(x => Number(x.id) === Number(r.product_id));
                    const total = lineRowTotal(p, r, totals);
                    const tp = posTaxMode === 'line' ? (Number(r.tax_percent) || 0) : 0;
                    const locked = kitchenLockedQty(r);
                    const minQty = locked > 0 ? locked : 0.001;
                    const canDec = Number(r.qty) > locked;
                    const canRemove = locked <= 0;
                    const lockAttrs = locked > 0 ? ' disabled title="Kitchen me bheja hua"' : '';
                    let row = `<tr${locked > 0 ? ' class="pos-cart-kitchen-locked"' : ''}>
                        <td class="pos-cart-col-product">${r.name}</td>
                        <td class="pos-cart-col-uom"><select class="form-select form-select-sm" onchange="upd(${i},'uom',this.value)"${lockAttrs}>${r.uoms.map(u => `<option value="${String(u).replace(/"/g,'&quot;')}" ${u===r.uom?'selected':''}>${u}</option>`).join('')}</select></td>
                        <td class="pos-cart-col-qty">
                            <div class="input-group input-group-sm pos-qty-stepper">
                                <button type="button" class="btn btn-outline-secondary" onclick="qtyStep(${i}, -1)" aria-label="Decrease quantity"${canDec ? '' : ' disabled'}>−</button>
                                <input type="number" step="0.001" min="${minQty}" class="form-control form-control-sm pos-qty-input" value="${r.qty}" onchange="upd(${i},'qty',this.value)">
                                <button type="button" class="btn btn-outline-secondary" onclick="qtyStep(${i}, 1)" aria-label="Increase quantity">+</button>
                            </div>
                        </td>
                        <td class="pos-cart-col-price fw-semibold text-nowrap">${fmtMoney(Number(r.unit_price))}</td>`;
                    if (posTaxMode === 'line') {
                        row += `<td class="pos-cart-col-tax"><input type="number" step="0.01" min="0" max="100" class="form-control form-control-sm" value="${tp}" onchange="upd(${i},'tax_percent',this.value)" title="Tax % on this line"></td>`;
                    }
                    row += `<td class="pos-cart-col-total fw-semibold text-nowrap">${fmtMoney(total)}</td>
                        <td class="pos-cart-col-notes"><input type="text" class="form-control form-control-sm" value="${escHtml(r.notes || '')}" maxlength="255" placeholder="Note" onchange="upd(${i},'notes',this.value)"${lockAttrs}></td>
                        <td class="pos-cart-col-served">${cartServedStatusBadge(r)}</td>
                        <td class="pos-cart-col-action"><button class="btn btn-sm btn-outline-danger pos-cart-rm-btn" onclick="rm(${i})" type="button"${canRemove ? '' : ' disabled title="Kitchen item remove nahi ho sakta"'}>×</button></td>
                    </tr>`;
                    return row;
                }).join('');
                renderSummary();
            }

            function renderPayments() {
                paymentRows.innerHTML = payments.map((p, i) => `
                    <div class="row g-2 mb-2">
                        <div class="col-4"><select class="form-select form-select-sm" onchange="updPay(${i},'method',this.value)"><option ${p.method==='cash'?'selected':''} value="cash">Cash</option><option ${p.method==='card'?'selected':''} value="card">Card</option><option ${p.method==='bank'?'selected':''} value="bank">Bank</option></select></div>
                        <div class="col-5"><input class="form-control form-control-sm" type="number" min="0.01" step="0.01" value="${p.amount}" onchange="updPay(${i},'amount',this.value)"></div>
                        <div class="col-3"><button class="btn btn-sm btn-outline-danger w-100" type="button" onclick="rmPay(${i})">x</button></div>
                    </div>
                `).join('');
            }

            function getGrandTotal() {
                return calcCartTotals().grand;
            }

            function renderSummary() {
                const { subtotal, discount, tax, grand } = calcCartTotals();
                document.getElementById('sumSubtotal').textContent = fmtMoney(subtotal);
                const sumDisc = document.getElementById('sumDiscount');
                if (sumDisc) sumDisc.textContent = fmtMoney(discount);
                const sumTax = document.getElementById('sumTax');
                if (sumTax) sumTax.textContent = fmtMoney(tax);
                document.getElementById('sumGrand').textContent = fmtMoney(grand);

                if (payments.length === 1 && autoPaymentAmount) {
                    payments[0].amount = Number(grand.toFixed(2));
                    renderPayments();
                }
            }

            function draftBillHtml() {
                const companyName = @json(config('app.name'));
                const now = new Date();
                const dateLabel = now.toLocaleString();
                const customerType = selectedCustomerType();
                const guestName = customerType === 'booking'
                    ? (checkedInRoomSelect?.options[checkedInRoomSelect.selectedIndex]?.dataset?.guestName || '')
                    : (guestNameInput?.value?.trim() || '');
                const roomNo = customerType === 'booking' ? (checkedInRoomSelect?.value || '') : '';
                const waiterName = isWalkInCustomerType(customerType) ? (waiterNameInput?.value?.trim() || '') : '';
                const totals = calcCartTotals();
                const rows = cart.map((r) => {
                    const p = products.find(x => Number(x.id) === Number(r.product_id));
                    const total = lineRowTotal(p, r, totals);
                    const noteLine = String(r.notes || '').trim()
                        ? `<div class="muted" style="font-size:12px;">Note: ${escHtml(String(r.notes || '').trim())}</div>`
                        : '';
                    return `
                        <tr>
                            <td style="padding:4px 0;">${escHtml(r.name)}${noteLine}</td>
                            <td style="padding:4px 0;text-align:right;white-space:nowrap;">${escHtml(fmtQty(Number(r.qty)))} ${escHtml(r.uom)} × ${escHtml(fmtMoney(Number(r.unit_price)))}</td>
                            <td style="padding:4px 0;text-align:right;white-space:nowrap;">${escHtml(fmtMoney(total))}</td>
                        </tr>
                    `;
                }).join('');

                const subtotal = document.getElementById('sumSubtotal')?.textContent || '0';
                const discount = document.getElementById('sumDiscount')?.textContent || '0';
                const tax = document.getElementById('sumTax')?.textContent || '0';
                const grand = document.getElementById('sumGrand')?.textContent || '0';

                return `<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>POS Bill Preview</title>
    <style>
        body{font-family:ui-monospace,Consolas,monospace;font-size:12px;color:#000;max-width:80mm;margin:0 auto;padding:8px;}
        .center{text-align:center}.bold{font-weight:700}.line{border-top:1px dashed #000;margin:8px 0}
        table{width:100%;border-collapse:collapse}.muted{color:#333}.tot{display:flex;justify-content:space-between;padding:2px 0}
        @media print {.noprint{display:none}}
    </style>
</head>
<body>
    <div class="center bold" style="font-size:14px;">${escHtml(companyName)}</div>
    <div class="center muted">Unpaid Bill Preview</div>
    <div class="center muted">${escHtml(dateLabel)}</div>
    <div class="center muted">Type: ${escHtml(customerTypeLabel(customerType))}</div>
    ${guestName ? `<div class="center muted">Guest: ${escHtml(guestName)}</div>` : ``}
    ${roomNo ? `<div class="center muted">Room: ${escHtml(roomNo)}</div>` : ``}
    ${waiterName ? `<div class="center muted">Waiter: ${escHtml(waiterName)}</div>` : ``}
    <div class="line"></div>
    <table>
        ${rows}
    </table>
    <div class="line"></div>
    <div class="tot"><span class="muted">Subtotal</span><span>${escHtml(subtotal)}</span></div>
    ${posShowDiscount ? `<div class="tot"><span class="muted">Discount</span><span>${escHtml(discount)}</span></div>` : ``}
    ${posTaxMode !== 'off' ? `<div class="tot"><span class="muted">Tax</span><span>${escHtml(tax)}</span></div>` : ``}
    <div class="tot bold" style="font-size:14px;"><span>TOTAL</span><span>${escHtml(grand)}</span></div>
    <div class="line"></div>
    <div class="center muted">Status: UNPAID</div>
</body>
</html>`;
            }

            function printDraftBillInline() {
                const html = draftBillHtml();
                const old = document.getElementById('posDraftPrintFrame');
                if (old) old.remove();

                const frame = document.createElement('iframe');
                frame.id = 'posDraftPrintFrame';
                frame.style.position = 'fixed';
                frame.style.right = '0';
                frame.style.bottom = '0';
                frame.style.width = '0';
                frame.style.height = '0';
                frame.style.border = '0';
                frame.setAttribute('aria-hidden', 'true');
                document.body.appendChild(frame);

                const doc = frame.contentWindow?.document;
                if (!doc) {
                    frame.remove();
                    alert('Unable to start print preview.');
                    return;
                }
                doc.open();
                doc.write(html);
                doc.close();

                const runPrint = () => {
                    try {
                        frame.contentWindow?.focus();
                        frame.contentWindow?.print();
                    } catch (e) {}
                    setTimeout(() => frame.remove(), 1200);
                };

                if (doc.readyState === 'complete') {
                    setTimeout(runPrint, 120);
                } else {
                    frame.onload = () => setTimeout(runPrint, 120);
                }
            }

            function upd(i, k, v) {
                const backup = JSON.parse(JSON.stringify(cart[i]));
                const locked = kitchenLockedQty(cart[i]);
                if (locked > 0 && (k === 'uom' || k === 'notes')) {
                    alert('Kitchen me bheji hui item edit nahi ho sakti.');
                    return;
                }
                const p = products.find(x => Number(x.id) === Number(cart[i].product_id));
                if (k === 'uom') {
                    cart[i][k] = v;
                    if (p) {
                        cart[i].unit_price = convertUnitPriceForUomChange(p, backup.unit_price, backup.uom, v);
                    }
                } else if (k === 'notes') {
                    cart[i][k] = String(v ?? '').trim().slice(0, 255);
                } else if (k === 'tax_percent') {
                    let n = Number(v);
                    if (!Number.isFinite(n)) n = 0;
                    cart[i][k] = Math.max(0, Math.min(100, n));
                } else {
                    cart[i][k] = Number.isFinite(Number(v)) ? Number(v) : v;
                }
                if (k === 'qty') {
                    const nextQty = Number(cart[i].qty) || 0;
                    if (nextQty + 0.00001 < locked) {
                        alert('Kitchen me bheji hui quantity kam nahi ho sakti.');
                        cart[i] = backup;
                        renderCart();
                        return;
                    }
                }
                if ((saleMode === 'staff' || isMessBillCustomer()) && k !== 'uom' && p) {
                    const repriced = unitPriceForProductUomStrict(p, cart[i].uom);
                    if (repriced != null) {
                        cart[i].unit_price = repriced;
                    }
                }
                if (orderType === 'sale' && (k === 'qty' || k === 'uom')) {
                    if (p) {
                        const msg = purchaseStockCheckMessage(p, cart[i], i);
                        if (msg && msg.startsWith('Stock nahi')) {
                            alert(msg);
                            cart[i] = backup;
                            renderCart();
                            return;
                        }
                        if (msg) alert(msg);
                    }
                }
                renderCart();
            }
            function rm(i) {
                if (kitchenLockedQty(cart[i]) > 0) {
                    alert('Kitchen me bheji hui item remove nahi ho sakti.');
                    return;
                }
                cart.splice(i, 1);
                renderCart();
            }
            function updPay(i, k, v) {
                if (k === 'amount') {
                    payments[i][k] = Number(v);
                    autoPaymentAmount = false;
                } else {
                    payments[i][k] = v;
                }
                renderPayments();
            }
            function rmPay(i) { payments.splice(i, 1); renderPayments(); }
            window.upd = upd; window.rm = rm; window.updPay = updPay; window.rmPay = rmPay; window.qtyStep = qtyStep;

            // ── Credit sale state ──────────────────────────────
            let isCreditMode = false;
            let selectedContactId = null;

            const creditToggle   = document.getElementById('creditToggle');
            const paymentCard    = document.getElementById('paymentCard');
            const addPaymentBtn  = document.getElementById('addPaymentBtn');
            const contactSearch  = document.getElementById('contactSearch');
            const contactDropdown= document.getElementById('contactDropdown');
            const selectedBox    = document.getElementById('selectedContactBox');
            const selName        = document.getElementById('selContactName');
            const selPhone       = document.getElementById('selContactPhone');
            const clearContactBtn= document.getElementById('clearContact');
            const quickAdd       = document.getElementById('quickAddContact');
            const checkoutBtn    = document.getElementById('checkoutBtn');
            const posCustomerCardTitle = document.getElementById('posCustomerCardTitle');

            function updateCheckoutVisibility() {
                if (!checkoutBtn) return;
                checkoutBtn.classList.remove('d-none');
            }

            function setCreditMode(enabled, options = {}) {
                const lock = !!options.lock;
                const customerType = selectedCustomerType();
                if (customerType === 'ast_offr') {
                    enabled = true;
                }
                isCreditMode = enabled;
                if (creditToggle) {
                    creditToggle.checked = enabled;
                    creditToggle.disabled = lock;
                }
                if (paymentCard) {
                    paymentCard.style.opacity = enabled ? '.4' : '1';
                    paymentCard.style.pointerEvents = enabled ? 'none' : '';
                }
                if (checkoutBtn) {
                    checkoutBtn.className = enabled ? 'pos-act-btn pos-act-credit' : 'pos-act-btn pos-act-pay';
                    checkoutBtn.textContent = enabled ? 'Record Credit Sale' : 'Pay Now';
                }
                if (posCustomerCardTitle) {
                    if (customerType === 'ast_offr') {
                        posCustomerCardTitle.textContent = 'Officer (Credit)';
                    } else if (customerType === 'mess_use') {
                        posCustomerCardTitle.textContent = enabled ? 'Walk-In (Credit)' : 'Walk-In Guest';
                    } else if (customerType === 'booking') {
                        posCustomerCardTitle.textContent = enabled ? 'In-House (Credit)' : 'In-House Guest';
                    } else {
                        posCustomerCardTitle.textContent = 'Customer';
                    }
                }
                updateCheckoutVisibility();
            }

            creditToggle?.addEventListener('change', function () {
                const customerType = selectedCustomerType();
                if (customerType === 'ast_offr' && !this.checked) {
                    this.checked = true;
                    return;
                }
                setCreditMode(this.checked);
            });

            // Contact live search
            let searchTimer;

            function positionContactDropdown() {
                if (!contactSearch || !contactDropdown) return;
                const rect = contactSearch.getBoundingClientRect();
                contactDropdown.style.left = `${Math.max(8, rect.left)}px`;
                contactDropdown.style.top = `${rect.bottom + 4}px`;
                contactDropdown.style.width = `${rect.width}px`;
            }

            function hideContactDropdown() {
                contactDropdown?.classList.add('d-none');
            }

            function filterPosContacts(query) {
                const needle = query.toLowerCase();
                return posContacts.filter((c) =>
                    String(c.name || '').toLowerCase().includes(needle)
                    || String(c.phone || '').toLowerCase().includes(needle)
                ).slice(0, 15);
            }

            let contactSearchResults = [];

            function renderContactDropdown(results, query) {
                if (!contactDropdown) return;
                contactSearchResults = results;

                if (!results.length) {
                    contactDropdown.innerHTML =
                        '<div class="px-3 py-2 small text-secondary">No contacts found.</div>' +
                        '<div class="px-3 py-1 border-top"><button type="button" class="btn btn-sm btn-link px-0 text-primary" id="showQuickAdd">+ Add new contact</button></div>';
                    positionContactDropdown();
                    contactDropdown.classList.remove('d-none');
                    document.getElementById('showQuickAdd')?.addEventListener('click', () => {
                        hideContactDropdown();
                        const nameInput = document.getElementById('newContactName');
                        if (nameInput) nameInput.value = query;
                        quickAdd?.classList.remove('d-none');
                    });
                    return;
                }

                contactDropdown.innerHTML = results.map((c) =>
                    `<div class="contact-option px-3 py-2 small d-flex justify-content-between align-items-center"
                        style="cursor:pointer;" data-id="${c.id}">
                        <span class="fw-semibold">${escHtml(c.name || '')}</span>
                        <span class="text-secondary">${escHtml(c.phone || '')}</span>
                    </div>`
                ).join('');
                positionContactDropdown();
                contactDropdown.classList.remove('d-none');
                contactDropdown.querySelectorAll('.contact-option').forEach((el) => {
                    el.addEventListener('mousedown', (ev) => {
                        ev.preventDefault();
                        const contact = contactSearchResults.find((row) => String(row.id) === String(el.dataset.id));
                        if (contact) {
                            selectContact(contact.id, contact.name || '', contact.phone || '');
                        }
                    });
                });
            }

            contactSearch?.addEventListener('input', function () {
                clearTimeout(searchTimer);
                const q = this.value.trim();
                quickAdd?.classList.add('d-none');
                if (q.length < 1) {
                    hideContactDropdown();
                    return;
                }
                searchTimer = setTimeout(() => {
                    const local = filterPosContacts(q);
                    renderContactDropdown(local, q);

                    fetch('{{ route('contacts.search') }}?q=' + encodeURIComponent(q), {
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    })
                        .then((r) => {
                            if (!r.ok) throw new Error('search failed');
                            return r.json();
                        })
                        .then((data) => {
                            if (!Array.isArray(data)) return;
                            const merged = [];
                            const seen = new Set();
                            [...local, ...data].forEach((c) => {
                                const id = String(c.id);
                                if (seen.has(id)) return;
                                seen.add(id);
                                merged.push(c);
                            });
                            if (contactSearch.value.trim() === q) {
                                renderContactDropdown(merged.slice(0, 15), q);
                            }
                        })
                        .catch(() => {});
                }, 200);
            });

            contactSearch?.addEventListener('focus', function () {
                const q = this.value.trim();
                if (q.length > 0) {
                    renderContactDropdown(filterPosContacts(q), q);
                }
            });

            window.addEventListener('resize', () => {
                if (contactDropdown && !contactDropdown.classList.contains('d-none')) {
                    positionContactDropdown();
                }
            });

            document.getElementById('posSaleTabPane')?.addEventListener('scroll', () => {
                if (contactDropdown && !contactDropdown.classList.contains('d-none')) {
                    positionContactDropdown();
                }
            }, true);

            function selectContact(id, name, phone) {
                selectedContactId = id;
                selName.textContent  = name;
                selPhone.textContent = phone;
                selectedBox?.classList.remove('d-none');
                if (contactSearch) contactSearch.value = '';
                hideContactDropdown();
                quickAdd?.classList.add('d-none');
                if (selectedCustomerType() === 'ast_offr' && guestNameInput) {
                    guestNameInput.value = name;
                }
            }

            clearContactBtn?.addEventListener('click', () => {
                selectedContactId = null;
                selectedBox.classList.add('d-none');
                contactSearch.value = '';
            });

            // Quick add new contact via AJAX
            document.getElementById('saveQuickContact')?.addEventListener('click', function () {
                const name  = document.getElementById('newContactName').value.trim();
                const phone = document.getElementById('newContactPhone').value.trim();
                if (!name) { alert('Name is required.'); return; }
                this.disabled = true;
                fetch('{{ route('contacts.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ name, phone })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.contact) {
                        selectContact(data.contact.id, data.contact.name, data.contact.phone || '');
                        quickAdd.classList.add('d-none');
                    }
                })
                .finally(() => { this.disabled = false; });
            });

            // Close dropdown on outside click
            document.addEventListener('click', e => {
                if (!e.target.closest('#contactSearch') && !e.target.closest('#contactDropdown')) {
                    hideContactDropdown();
                }
            });

            function prepareOrderSubmit(mode, cashTenderMeta) {
                if (!cart.length) {
                    alert('Add at least one item.');
                    return false;
                }
                syncCartPricingBeforeSubmit();
                const customerType = selectedCustomerType();

                if (isWalkInCustomerType(customerType)) {
                    const typeLabel = customerTypeLabel(customerType);
                    if (customerType === 'ast_offr') {
                        if (!selectedContactId) {
                            alert(posMessBillLabel + ' ke liye officer select karein (Customer search se).');
                            return false;
                        }
                    } else {
                        if (!(guestNameInput?.value || '').trim()) {
                            alert(typeLabel + ' ke liye Guest Name required hai.');
                            return false;
                        }
                        if (!(waiterNameInput?.value || '').trim()) {
                            alert(typeLabel + ' ke liye Waiter select karein.');
                            return false;
                        }
                    }
                } else if (!(checkedInRoomSelect?.value || '').trim()) {
                    alert('Booking ke liye checked-in Room select karein.');
                    return false;
                }

                const resolvedGuestName = customerType === 'booking'
                    ? (checkedInRoomSelect?.options[checkedInRoomSelect.selectedIndex]?.dataset?.guestName || '')
                    : resolvedWalkInGuestName(customerType);
                const resolvedRoomNo = customerType === 'booking' ? (checkedInRoomSelect?.value || '') : '';

                if (mode === 'checkout' || mode === 'hold') {
                    const pendingBill = findGuestPendingBill(customerType, resolvedGuestName, resolvedRoomNo);
                    if (pendingBill) {
                        alert(`Is guest ki pending bill pehle se maujood hai (${pendingBill.order_no}). Pehle Pending Bills tab se Resume kar ke pay karein ya Discard karein.`);
                        return false;
                    }
                }

                const tenderInp = document.getElementById('cashTenderedInput');
                const changeInp = document.getElementById('cashChangeInput');
                if (mode === 'checkout') {
                    if (cashTenderMeta && cashTenderMeta.tendered != null) {
                        tenderInp.value = String(cashTenderMeta.tendered);
                        changeInp.value = String(cashTenderMeta.change != null ? cashTenderMeta.change : '');
                    } else {
                        tenderInp.value = '';
                        changeInp.value = '';
                    }
                }

                if (mode === 'checkout' && (isCreditMode || customerType === 'ast_offr')) {
                    if (!selectedContactId) {
                        alert(customerType === 'ast_offr'
                            ? posMessBillLabel + ' ke liye officer select karein.'
                            : 'Please select a contact for credit sale.');
                        return false;
                    }
                    document.getElementById('isCreditInput').value = '1';
                    document.getElementById('contactIdInput').value = selectedContactId;
                    document.getElementById('paymentsJson').value = JSON.stringify([]);
                } else {
                    document.getElementById('isCreditInput').value = '0';
                    document.getElementById('contactIdInput').value = selectedContactId || '';
                    if (!payments.length && mode === 'checkout') {
                        alert('Add at least one payment.');
                        return false;
                    }
                    document.getElementById('paymentsJson').value = JSON.stringify(mode === 'checkout' ? payments : [{method: 'cash', amount: 0}]);
                }

                document.getElementById('orderType').value = orderType;
                if (saleModeInput) saleModeInput.value = saleMode;
                if (staffIncludeGasHidden) {
                    if (customerType === 'ast_offr') {
                        staffIncludeGasHidden.value = '0';
                    } else if (saleMode === 'staff') {
                        staffIncludeGasHidden.value = '1';
                    } else {
                        staffIncludeGasHidden.value = '0';
                    }
                }
                if (posTablesEnabled && customerType === 'mess_use') {
                    document.getElementById('tableIdInput').value = tableSelect?.value || '';
                } else {
                    document.getElementById('tableIdInput').value = '';
                }
                if (customerTypeHidden) customerTypeHidden.value = customerType;
                document.getElementById('guestNameHidden').value = customerType === 'booking'
                    ? (checkedInRoomSelect?.options[checkedInRoomSelect.selectedIndex]?.dataset?.guestName || '')
                    : resolvedWalkInGuestName(customerType);
                document.getElementById('roomNoHidden').value = customerType === 'booking'
                    ? (checkedInRoomSelect?.value || '')
                    : '';
                document.getElementById('waiterNameHidden').value = customerType === 'mess_use'
                    ? (waiterNameInput?.value || '')
                    : '';
                document.getElementById('orderNotesHidden').value = (orderNotesInput?.value || '').trim();
                document.getElementById('serveTimeHidden').value = customerType === 'mess_use'
                    ? (serveTimeInput?.value || '')
                    : '';
                document.getElementById('itemsJson').value = JSON.stringify(cartItemsForSubmit());
                const billH = document.getElementById('billTaxPercentHidden');
                if (billH) billH.value = posTaxMode === 'bill' ? String(getBillTaxPercent()) : '0';
                const billDiscH = document.getElementById('billDiscountPercentHidden');
                if (billDiscH) billDiscH.value = posShowDiscount ? String(getBillDiscountPercent()) : '0';

                return true;
            }

            function pendingBillTypeBadge(customerType) {
                if (customerType === 'booking') return '<span class="badge text-bg-primary">In-House</span>';
                if (customerType === 'ast_offr') return '<span class="badge text-bg-info">' + escHtml(posMessBillLabel) + '</span>';
                return '<span class="badge text-bg-secondary">Walk-In</span>';
            }

            function pendingBillTableRoomCell(order) {
                const parts = [];
                if (posTablesEnabled && order.table_name) parts.push(order.table_name);
                if (order.room_no) parts.push(order.room_no);
                return parts.length
                    ? escHtml(parts.join(' / '))
                    : '<span class="text-secondary">—</span>';
            }

            function pendingBillItemsCell(order) {
                const lines = Array.isArray(order.items) ? order.items : [];
                const count = order.items_count ?? lines.length;
                return escHtml(String(count));
            }

            function kitchenItemStatusBadge(line) {
                if (line.kitchen_served) {
                    const servedAt = line.kitchen_served_at ? ` ${escHtml(line.kitchen_served_at)}` : '';
                    return `<span class="badge text-bg-success">Served${servedAt}</span>`;
                }
                if (line.kitchen_pending) {
                    return '<span class="badge text-bg-warning text-dark">Pending</span>';
                }
                return '<span class="text-secondary">—</span>';
            }

            function pendingBillStatusCell(order) {
                const label = order.kitchen_status_label || 'Queued';
                const badge = order.kitchen_status_badge || 'text-bg-secondary';
                return `<span class="badge ${escHtml(badge)}">${escHtml(label)}</span>`;
            }

            function pendingBillOrderCell(order) {
                let html = escHtml(order.order_no || '');
                if (order.from_order_taker) {
                    html += ' <span class="badge text-bg-info ms-1">Order Taker</span>';
                    if (order.order_time) {
                        html += `<div class="small text-secondary">Order ${escHtml(order.order_time)}</div>`;
                    }
                    if (order.serve_time) {
                        html += `<div class="small text-secondary">Serve ${escHtml(order.serve_time)}</div>`;
                    }
                }
                if (order.served_at) {
                    html += `<div class="small text-success">Served ${escHtml(order.served_at)}</div>`;
                }
                return html;
            }

            function paidBillTypeBadge(order) {
                if (order.is_refund) return '<span class="badge text-bg-danger">Refund</span>';
                if (order.customer_type === 'ast_offr') return '<span class="badge text-bg-info">' + escHtml(posMessBillLabel) + '</span>';
                if (order.is_credit) return '<span class="badge text-bg-warning text-dark">Credit</span>';
                return pendingBillTypeBadge(order.customer_type);
            }

            function paidBillKitchenSummaryHtml(order) {
                const lines = Array.isArray(order.items) ? order.items : [];
                const served = lines.filter((line) => line.kitchen_served);
                const pending = lines.filter((line) => !line.kitchen_served && line.kitchen_pending);

                if (!served.length && !pending.length) {
                    return '';
                }

                const servedList = served.map((line) => `
                    <div class="small py-1 border-bottom border-success-subtle">
                        <span class="badge text-bg-success me-1">Served${line.kitchen_served_at ? ' ' + escHtml(line.kitchen_served_at) : ''}</span>
                        ${escHtml(line.name || 'Item')} × ${escHtml(String(line.qty ?? 0))} ${escHtml(line.uom || '')}
                    </div>
                `).join('');

                const pendingList = pending.map((line) => `
                    <div class="small py-1 border-bottom border-warning-subtle">
                        <span class="badge text-bg-warning text-dark me-1">Pending</span>
                        ${escHtml(line.name || 'Item')} × ${escHtml(String(line.qty ?? 0))} ${escHtml(line.uom || '')}
                    </div>
                `).join('');

                return `
                    <div class="row g-2 mb-3">
                        ${served.length ? `
                            <div class="col-md-6">
                                <div class="rounded-3 p-2 h-100" style="background:#ecfdf5;border:1px solid #bbf7d0;">
                                    <div class="small fw-bold text-success mb-1">Served (${served.length})</div>
                                    ${servedList}
                                </div>
                            </div>
                        ` : ''}
                        ${pending.length ? `
                            <div class="col-md-6">
                                <div class="rounded-3 p-2 h-100" style="background:#fffbeb;border:1px solid #fde68a;">
                                    <div class="small fw-bold text-warning mb-1">Pending (${pending.length})</div>
                                    ${pendingList}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
            }

            function orderTimelineHtml(order) {
                const steps = Array.isArray(order.timeline) ? order.timeline : [];
                if (!steps.length) return '';

                const rows = steps.map((step) => {
                    const done = step.value && String(step.value).trim() !== '';
                    return `<div class="pos-order-timeline-row${done ? ' is-done' : ''}">
                        <div class="pos-order-timeline-label">${escHtml(step.label || '')}</div>
                        <div class="pos-order-timeline-value">${escHtml(done ? step.value : '—')}</div>
                    </div>`;
                }).join('');

                return `
                    <div class="mb-2 fw-semibold">Order timeline</div>
                    <div class="pos-order-timeline">${rows}</div>
                `;
            }

            function orderBillDetailsHtml(order) {
                const metaRows = [
                    ['Guest', order.guest_name || '—'],
                    ['Waiter', order.waiter_name || '—'],
                    ['Table / Room', order.table_room || '—'],
                ];
                if (String(order.order_notes || '').trim() !== '') {
                    metaRows.push(['Notes', order.order_notes]);
                }
                if (!order.is_pending) {
                    metaRows.push(['Payment', order.payment_label || '—']);
                    metaRows.push(['Paid', order.paid_at_full || order.paid_at || '—']);
                } else {
                    metaRows.push(['Kitchen status', order.kitchen_status_label || 'Queued']);
                }
                metaRows.push(['Total', fmtMoney(Number(order.grand_total || 0))]);

                const metaHtml = metaRows.map(([label, value]) => `
                    <div class="col-sm-6">
                        <div class="small text-secondary">${escHtml(label)}</div>
                        <div class="fw-semibold">${escHtml(String(value))}</div>
                    </div>
                `).join('');

                const sortedItems = (Array.isArray(order.items) ? [...order.items] : []).sort((a, b) => {
                    const rank = (line) => {
                        if (line.kitchen_served) return 0;
                        if (line.kitchen_pending) return 1;
                        return 2;
                    };
                    return rank(a) - rank(b);
                });

                const itemRows = sortedItems.map((line) => {
                    const noteHtml = line.notes
                        ? `<div class="small text-secondary">Note: ${escHtml(line.notes)}</div>`
                        : '';
                    return `<tr>
                        <td>
                            <div class="fw-semibold">${escHtml(line.name || 'Item')}</div>
                            ${noteHtml}
                        </td>
                        <td class="text-nowrap">${escHtml(String(line.qty ?? 0))} ${escHtml(line.uom || '')}</td>
                        <td class="text-nowrap">${escHtml(fmtMoney(Number(line.unit_price || 0)))}</td>
                        <td class="text-nowrap fw-semibold">${escHtml(fmtMoney(Number(line.total || 0)))}</td>
                        <td class="text-nowrap">${kitchenItemStatusBadge(line)}</td>
                    </tr>`;
                }).join('');

                const typeBadge = order.is_pending
                    ? pendingBillTypeBadge(order.customer_type)
                    : paidBillTypeBadge(order);

                return `
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                        ${typeBadge}
                        <span class="fw-bold">${escHtml(order.order_no || '')}</span>
                        ${order.from_order_taker ? '<span class="badge text-bg-info">Order Taker</span>' : ''}
                    </div>
                    ${orderTimelineHtml(order)}
                    <div class="row g-3 mb-3">${metaHtml}</div>
                    ${paidBillKitchenSummaryHtml(order)}
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                    <th>Kitchen</th>
                                </tr>
                            </thead>
                            <tbody>${itemRows || '<tr><td colspan="5" class="text-secondary">No items.</td></tr>'}</tbody>
                        </table>
                    </div>
                `;
            }

            function paidBillDetailsHtml(order) {
                return orderBillDetailsHtml(order);
            }

            const paidBillModalEl = document.getElementById('posPaidBillModal');
            let paidBillModalBs = null;

            function getPaidBillModalBs() {
                const Modal = window.bootstrap && window.bootstrap.Modal;
                if (!paidBillModalEl || !Modal) return null;
                if (!paidBillModalBs) {
                    paidBillModalBs = Modal.getOrCreateInstance(paidBillModalEl);
                }
                return paidBillModalBs;
            }

            function openOrderDetails(orderId) {
                const order = ordersDetailById[Number(orderId)];
                if (!order) return;

                const titleEl = document.getElementById('posPaidBillModalLabel');
                const bodyEl = document.getElementById('posPaidBillModalBody');
                const receiptLink = document.getElementById('posPaidBillReceiptLink');
                if (titleEl) titleEl.textContent = `Order Details — ${order.order_no || ''}`;
                if (bodyEl) bodyEl.innerHTML = orderBillDetailsHtml(order);
                if (receiptLink) {
                    if (order.is_pending) {
                        receiptLink.classList.add('d-none');
                    } else {
                        receiptLink.href = posReceiptRouteStub.replace('__ID__', String(order.id));
                        receiptLink.classList.remove('d-none');
                    }
                }

                const modal = getPaidBillModalBs();
                if (modal) {
                    modal.show();
                } else if (paidBillModalEl) {
                    paidBillModalEl.classList.add('show');
                    paidBillModalEl.style.display = 'block';
                    paidBillModalEl.removeAttribute('aria-hidden');
                }
            }

            function openPaidBillDetails(orderId) {
                openOrderDetails(orderId);
            }

            function pendingBillRowHtml(order) {
                const resumeUrl = posResumeRouteStub.replace('__ID__', String(order.id));
                const discardUrl = posDiscardRouteStub.replace('__ID__', String(order.id));
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

                return `<tr data-pending-order-id="${escHtml(String(order.id))}">
                    <td>${pendingBillOrderCell(order)}</td>
                    <td>${pendingBillTypeBadge(order.customer_type)}</td>
                    <td>${pendingBillTableRoomCell(order)}</td>
                    <td>${escHtml(order.guest_name || '—')}</td>
                    <td>${escHtml(order.waiter_name || '—')}</td>
                    <td>${pendingBillStatusCell(order)}</td>
                    <td class="small">${pendingBillItemsCell(order)}</td>
                    <td>${escHtml(fmtMoney(Number(order.grand_total || 0)))}</td>
                    <td>${escHtml(order.created_at || '')}</td>
                    <td class="text-end text-nowrap">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-order-details="${escHtml(String(order.id))}" title="Order details">
                            <i class="bi bi-list-check"></i>
                        </button>
                        <a href="${escHtml(resumeUrl)}" class="btn btn-sm btn-outline-secondary">Resume</a>
                        <form method="POST" action="${escHtml(discardUrl)}" class="d-inline ms-1" onsubmit="return confirm('Discard this pending bill? It cannot be undone.');">
                            <input type="hidden" name="_token" value="${escHtml(csrf)}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Discard hold"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>`;
            }

            function syncPendingBillsArray(order, isUpdate) {
                ordersDetailById[Number(order.id)] = order;
                const entry = {
                    id: order.id,
                    order_no: order.order_no,
                    customer_type: order.customer_type,
                    guest_name: order.guest_name,
                    room_no: order.room_no,
                };
                if (isUpdate) {
                    const idx = pendingBills.findIndex((pending) => Number(pending.id) === Number(order.id));
                    if (idx >= 0) pendingBills[idx] = entry;
                    else pendingBills.unshift(entry);
                } else {
                    pendingBills.unshift(entry);
                }
            }

            function lineFingerprint(r) {
                const qty = Number(r.qty);
                if (!Number.isFinite(qty)) return '';
                return [
                    Number(r.product_id),
                    String(r.uom ?? '').trim().toLowerCase(),
                    qty.toFixed(3),
                    String(r.notes ?? '').trim(),
                ].join('|');
            }

            function replacePendingBillsFromSync(orders) {
                const tbody = document.getElementById('posPendingBillsBody');
                if (!tbody) return;

                pendingBills.length = 0;
                if (!Array.isArray(orders) || orders.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="10" class="text-secondary">No pending bills.</td></tr>';
                    updateHeldCountBadge(0);
                    return;
                }

                orders.forEach((order) => syncPendingBillsArray(order, true));
                tbody.innerHTML = orders.map((order) => pendingBillRowHtml(order)).join('');
                updateHeldCountBadge(orders.length);
            }

            function mergeServerLineIntoCart(serverLine) {
                const p = products.find(x => Number(x.id) === Number(serverLine.product_id));
                if (!p) return;

                const uom = canonicalUomForLine(serverLine.uom, p);
                cart.push({
                    product_id: p.id,
                    name: p.name,
                    uom,
                    uoms: [p.uom, ...(p.uoms || []).map(x => x.uom).filter(u => u !== p.uom)],
                    qty: Number(serverLine.qty || 1),
                    unit_price: Number(serverLine.unit_price || p.price || 0),
                    tax_percent: posTaxMode === 'line' ? Number(serverLine.tax_percent || 0) : 0,
                    notes: String(serverLine.notes || ''),
                    kitchen_served: !!serverLine.kitchen_served,
                    kitchen_pending: serverLine.kitchen_pending !== false && !serverLine.kitchen_served,
                    kitchen_locked_qty: kitchenLockedFromResume(serverLine),
                });
            }

            function applyResumedOrderSync(resumed) {
                if (!resumed || !Array.isArray(resumed.items)) return;

                const resumeId = Number(document.getElementById('resumeOrderIdInput')?.value || 0);
                if (!resumeId || Number(resumed.id) !== resumeId) return;

                let changed = false;
                resumed.items.forEach((serverLine) => {
                    const fp = lineFingerprint(serverLine);
                    const idx = cart.findIndex((r) => lineFingerprint(r) === fp);
                    if (idx >= 0) {
                        const row = cart[idx];
                        const served = !!serverLine.kitchen_served;
                        const pending = !!serverLine.kitchen_pending;
                        const locked = kitchenLockedFromResume(serverLine);
                        if (row.kitchen_served !== served || row.kitchen_pending !== pending || row.kitchen_locked_qty !== locked) {
                            row.kitchen_served = served;
                            row.kitchen_pending = pending;
                            row.kitchen_locked_qty = locked;
                            changed = true;
                        }
                    } else {
                        mergeServerLineIntoCart(serverLine);
                        changed = true;
                    }
                });

                if (changed) {
                    applySaleModePricingToCart(true);
                    renderCart();
                }
            }

            let posSyncInFlight = false;
            let lastPendingSyncKey = '';
            let lastResumedSyncKey = '';

            async function pollPosSync() {
                if (posSyncInFlight || document.hidden) return;
                posSyncInFlight = true;
                try {
                    const resumeId = document.getElementById('resumeOrderIdInput')?.value || '';
                    const url = resumeId
                        ? `${posSyncRoute}?resume_order_id=${encodeURIComponent(resumeId)}`
                        : posSyncRoute;
                    const res = await fetch(url, {
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    if (!res.ok) return;
                    const data = await res.json();
                    if (Array.isArray(data.pending)) {
                        const pendingKey = JSON.stringify(data.pending);
                        if (pendingKey !== lastPendingSyncKey) {
                            lastPendingSyncKey = pendingKey;
                            replacePendingBillsFromSync(data.pending);
                        }
                    }
                    if (data.resumed) {
                        const resumedKey = JSON.stringify(data.resumed);
                        if (resumedKey !== lastResumedSyncKey) {
                            lastResumedSyncKey = resumedKey;
                            applyResumedOrderSync(data.resumed);
                        }
                    }
                } catch (_) {
                    // ignore transient network errors
                } finally {
                    posSyncInFlight = false;
                }
            }

            function prependOrUpdatePendingBillRow(order, isUpdate) {
                const tbody = document.getElementById('posPendingBillsBody');
                if (!tbody) return;

                const emptyRow = tbody.querySelector('td[colspan="10"]');
                if (emptyRow) emptyRow.closest('tr')?.remove();

                const rowHtml = pendingBillRowHtml(order);
                if (isUpdate) {
                    const existing = tbody.querySelector(`tr[data-pending-order-id="${order.id}"]`);
                    if (existing) {
                        existing.outerHTML = rowHtml;
                        return;
                    }
                }
                tbody.insertAdjacentHTML('afterbegin', rowHtml);
            }

            function updateHeldCountBadge(count) {
                const countEl = document.getElementById('posHeldTabCount');
                if (countEl) {
                    countEl.textContent = String(count);
                }

                const countNote = document.querySelector('.pos-held-count-note');
                if (countNote) {
                    if (count > 0) {
                        countNote.textContent = count === 1 ? '1 pending bill hai' : `${count} pending bills hain`;
                        countNote.classList.remove('d-none');
                    } else {
                        countNote.textContent = '';
                        countNote.classList.add('d-none');
                    }
                }
            }

            function resetPosForNewBill() {
                cart.length = 0;
                renderCart();
                document.getElementById('resumeOrderIdInput').value = '';
                payments.length = 0;
                payments.push({method: 'cash', amount: 0});
                autoPaymentAmount = true;
                renderPayments();
                if (guestNameInput) guestNameInput.value = '';
                if (waiterNameInput) waiterNameInput.value = '';
                if (serveTimeInput) serveTimeInput.value = '';
                if (tableSelect) tableSelect.value = '';
                if (checkedInRoomSelect) checkedInRoomSelect.selectedIndex = 0;
                selectedContactId = null;
                selectedBox?.classList.add('d-none');
                if (contactSearch) contactSearch.value = '';
                applyCustomerTypeDefaults();
                productSearchInput?.focus();

                const url = new URL(window.location.href);
                if (url.searchParams.has('resume_order')) {
                    url.searchParams.delete('resume_order');
                    window.history.replaceState({}, '', url.pathname + url.search);
                }
            }

            async function submitHoldOrder() {
                if (!prepareOrderSubmit('hold')) return;

                const holdBtn = document.getElementById('holdBtn');
                const form = document.getElementById('submitForm');
                const clientTotals = calcCartTotals();
                const formData = new FormData(form);
                formData.set('items', JSON.stringify(cartItemsForSubmit()));
                formData.set('client_grand_total', String(clientTotals.grand));
                formData.set('client_subtotal', String(clientTotals.subtotal));
                formData.set('client_discount_total', String(clientTotals.discount));
                formData.set('client_tax_total', String(clientTotals.tax));

                if (holdBtn) holdBtn.disabled = true;
                try {
                    const res = await fetch('{{ route('pos.hold') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
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
                        syncPendingBillsArray(data.order, !!data.updated);
                        prependOrUpdatePendingBillRow(data.order, !!data.updated);
                    }
                    if (typeof data.held_count === 'number') {
                        updateHeldCountBadge(data.held_count);
                    }
                    resetPosForNewBill();
                } catch (e) {
                    alert(e.message || 'Hold failed.');
                } finally {
                    if (holdBtn) holdBtn.disabled = false;
                }
            }

            function submitOrder(mode, cashTenderMeta) {
                if (!prepareOrderSubmit(mode, cashTenderMeta)) return;
                const form = document.getElementById('submitForm');
                form.action = mode === 'checkout' ? '{{ route('pos.checkout') }}' : '{{ route('pos.hold') }}';
                form.submit();
            }

            productSearchInput?.addEventListener('input', () => openProductSearchFromInput());
            productSearchInput?.addEventListener('focus', () => {
                if (!productSearchInput.value.trim()) {
                    hideProductDropdown();
                } else {
                    openProductSearchFromInput();
                }
            });
            productSearchInput?.addEventListener('keydown', (e) => {
                const list = productSearchFiltered;
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (productSearchDropdown.classList.contains('d-none')) {
                        openProductSearchFromInput();
                        return;
                    }
                    if (list.length) {
                        productSearchHighlight = Math.min(productSearchHighlight + 1, list.length - 1);
                        renderProductDropdown();
                    }
                    return;
                }
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (list.length) {
                        productSearchHighlight = Math.max(productSearchHighlight - 1, 0);
                        renderProductDropdown();
                    }
                    return;
                }
                if (e.key === 'Escape') {
                    e.preventDefault();
                    hideProductDropdown();
                    return;
                }
                if (e.key === 'Enter') {
                    const q = productSearchInput.value.toLowerCase().trim();
                    const fresh = filterProducts(productSearchInput.value);
                    if (list.length && productSearchHighlight >= 0 && productSearchHighlight < list.length) {
                        e.preventDefault();
                        addProductById(list[productSearchHighlight].id);
                        return;
                    }
                    if (fresh.length === 1) {
                        e.preventDefault();
                        addProductById(fresh[0].id);
                        return;
                    }
                    const byBarcode = fresh.find(p => (p.barcode || '').toLowerCase() === q);
                    if (byBarcode) {
                        e.preventDefault();
                        addProductById(byBarcode.id);
                        return;
                    }
                    const bySku = fresh.find(p => (p.sku || '').toLowerCase() === q);
                    if (bySku) {
                        e.preventDefault();
                        addProductById(bySku.id);
                    }
                }
            });
            document.addEventListener('click', (e) => {
                if (!productSearchInput || !productSearchDropdown) return;
                if (e.target === productSearchInput || productSearchDropdown.contains(e.target)) return;
                hideProductDropdown();
            });
            document.getElementById('addPaymentBtn').addEventListener('click', () => {
                autoPaymentAmount = false;
                payments.push({method: 'cash', amount: 0});
                renderPayments();
            });

            const payModalEl = document.getElementById('posPayModal');
            let payModalBs = null;
            let payModalUsingFallback = false;

            function getPayModalBs() {
                const Modal = window.bootstrap && window.bootstrap.Modal;
                if (!payModalEl || !Modal) return null;
                if (!payModalBs) {
                    payModalBs = Modal.getOrCreateInstance(payModalEl);
                }
                return payModalBs;
            }

            function showPayModalFallback() {
                if (!payModalEl) return;
                payModalUsingFallback = true;
                payModalEl.classList.add('show');
                payModalEl.style.display = 'block';
                payModalEl.removeAttribute('aria-hidden');
                payModalEl.setAttribute('aria-modal', 'true');
                document.body.classList.add('modal-open');
                document.body.style.overflow = 'hidden';
                if (!document.getElementById('posPayModalBackdrop')) {
                    const b = document.createElement('div');
                    b.className = 'modal-backdrop fade show';
                    b.id = 'posPayModalBackdrop';
                    b.addEventListener('click', closePosPayModal);
                    document.body.appendChild(b);
                }
            }

            function hidePayModalFallback() {
                if (!payModalEl) return;
                payModalEl.classList.remove('show');
                payModalEl.style.display = 'none';
                payModalEl.setAttribute('aria-hidden', 'true');
                payModalEl.removeAttribute('aria-modal');
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.getElementById('posPayModalBackdrop')?.remove();
                payModalUsingFallback = false;
            }

            function closePosPayModal() {
                if (payModalUsingFallback) {
                    hidePayModalFallback();
                } else {
                    getPayModalBs()?.hide();
                }
                document.getElementById('payModalErr')?.classList.add('d-none');
            }

            function updatePayModalChange() {
                const dueEl = document.getElementById('payModalDue');
                const grand = parseFloat(dueEl?.dataset.grand || '0') || 0;
                const rec = parseFloat(document.getElementById('payModalReceived')?.value || '0') || 0;
                const change = Math.max(0, rec - grand);
                const chEl = document.getElementById('payModalChange');
                if (chEl) chEl.textContent = fmtMoney(change);
            }

            function openCashPayModal(grand) {
                const dueEl = document.getElementById('payModalDue');
                const recInp = document.getElementById('payModalReceived');
                const errEl = document.getElementById('payModalErr');
                if (!dueEl || !recInp || !payModalEl) {
                    submitOrder('checkout');
                    return;
                }
                hidePayModalFallback();
                payModalUsingFallback = false;
                dueEl.textContent = fmtMoney(grand);
                dueEl.dataset.grand = String(grand);
                recInp.value = '';
                if (errEl) {
                    errEl.classList.add('d-none');
                    errEl.textContent = '';
                }
                updatePayModalChange();
                const m = getPayModalBs();
                if (m) {
                    payModalUsingFallback = false;
                    m.show();
                } else {
                    showPayModalFallback();
                }
                setTimeout(() => recInp.focus(), 350);
            }

            document.getElementById('payModalReceived')?.addEventListener('input', updatePayModalChange);
            document.getElementById('payModalReceived')?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    document.getElementById('payModalConfirm')?.click();
                }
            });

            payModalEl?.querySelectorAll('[data-pay-modal-close]').forEach(el => {
                el.addEventListener('click', closePosPayModal);
            });

            document.querySelectorAll('.pay-quick').forEach(btn => {
                btn.addEventListener('click', () => {
                    const dueEl = document.getElementById('payModalDue');
                    const grand = parseFloat(dueEl?.dataset.grand || '0') || 0;
                    const inp = document.getElementById('payModalReceived');
                    if (!inp) return;
                    const add = btn.getAttribute('data-add');
                    if (add === 'exact') {
                        inp.value = fmtMoney(grand);
                    } else {
                        let base = parseFloat(inp.value);
                        if (!Number.isFinite(base) || String(inp.value).trim() === '') {
                            base = grand;
                        }
                        inp.value = fmtMoney(base + parseFloat(add));
                    }
                    updatePayModalChange();
                });
            });

            document.getElementById('payModalConfirm')?.addEventListener('click', () => {
                const dueEl = document.getElementById('payModalDue');
                const grand = parseFloat(dueEl?.dataset.grand || '0') || 0;
                const rec = parseFloat(document.getElementById('payModalReceived')?.value || '0') || 0;
                const errEl = document.getElementById('payModalErr');
                if (rec + 0.009 < grand) {
                    if (errEl) {
                        errEl.textContent = 'Received amount must be at least the amount due (وصول شدہ رقم کل سے کم ہے).';
                        errEl.classList.remove('d-none');
                    } else {
                        alert('Received amount must be at least the amount due.');
                    }
                    return;
                }
                errEl?.classList.add('d-none');
                if (payments.length === 1 && payments[0].method === 'cash') {
                    payments[0].amount = grand;
                }
                closePosPayModal();
                const ch = Math.max(0, rec - grand);
                submitOrder('checkout', { tendered: fmtMoney(rec), change: fmtMoney(ch) });
            });

            payModalEl?.addEventListener('hidden.bs.modal', () => {
                payModalUsingFallback = false;
                document.getElementById('payModalErr')?.classList.add('d-none');
            });

            document.getElementById('checkoutBtn')?.addEventListener('click', () => {
                if (!cart.length) return alert('Add at least one item.');
                if (isCreditMode) {
                    submitOrder('checkout');
                    return;
                }
                if (orderType === 'refund') {
                    submitOrder('checkout');
                    return;
                }
                if (!payments.length) return alert('Add at least one payment.');
                const grand = getGrandTotal();
                if (payments.length === 1 && payments[0].method === 'cash') {
                    payments[0].amount = grand;
                    renderPayments();
                }
                const paySum = payments.reduce((s, p) => s + Number(p.amount || 0), 0);
                if (Math.abs(paySum - grand) > 0.02) {
                    return alert('Payments total must match grand total. Adjust payment amounts.');
                }
                const singleCash = payments.length === 1 && payments[0].method === 'cash';
                if (singleCash && orderType === 'sale') {
                    autoPaymentAmount = true;
                    openCashPayModal(grand);
                    return;
                }
                submitOrder('checkout');
            });
            document.getElementById('printDraftBillBtn')?.addEventListener('click', () => {
                if (!posAllowBillPrint) return;
                if (!cart.length) return alert('Add at least one item.');
                printDraftBillInline();
            });

            document.getElementById('holdBtn')?.addEventListener('click', () => submitHoldOrder());
            document.getElementById('toggleRefundBtn')?.addEventListener('click', (e) => {
                orderType = orderType === 'sale' ? 'refund' : 'sale';
                e.target.classList.toggle('btn-danger');
                e.target.classList.toggle('btn-outline-secondary');
                e.target.textContent = orderType === 'refund' ? 'Refund Mode ON' : 'Toggle Refund Mode';
            });

            function applySaleModePricingToCart(resetCustomerPrices = false) {
                const includeGas = staffIncludeGasCheckbox?.checked ?? staffIncludeGas;
                staffIncludeGas = includeGas;
                cart.forEach((r) => {
                    const p = products.find(x => Number(x.id) === Number(r.product_id));
                    if (!p) return;
                    if (saleMode === 'staff' || isMessBillCustomer()) {
                        const repriced = unitPriceForProductUomStrict(p, r.uom);
                        if (repriced != null) {
                            r.unit_price = repriced;
                        }
                    } else if (resetCustomerPrices) {
                        r.unit_price = unitPriceForProductUom(p, r.uom);
                    }
                });
                renderCart();
            }

            function pricesNearlyEqual(left, right) {
                return Math.abs(Number(left) - Number(right)) < 0.02;
            }

            function staffPricesForProduct(product) {
                const cost = Number(product?.cost || 0);
                const gas = gasChargeForProduct(product);
                const withoutGas = Math.round((cost + Number.EPSILON) * 100) / 100;
                const withGas = Math.round((cost + gas + Number.EPSILON) * 100) / 100;
                return { withoutGas, withGas };
            }

            function setSaleModeHighlight(mode, options = {}) {
                saleMode = mode === 'staff' ? 'staff' : 'customer';
                const customerToggle = document.getElementById('saleModeCustomer');
                const staffToggle = document.getElementById('saleModeStaff');
                if (customerToggle) customerToggle.checked = saleMode === 'customer';
                if (staffToggle) staffToggle.checked = saleMode === 'staff';
                if (saleModeInput) saleModeInput.value = saleMode;
                if (saleMode === 'staff' && !isMessBillCustomer() && options.includeGas === undefined) {
                    options.includeGas = true;
                }
                if (isMessBillCustomer()) {
                    options.includeGas = false;
                }
                if (options.includeGas !== undefined && staffIncludeGasCheckbox) {
                    staffIncludeGasCheckbox.checked = !!options.includeGas;
                    staffIncludeGas = !!options.includeGas;
                }
                syncSaleModeUi();
                if (productSearchInput?.value.trim()) {
                    openProductSearchFromInput();
                }
            }

            function applyCustomerTypeDefaults(options = {}) {
                const customerType = selectedCustomerType();
                const preserveSaleMode = !!options.preserveSaleMode;
                const customerToggle = document.getElementById('saleModeCustomer');
                const staffToggle = document.getElementById('saleModeStaff');

                if (customerType === 'ast_offr') {
                    setSaleModeHighlight('staff');
                    setCreditMode(true, { lock: true });
                    if (customerToggle) customerToggle.disabled = true;
                    if (staffToggle) staffToggle.disabled = false;
                    if (tableSelect) tableSelect.value = '';
                    if (waiterNameInput) waiterNameInput.value = '';
                    if (serveTimeInput) serveTimeInput.value = '';
                    if (!preserveSaleMode && staffIncludeGasCheckbox) {
                        staffIncludeGasCheckbox.checked = false;
                        staffIncludeGas = false;
                    }
                    syncSaleModeUi();
                    applySaleModePricingToCart(true);
                    if (posCustomerCardTitle) posCustomerCardTitle.textContent = 'Officer (Credit)';
                    if (contactSearch) contactSearch.placeholder = 'Search officer name or phone…';
                    if (productSearchInput?.value.trim()) {
                        openProductSearchFromInput();
                    }
                } else if (customerType === 'mess_use') {
                    if (!preserveSaleMode) {
                        setSaleModeHighlight('customer');
                    }
                    setCreditMode(false);
                    if (customerToggle) customerToggle.disabled = false;
                    if (staffToggle) staffToggle.disabled = false;
                    if (contactSearch) contactSearch.placeholder = 'Search contact name or phone…';
                    if (!preserveSaleMode) {
                        applySaleModePricingToCart(true);
                    }
                } else if (customerType === 'booking') {
                    if (!preserveSaleMode) {
                        setSaleModeHighlight('customer');
                    }
                    setCreditMode(false);
                    if (customerToggle) customerToggle.disabled = false;
                    if (staffToggle) staffToggle.disabled = false;
                    if (contactSearch) contactSearch.placeholder = 'Search contact name or phone…';
                    if (!preserveSaleMode) {
                        applySaleModePricingToCart(true);
                    }
                }

                syncCustomerTypeUi();
            }

            function handleCustomerTypeChange() {
                try {
                    applyCustomerTypeDefaults();
                } catch (error) {
                    console.error('POS customer type defaults failed:', error);
                }
                if (selectedCustomerType() !== 'ast_offr') {
                    selectedContactId = null;
                    selectedBox?.classList.add('d-none');
                    if (contactSearch) contactSearch.value = '';
                }
                hideProductDropdown();
            }

            function saleModeForLine(product, linePrice, uomCode) {
                const factor = factorForUom(product, uomCode) ?? 1;
                const price = Number(linePrice);
                const customerPrice = Number(product?.price || 0) * factor;
                const staffPrices = staffPricesForProduct(product);
                const staffWithoutGas = staffPrices.withoutGas * factor;
                const staffWithGas = staffPrices.withGas * factor;

                const candidates = [
                    { mode: 'staff', includeGas: true, price: staffWithGas },
                    { mode: 'staff', includeGas: false, price: staffWithoutGas },
                    { mode: 'customer', includeGas: false, price: customerPrice },
                ];

                let best = null;
                let bestDiff = Infinity;
                candidates.forEach((candidate) => {
                    const diff = Math.abs(price - candidate.price);
                    if (diff < bestDiff) {
                        bestDiff = diff;
                        best = candidate;
                    }
                });

                if (best && bestDiff < 0.02) {
                    return best;
                }

                if (customerPrice > 0 && price < customerPrice - 0.02) {
                    const staffDiff = Math.min(
                        Math.abs(price - staffWithoutGas),
                        Math.abs(price - staffWithGas)
                    );
                    if (staffDiff <= Math.abs(price - customerPrice)) {
                        return {
                            mode: 'staff',
                            includeGas: Math.abs(price - staffWithGas) < Math.abs(price - staffWithoutGas),
                        };
                    }
                }

                return best || { mode: 'customer', includeGas: false };
            }

            function syncSaleModeHighlightFromCart() {
                if (!cart.length) return;

                let staffVotes = 0;
                let customerVotes = 0;
                let withGasVotes = 0;
                let withoutGasVotes = 0;

                cart.forEach((row) => {
                    const product = products.find(x => Number(x.id) === Number(row.product_id));
                    if (!product) return;

                    const detected = saleModeForLine(product, row.unit_price, row.uom);
                    if (detected.mode === 'staff') {
                        staffVotes++;
                        if (detected.includeGas) withGasVotes++;
                        else withoutGasVotes++;
                    } else {
                        customerVotes++;
                    }
                });

                if (staffVotes > customerVotes) {
                    setSaleModeHighlight('staff', {
                        includeGas: withGasVotes >= withoutGasVotes,
                    });
                } else if (customerVotes > 0) {
                    setSaleModeHighlight('customer');
                }
            }

            saleModeToggleInputs.forEach((input) => {
                input.addEventListener('change', () => {
                    if (!input.checked) return;
                    if (selectedCustomerType() === 'ast_offr' && input.value === 'customer') {
                        const staffToggle = document.getElementById('saleModeStaff');
                        if (staffToggle) staffToggle.checked = true;
                        return;
                    }
                    setSaleModeHighlight(input.value === 'staff' ? 'staff' : 'customer');
                    applySaleModePricingToCart(true);
                });
            });

            staffIncludeGasCheckbox?.addEventListener('change', () => {
                staffIncludeGas = staffIncludeGasCheckbox.checked;
                syncSaleModeUi();
                applySaleModePricingToCart(true);
                if (productSearchInput?.value.trim()) {
                    openProductSearchFromInput();
                } else {
                    hideProductDropdown();
                }
            });

            checkedInRoomSelect?.addEventListener('change', () => {
                const selected = checkedInRoomSelect.options[checkedInRoomSelect.selectedIndex];
                const guestName = selected?.dataset?.guestName || '';

                if (guestNameInput && guestName) {
                    guestNameInput.value = guestName;
                }
                if (customerTypeSelect) {
                    customerTypeSelect.value = 'booking';
                    if (typeof window.posOnCustomerTypeChange === 'function') {
                        window.posOnCustomerTypeChange();
                    } else {
                        handleCustomerTypeChange();
                    }
                }
            });

            function pushLinesIntoCart(lineRows) {
                if (!Array.isArray(lineRows)) return;
                lineRows.forEach(ri => {
                    const p = products.find(x => Number(x.id) === Number(ri.product_id));
                    if (!p) return;
                    const uom = canonicalUomForLine(ri.uom, p);
                    cart.push({
                        product_id: p.id,
                        name: p.name,
                        uom,
                        uoms: [p.uom, ...(p.uoms || []).map(x => x.uom).filter(u => u !== p.uom)],
                        qty: Number(ri.qty || 1),
                        unit_price: Number(ri.unit_price || p.price || 0),
                        tax_percent: posTaxMode === 'line' ? Number(ri.tax_percent || 0) : 0,
                        notes: String(ri.notes || ''),
                        kitchen_served: !!ri.kitchen_served,
                        kitchen_pending: !!ri.kitchen_pending,
                        kitchen_locked_qty: kitchenLockedFromResume(ri),
                    });
                });
            }

            hideProductDropdown();
            staffIncludeGas = staffIncludeGasCheckbox?.checked ?? staffIncludeGas;
            updateOrderDateTime();
            setInterval(updateOrderDateTime, 1000);
            setInterval(pollPosSync, 4000);
            pollPosSync();
            window.addEventListener('pos-customer-type-change', handleCustomerTypeChange);
            payments.push({method: 'cash', amount: 0});
            if (posTablesEnabled && tableSelect && resumeTableId) {
                tableSelect.value = String(resumeTableId);
            }

            const retryLines = checkoutRetry && Array.isArray(checkoutRetry.items) ? checkoutRetry.items : null;
            if (retryLines && retryLines.length) {
                pushLinesIntoCart(retryLines);
                if (Array.isArray(checkoutRetry.payments) && checkoutRetry.payments.length) {
                    payments.length = 0;
                    checkoutRetry.payments.forEach(p => {
                        payments.push({ method: p.method || 'cash', amount: Number(p.amount) || 0 });
                    });
                    autoPaymentAmount = false;
                }
                if (checkoutRetry.sale_mode === 'staff') {
                    setSaleModeHighlight('staff', { includeGas: !!checkoutRetry.staff_include_gas });
                }
                if (checkoutRetry.is_credit) {
                    setCreditMode(true);
                    const c = checkoutRetry.contact;
                    if (c && c.id) {
                        selectContact(String(c.id), c.name || '', c.phone || '');
                    }
                }
                if (checkoutRetry.type === 'refund') {
                    orderType = 'refund';
                    const tBtn = document.getElementById('toggleRefundBtn');
                    if (tBtn) {
                        tBtn.classList.add('btn-danger');
                        tBtn.classList.remove('btn-outline-secondary');
                        tBtn.textContent = 'Refund Mode ON';
                    }
                }
                if (checkoutRetry.order_notes && orderNotesInput) {
                    orderNotesInput.value = String(checkoutRetry.order_notes);
                }
            } else if (resumeItems.length) {
                pushLinesIntoCart(resumeItems);
            }

            if (cart.length) {
                if (resumeItems.length && resumeSaleMode) {
                    setSaleModeHighlight(resumeSaleMode === 'staff' ? 'staff' : 'customer');
                } else if (!resumeItems.length) {
                    syncSaleModeHighlightFromCart();
                }
            } else if (resumeSaleMode === 'staff') {
                setSaleModeHighlight('staff');
            } else if (!resumeItems.length && !(retryLines && retryLines.length)) {
                setSaleModeHighlight('customer');
            }

            if (resumeItems.length || (retryLines && retryLines.length)) {
                applyCustomerTypeDefaults({ preserveSaleMode: true });
            } else {
                applyCustomerTypeDefaults();
            }
            syncCustomerTypeUi();

            if (selectedCustomerType() === 'ast_offr' && !selectedContactId) {
                const resumedGuest = (guestNameInput?.value || '').trim();
                if (resumedGuest) {
                    const match = posContacts.find((contact) =>
                        String(contact.name || '').trim().localeCompare(resumedGuest, undefined, { sensitivity: 'accent' }) === 0
                    );
                    if (match) {
                        selectContact(String(match.id), match.name || '', match.phone || '');
                    }
                }
            }

            applySaleModePricingToCart();
            renderPayments();
            renderSummary();
            document.getElementById('billTaxPercentInput')?.addEventListener('input', () => renderSummary());
            document.getElementById('billDiscountPercentInput')?.addEventListener('input', () => renderCart());

            @if(session('pos_active_tab') === 'paid')
            (function () {
                const paidTabBtn = document.getElementById('pos-paid-tab');
                if (paidTabBtn && typeof bootstrap !== 'undefined') {
                    bootstrap.Tab.getOrCreateInstance(paidTabBtn).show();
                    const lastId = {{ (int) session('last_pos_order_id', 0) }};
                    if (lastId > 0) {
                        requestAnimationFrame(() => {
                            document.querySelector(`#posPaidBillsBody tr[data-paid-order-id="${lastId}"]`)?.scrollIntoView({ block: 'nearest' });
                        });
                    }
                }
            })();
            @endif

            document.getElementById('posPaidBillsBody')?.addEventListener('click', (event) => {
                const btn = event.target.closest('[data-order-details]');
                if (!btn) return;
                openOrderDetails(btn.getAttribute('data-order-details'));
            });

            document.getElementById('posPendingBillsBody')?.addEventListener('click', (event) => {
                const btn = event.target.closest('[data-order-details]');
                if (!btn) return;
                openOrderDetails(btn.getAttribute('data-order-details'));
            });

            paidBillModalEl?.querySelectorAll('[data-paid-bill-modal-close]').forEach((el) => {
                el.addEventListener('click', () => getPaidBillModalBs()?.hide());
            });

            productSearchInput?.focus();
        </script>
@endsection
