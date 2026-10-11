@extends('layouts.guest')
@section('title', 'Forgot Password')
@section('auth-content')
<h4 class="fw-bold mb-1" style="color:var(--primary);">Lupa Password</h4>
<p class="text-muted mb-4" style="font-size:0.85rem;">Masukkan email Anda untuk menerima link reset password</p>

@if (session('status'))
<div class="alert alert-success" style="font-size:0.85rem;">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf
    <div class="mb-4">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Kirim Link Reset</button>
    <div class="text-center mt-3">
        <a href="{{ route('login') }}" style="font-size:0.8rem;color:var(--accent);text-decoration:none;">Kembali ke Login</a>
    </div>
</form>
@endsection
