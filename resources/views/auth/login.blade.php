@extends('layouts.guest')
@section('title', 'Login')
@section('auth-content')

<div class="auth-header">
    <h4 class="auth-title">Selamat Datang</h4>
    <p class="auth-subtitle">Silakan masuk ke akun Anda untuk melanjutkan</p>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="glass-field mb-3">
        <div class="glass-input-wrapper @error('email') has-error @enderror">
            <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            <input type="email" name="email" class="glass-input" value="{{ old('email') }}" placeholder=" " required autofocus>
            <label class="glass-label">Alamat Email</label>
        </div>
        @error('email') <div class="glass-error">{{ $message }}</div> @enderror
    </div>

    <div class="glass-field mb-3">
        <div class="glass-input-wrapper @error('password') has-error @enderror">
            <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            <input type="password" name="password" class="glass-input" placeholder=" " required>
            <label class="glass-label">Kata Sandi</label>
        </div>
        @error('password') <div class="glass-error">{{ $message }}</div> @enderror
    </div>

    <div class="auth-options">
        <label class="toggle-label">
            <input type="checkbox" name="remember" class="toggle-input" id="remember">
            <span class="toggle-track">
                <span class="toggle-thumb"></span>
            </span>
            <span class="toggle-text">Ingat Saya</span>
        </label>
        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
        @endif
    </div>

    <button type="submit" class="login-gradient-btn">
        <span>Masuk</span>
        <svg class="btn-arrow" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        <div class="btn-glow"></div>
    </button>
</form>
@endsection
