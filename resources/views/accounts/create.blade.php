@php
    $breadcrumbs = ['Chart of Accounts' => route('accounts.index'), 'Tambah Akun' => ''];
@endphp
@extends('layouts.app')
@section('title', 'Tambah Akun')
@section('content')
<div class="page-header">
    <h4>Tambah Akun</h4>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('accounts.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Kode Akun</label>
                    <input type="text" name="kode_akun" class="form-control @error('kode_akun') is-invalid @enderror" value="{{ old('kode_akun') }}" required>
                    @error('kode_akun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Nama Akun</label>
                    <input type="text" name="nama_akun" class="form-control @error('nama_akun') is-invalid @enderror" value="{{ old('nama_akun') }}" required>
                    @error('nama_akun') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
                        <option value="">Pilih Kategori</option>
                        <option value="Aset" {{ old('kategori') == 'Aset' ? 'selected' : '' }}>Aset</option>
                        <option value="Kewajiban" {{ old('kategori') == 'Kewajiban' ? 'selected' : '' }}>Kewajiban</option>
                        <option value="Modal" {{ old('kategori') == 'Modal' ? 'selected' : '' }}>Modal</option>
                        <option value="Pendapatan" {{ old('kategori') == 'Pendapatan' ? 'selected' : '' }}>Pendapatan</option>
                        <option value="Beban" {{ old('kategori') == 'Beban' ? 'selected' : '' }}>Beban</option>
                    </select>
                    @error('kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Saldo Normal</label>
                    <select name="saldo_normal" class="form-select @error('saldo_normal') is-invalid @enderror" required>
                        <option value="">Pilih Saldo Normal</option>
                        <option value="Debit" {{ old('saldo_normal') == 'Debit' ? 'selected' : '' }}>Debit</option>
                        <option value="Kredit" {{ old('saldo_normal') == 'Kredit' ? 'selected' : '' }}>Kredit</option>
                    </select>
                    @error('saldo_normal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('accounts.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
