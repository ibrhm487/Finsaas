@extends('layouts.guest')
@section('title', 'Confirm Password')
@section('auth-content')
<h4 class="fw-bold mb-1" style="color:var(--primary);">Konfirmasi Password</h4>
<p class="text-muted mb-4" style="font-size:0.85rem;">Konfirmasi password untuk melanjutkan</p>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf
    <div class="mb-4">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Konfirmasi</button>
</form>
@endsection
