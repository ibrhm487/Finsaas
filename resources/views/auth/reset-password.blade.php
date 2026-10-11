@extends('layouts.guest')
@section('title', 'Reset Password')
@section('auth-content')
<h4 class="fw-bold mb-1" style="color:var(--primary);">Reset Password</h4>
<p class="text-muted mb-4" style="font-size:0.85rem;">Buat password baru Anda</p>

<form method="POST" action="{{ route('password.store') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $email) }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-3">
        <label class="form-label">Password Baru</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="mb-4">
        <label class="form-label">Konfirmasi Password Baru</label>
        <input type="password" name="password_confirmation" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Reset Password</button>
</form>
@endsection
