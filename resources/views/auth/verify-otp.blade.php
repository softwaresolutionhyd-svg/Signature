@extends('layouts.auth')

@section('title', 'Verify OTP')

@section('content')
<div class="auth-shell auth-shell--solo">
    <div class="auth-panel">
        <div class="auth-panel-inner">
            <div class="auth-brand-top">
                <div class="auth-logo-fallback" aria-hidden="true">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <p class="auth-welcome">Security check</p>
                <h1 class="auth-company-name">OTP Verification</h1>
                <p class="auth-contact mb-0">
                    <i class="bi bi-phone-fill"></i>
                    Code bheja gaya: <strong>{{ $maskedPhone }}</strong>
                </p>
            </div>

            <p class="auth-heading">Apne mobile par aaya hua 6-digit SMS code enter karein</p>

            @if (session('status'))
                <div class="alert alert-success small py-2">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login.verify-otp.submit') }}" class="auth-form" autocomplete="off">
                @csrf

                <label class="auth-label" for="otp">OTP Code</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-123 input-icon" aria-hidden="true"></i>
                    <input id="otp" type="text" name="otp" inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
                           class="form-control auth-no-autofill text-center @error('otp') is-invalid @enderror"
                           placeholder="000000" required autofocus autocomplete="one-time-code">
                    @error('otp')
                        <div class="invalid-feedback d-block small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="auth-btn-submit">
                    <span>Verify &amp; Sign In</span>
                    <i class="bi bi-arrow-right-short"></i>
                </button>
            </form>

            <form method="POST" action="{{ route('login.resend-otp') }}" class="mt-3 text-center">
                @csrf
                <button type="submit" class="btn btn-link auth-forgot p-0 border-0 bg-transparent">
                    <i class="bi bi-arrow-repeat"></i>
                    OTP dubara bhejein
                </button>
            </form>

            <a class="auth-forgot text-decoration-none mt-2" href="{{ route('login') }}">
                <i class="bi bi-arrow-left"></i>
                Wapas login
            </a>
        </div>
    </div>
</div>
@endsection
