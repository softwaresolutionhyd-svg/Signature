@extends('layouts.admin')

@section('title', 'Profile — ' . config('app.name'))

@section('content')
<div class="mb-3">
    <h4 class="fw-bold mb-0">Profile</h4>
    <div class="text-secondary small">Name aur password change</div>
</div>

@if (!empty($mustChangePassword))
    <div class="alert alert-warning">
        <strong>Password change required.</strong> Admin ne temporary password diya hai — pehle naya strong password set karein.
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        {{ session('warning') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="card shadow-sm" style="max-width: 520px;">
    <div class="card-body">
        <form method="POST" action="{{ route('profile.update') }}" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label" for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}" required maxlength="150">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                <div class="form-text">Email yahan se change nahi hota.</div>
            </div>

            <hr class="my-4">

            <p class="fw-semibold small text-secondary mb-3">
                @if(!empty($mustChangePassword))
                    New password (required)
                @else
                    Password change (optional)
                @endif
            </p>

            @if(empty($mustChangePassword))
            <div class="mb-3">
                <label class="form-label" for="current_password">Current password</label>
                <input type="password" name="current_password" id="current_password"
                       class="form-control @error('current_password') is-invalid @enderror"
                       autocomplete="current-password">
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            @endif

            <div class="mb-3">
                <label class="form-label" for="password">New password</label>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       autocomplete="new-password"
                       @if(!empty($mustChangePassword)) required @endif>
                <div class="form-text">Minimum 8 characters, upper + lower + number.</div>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label class="form-label" for="password_confirmation">Confirm new password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" autocomplete="new-password"
                    @if(!empty($mustChangePassword)) required @endif>
            </div>

            <button type="submit" class="btn btn-primary">{{ !empty($mustChangePassword) ? 'Set new password' : 'Save' }}</button>
        </form>
    </div>
</div>
@endsection
