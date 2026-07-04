<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Login')) — {{ config('app.name', 'Stair') }}</title>
    <meta name="theme-color" content="#7c3aed">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/display-quality.css') }}?v=3">
    <style>
        :root {
            --auth-accent: #7c3aed;
            --auth-accent-hover: #5b21b6;
            --auth-accent-light: #a78bfa;
            --auth-surface: rgba(255, 255, 255, 0.98);
        }
        body.auth-body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
            margin: 0;
            background: #0b1020;
            background-image:
                radial-gradient(ellipse 120% 80% at 50% -20%, rgba(124, 58, 237, 0.42), transparent),
                radial-gradient(ellipse 70% 55% at 100% 40%, rgba(99, 102, 241, 0.14), transparent),
                radial-gradient(ellipse 55% 45% at 0% 85%, rgba(124, 58, 237, 0.1), transparent),
                linear-gradient(168deg, #0b1020 0%, #111827 42%, #0a0f1a 100%);
        }
        .auth-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem 1rem 5rem;
        }
        .auth-panel {
            width: 100%;
            max-width: 420px;
            background: var(--auth-surface);
            border-radius: 1.25rem;
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.06) inset,
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 90px -24px rgba(124, 58, 237, 0.35);
            overflow: hidden;
        }
        .auth-panel-inner {
            padding: 2rem 1.75rem 1.75rem;
        }
        @media (min-width: 576px) {
            .auth-panel-inner { padding: 2.25rem 2rem 2rem; }
        }
        .auth-brand-top {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .auth-brand-top img.company-logo {
            max-height: 88px;
            max-width: 200px;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 0.75rem;
            padding: 0.5rem;
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            box-shadow: 0 4px 14px rgba(15, 23, 42, 0.06);
        }
        .auth-company-name {
            font-size: 1.35rem;
            font-weight: 700;
            color: #2d1f2a;
            letter-spacing: -0.02em;
            line-height: 1.25;
            margin: 0.75rem 0 0.25rem;
        }
        .auth-contact {
            font-size: 0.875rem;
            color: #64748b;
            margin: 0;
        }
        .auth-contact a {
            color: var(--auth-accent);
            font-weight: 500;
        }
        .auth-contact a:hover { color: var(--auth-accent-hover); }
        .auth-heading {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #8b7a85;
            margin-bottom: 1.25rem;
            text-align: center;
        }
        .auth-input-wrap {
            position: relative;
            margin-bottom: 1rem;
        }
        .auth-input-wrap .form-control {
            border-radius: 0.65rem;
            border: 1px solid #e2e8f0;
            padding: 0.65rem 0.85rem 0.65rem 2.65rem;
            font-size: 0.95rem;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .auth-input-wrap .form-control:focus {
            border-color: var(--auth-accent);
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.2);
        }
        .auth-input-wrap .input-icon {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 1.05rem;
            pointer-events: none;
            z-index: 2;
        }
        .auth-input-wrap:focus-within .input-icon { color: var(--auth-accent); }
        /* Browser autofill: no yellow box; match normal field look */
        .auth-input-wrap .form-control.auth-no-autofill:-webkit-autofill,
        .auth-input-wrap .form-control.auth-no-autofill:-webkit-autofill:hover,
        .auth-input-wrap .form-control.auth-no-autofill:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px #fff inset;
            box-shadow: 0 0 0 1000px #fff inset;
            -webkit-text-fill-color: #1e293b;
            caret-color: #1e293b;
            border: 1px solid #e2e8f0;
            transition: background-color 99999s ease-out;
        }
        .auth-input-wrap .form-control.auth-no-autofill:-webkit-autofill:focus {
            border-color: var(--auth-accent);
            -webkit-box-shadow: 0 0 0 1000px #fff inset, 0 0 0 3px rgba(124, 58, 237, 0.2);
            box-shadow: 0 0 0 1000px #fff inset, 0 0 0 3px rgba(124, 58, 237, 0.2);
        }
        .auth-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            font-size: 0.875rem;
        }
        .auth-options .form-check-input:checked {
            background-color: var(--auth-accent);
            border-color: var(--auth-accent);
        }
        .auth-btn-submit {
            width: 100%;
            border: none;
            border-radius: 0.65rem;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            font-size: 0.95rem;
            color: #fff;
            background: linear-gradient(135deg, var(--auth-accent-light) 0%, var(--auth-accent) 45%, var(--auth-accent-hover) 100%);
            box-shadow: 0 4px 16px rgba(124, 58, 237, 0.4);
            transition: transform 0.12s, box-shadow 0.12s;
        }
        .auth-btn-submit:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(124, 58, 237, 0.48);
        }
        .auth-btn-submit:active { transform: translateY(0); }
        .auth-forgot {
            display: block;
            text-align: center;
            margin-top: 1rem;
            font-size: 0.875rem;
            color: var(--auth-accent);
            font-weight: 500;
        }
        .auth-forgot:hover { color: var(--auth-accent-hover); }
        .auth-footer-global {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1rem;
            text-align: center;
            background: linear-gradient(to top, rgba(18, 13, 17, 0.97), transparent);
        }
        .auth-footer-global img { opacity: 0.92; filter: drop-shadow(0 0 12px rgba(124, 58, 237, 0.28)); }
        .auth-footer-global span {
            display: block;
            margin-top: 0.35rem;
            font-size: 0.75rem;
            color: rgba(180, 160, 172, 0.88);
        }
    </style>
</head>
<body class="auth-body">
    @yield('content')
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(() => {});
            });
        }
    </script>
</body>
</html>
