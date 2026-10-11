@extends('layouts.app')
@section('title', 'Profile')
@section('content')
@php
    $breadcrumbs = ['Profile' => route('profile.edit')];
@endphp
<div class="page-header">
    <h4>Profile</h4>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-center p-4">
            <div style="width:80px;height:80px;border-radius:20px;background:var(--accent);color:#fff;display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:700;margin:0 auto 1rem;">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <h5 class="fw-bold">{{ Auth::user()->name }}</h5>
            <p class="text-muted" style="font-size:0.85rem;">{{ Auth::user()->role?->name ?? 'User' }}</p>
            <span class="badge bg-success">{{ Auth::user()->status }}</span>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Edit Profile</div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">Hapus Akun</div>
            <div class="card-body">
                <p class="text-muted" style="font-size:0.85rem;">Setelah akun dihapus, semua data akan terhapus permanen.</p>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Hapus Akun</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Konfirmasi Hapus Akun</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus akun? Masukkan password untuk konfirmasi.</p>
                <form id="deleteForm" method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="deleteForm" class="btn btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection
