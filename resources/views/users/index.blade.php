@php $breadcrumbs = ['Users' => route('users.index')]; @endphp
@extends('layouts.app')
@section('title', 'Users')
@section('content')
<div class="page-header">
    <h4>Management Users</h4>
    <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah User</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4"><input type="text" name="search" class="form-control" placeholder="Cari nama/email..." value="{{ request('search') }}"></div>
            <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
            <div class="col-md-2"><a href="{{ route('users.index') }}" class="btn btn-secondary w-100">Reset</a></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Status</th><th class="text-end">Aksi</th></tr></thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge" style="background:rgba(59,130,246,0.1);color:var(--accent);">{{ $u->role?->name }}</span></td>
                        <td>
                            @if($u->status == 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('users.edit', $u) }}" class="btn btn-sm btn-warning">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $u->id }}">Hapus</button>
                            <div class="modal fade" id="deleteModal{{ $u->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header"><h5 class="modal-title">Konfirmasi Hapus</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">Yakin ingin menghapus <strong>{{ $u->name }}</strong>?</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form method="POST" action="{{ route('users.destroy', $u) }}">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Hapus</button></form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state py-4"><h6>Belum ada user</h6></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $users->links() }}
    </div>
</div>
@endsection
