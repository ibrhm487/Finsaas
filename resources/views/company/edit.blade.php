@php $breadcrumbs = ['Perusahaan' => route('company.edit')]; @endphp
@extends('layouts.app')
@section('title', 'Profil Perusahaan')
@section('content')
<div class="page-header"><h4>Profil Perusahaan</h4></div>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('company.update') }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Perusahaan</label>
                    <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $company->nama) }}" required>
                    @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $company->email) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $company->telepon) }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">NPWP</label>
                    <input type="text" name="npwp" class="form-control" value="{{ old('npwp', $company->npwp) }}">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3">{{ old('alamat', $company->alamat) }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    @if($company->logo)
                    <div class="mt-2"><img src="{{ asset('storage/'.$company->logo) }}" style="max-height:80px;"></div>
                    @endif
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>
@endsection
