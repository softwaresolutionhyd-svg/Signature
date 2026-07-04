@extends('layouts.auth')

@section('title', __('Login'))

@php($loginBrand = login_page_branding())

@section('content')
<div class="auth-shell">
    <div class="auth-panel">
        <div class="auth-panel-inner">
            <div class="auth-brand-top">
                @if (! empty(trim($loginBrand['company_logo'] ?? '')))
                    <div class="mb-2">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($loginBrand['company_logo']) }}"
                             alt="{{ $loginBrand['company_name'] }}"
                             class="company-logo">
                    </div>
                @endif
                <h1 class="auth-company-name">{{ $loginBrand['company_name'] }}</h1>
                @if (! empty(trim($loginBrand['company_phone'] ?? '')))
                    <p class="auth-contact mb-0">
                        <i class="bi bi-telephone me-1"></i>
                        <a href="tel:{{ preg_replace('/\s+/', '', $loginBrand['company_phone']) }}">{{ $loginBrand['company_phone'] }}</a>
                    </p>
                @endif
            </div>

            <p class="auth-heading">{{ __('Sign in to continue') }}</p>

            <form method="POST" action="{{ route('login') }}" novalidate autocomplete="off">
                @csrf

                <div class="auth-input-wrap">
                    <i class="bi bi-envelope input-icon" aria-hidden="true"></i>
                    <input id="login" type="text" name="login" value="{{ old('login') }}"
                           class="form-control auth-no-autofill @error('login') is-invalid @enderror"
                           placeholder="{{ __('Username') }}" required
                           autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                           readonly
                           onfocus="this.removeAttribute('readonly')"
                           autofocus>
                    @error('login')
                        <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-input-wrap">
                    <i class="bi bi-lock input-icon" aria-hidden="true"></i>
                    <input id="password" type="password" name="password"
                           class="form-control auth-no-autofill @error('password') is-invalid @enderror"
                           placeholder="{{ __('Password') }}" required
                           autocomplete="off" autocorrect="off" spellcheck="false"
                           readonly
                           onfocus="this.removeAttribute('readonly')">
                    @error('password')
                        <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="auth-options">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-secondary" for="remember">{{ __('Remember Me') }}</label>
                    </div>
                </div>

                <button type="submit" class="auth-btn-submit">{{ __('Login') }}</button>

                <a class="auth-forgot text-decoration-none" href="{{ route('password-reset-request.create') }}">Password reset ki request (admin)</a>
            </form>
        </div>
    </div>
</div>

<div class="auth-footer-global">
    <img src="{{ asset('images/stair-logo.svg') }}" alt="Stair" width="36" height="36">
    <span>Stair by Software Solutions</span>
</div>
@endsection
