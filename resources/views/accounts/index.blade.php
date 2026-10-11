@php
    $breadcrumbs = ['Chart of Accounts' => route('accounts.index')];
@endphp
@extends('layouts.app')
@section('title', 'Chart of Accounts')
@section('content')
<div class="page-header">
    <h4>Chart of Accounts</h4>
    <a href="{{ route('accounts.create') }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Akun
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari kode/nama akun..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="kategori" class="form-select">
                    <option value="">Semua Kategori</option>
                    <option value="Aset" {{ request('kategori') == 'Aset' ? 'selected' : '' }}>Aset</option>
                    <option value="Kewajiban" {{ request('kategori') == 'Kewajiban' ? 'selected' : '' }}>Kewajiban</option>
                    <option value="Modal" {{ request('kategori') == 'Modal' ? 'selected' : '' }}>Modal</option>
                    <option value="Pendapatan" {{ request('kategori') == 'Pendapatan' ? 'selected' : '' }}>Pendapatan</option>
                    <option value="Beban" {{ request('kategori') == 'Beban' ? 'selected' : '' }}>Beban</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('accounts.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Akun</th>
                        <th>Kategori</th>
                        <th>Saldo Normal</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($accounts as $account)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $account->kode_akun }}</span></td>
                        <td>{{ $account->nama_akun }}</td>
                        <td><span class="badge" style="background:rgba(59,130,246,0.1);color:var(--accent);">{{ $account->kategori }}</span></td>
                        <td>{{ $account->saldo_normal }}</td>
                        <td>
                            @if($account->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('accounts.edit', $account) }}" class="btn btn-sm btn-warning">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $account->id }}">Hapus</button>
                            <div class="modal fade" id="deleteModal{{ $account->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header"><h5 class="modal-title">Konfirmasi Hapus</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">Yakin ingin menghapus <strong>{{ $account->nama_akun }}</strong>?</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form method="POST" action="{{ route('accounts.destroy', $account) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state py-4"><h6>Belum ada akun</h6></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $accounts->links() }}
    </div>
</div>
@endsection
