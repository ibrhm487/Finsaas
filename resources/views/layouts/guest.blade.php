<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FinSaaS') }} - @yield('title', 'Login')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/login-animations.js'])
</head>
<body>

<div class="split-layout">
    <div class="split-brand">
        <div class="brand-orb"></div>
        <div class="brand-orb-2"></div>
        <div class="brand-grid"></div>

        <div class="brand-content">
            <div class="brand-title-wrapper">
                <span class="brand-title">FinSaaS</span>
            </div>
            <p class="brand-sub">Laporan Keuangan</p>
            <p class="brand-desc">Sistem Informasi Akuntansi terintegrasi untuk pengelolaan laporan keuangan perusahaan yang akurat, cepat, dan andal.</p>
            <div class="brand-features">
                <div class="brand-feature">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Double-Entry Otomatis</span>
                </div>
                <div class="brand-feature">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Laporan Keuangan Real-time</span>
                </div>
                <div class="brand-feature">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span>Multi-Perusahaan & Multi-Role</span>
                </div>
            </div>
        </div>

        <div class="brand-footer">
            &copy; {{ date('Y') }} FinSaaS. All rights reserved.
        </div>
    </div>

    <div class="split-form">
        <div class="form-container">
            @yield('auth-content')
        </div>
    </div>
</div>

</body>
</html>
