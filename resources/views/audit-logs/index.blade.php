@php $breadcrumbs = ['Audit Trail' => route('audit-logs.index')]; @endphp
@extends('layouts.app')
@section('title', 'Audit Trail')
@section('content')
<div class="page-header"><h4>Audit Trail</h4></div>
<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3"><input type="text" name="search" class="form-control" placeholder="Cari user..." value="{{ request('search') }}"></div>
            <div class="col-md-2">
                <select name="module" class="form-select">
                    <option value="">Semua Module</option>
                    <option value="Auth" {{ request('module') == 'Auth' ? 'selected' : '' }}>Auth</option>
                    <option value="Chart of Accounts" {{ request('module') == 'Chart of Accounts' ? 'selected' : '' }}>Chart of Accounts</option>
                    <option value="Jurnal Umum" {{ request('module') == 'Jurnal Umum' ? 'selected' : '' }}>Jurnal Umum</option>
                    <option value="User Management" {{ request('module') == 'User Management' ? 'selected' : '' }}>User Management</option>
                    <option value="Perusahaan" {{ request('module') == 'Perusahaan' ? 'selected' : '' }}>Perusahaan</option>
                    <option value="Profile" {{ request('module') == 'Profile' ? 'selected' : '' }}>Profile</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="aksi" class="form-select">
                    <option value="">Semua Aksi</option>
                    <option value="Create" {{ request('aksi') == 'Create' ? 'selected' : '' }}>Create</option>
                    <option value="Update" {{ request('aksi') == 'Update' ? 'selected' : '' }}>Update</option>
                    <option value="Delete" {{ request('aksi') == 'Delete' ? 'selected' : '' }}>Delete</option>
                    <option value="Login" {{ request('aksi') == 'Login' ? 'selected' : '' }}>Login</option>
                    <option value="Logout" {{ request('aksi') == 'Logout' ? 'selected' : '' }}>Logout</option>
                </select>
            </div>
            <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
            <div class="col-md-2"><a href="{{ route('audit-logs.index') }}" class="btn btn-secondary w-100">Reset</a></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>User</th><th>Aksi</th><th>Module</th><th>IP Address</th><th>Waktu</th></tr></thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->user?->name ?? 'System' }}</td>
                        <td>
                            @if($log->aksi == 'Create') <span class="badge bg-success">Create</span>
                            @elseif($log->aksi == 'Update') <span class="badge bg-warning">Update</span>
                            @elseif($log->aksi == 'Delete') <span class="badge bg-danger">Delete</span>
                            @elseif($log->aksi == 'Login') <span class="badge bg-info">Login</span>
                            @elseif($log->aksi == 'Logout') <span class="badge bg-secondary">Logout</span>
                            @else <span class="badge bg-primary">{{ $log->aksi }}</span>
                            @endif
                        </td>
                        <td>{{ $log->module }}</td>
                        <td><code>{{ $log->ip_address }}</code></td>
                        <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state py-4"><h6>Belum ada data audit</h6></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $logs->links() }}
    </div>
</div>
@endsection
