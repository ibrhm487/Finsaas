@php $breadcrumbs = ['Jurnal Umum' => route('journals.index')]; @endphp
@extends('layouts.app')
@section('title', 'Jurnal Umum')
@section('content')
<div class="page-header">
    <h4>Jurnal Umum</h4>
    <a href="{{ route('journals.create') }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="me-1"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Jurnal
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Cari nomor/keterangan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="from" class="form-control" value="{{ request('from') }}" placeholder="Dari">
            </div>
            <div class="col-md-3">
                <input type="date" name="to" class="form-control" value="{{ request('to') }}" placeholder="Sampai">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('journals.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr><th>No. Jurnal</th><th>Tanggal</th><th>Keterangan</th><th>User</th><th class="text-end">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($journals as $j)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $j->nomor_jurnal }}</span></td>
                        <td>{{ $j->tanggal->format('d/m/Y') }}</td>
                        <td>{{ Str::limit($j->keterangan, 40) }}</td>
                        <td>{{ $j->user?->name }}</td>
                        <td class="text-end">
                            <a href="{{ route('journals.show', $j) }}" class="btn btn-sm btn-info text-white">Detail</a>
                            <a href="{{ route('journals.edit', $j) }}" class="btn btn-sm btn-warning">Edit</a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $j->id }}">Hapus</button>
                            <div class="modal fade" id="deleteModal{{ $j->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header"><h5 class="modal-title">Konfirmasi Hapus</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">Yakin ingin menghapus jurnal <strong>{{ $j->nomor_jurnal }}</strong>?</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form method="POST" action="{{ route('journals.destroy', $j) }}">@csrf @method('DELETE')<button type="submit" class="btn btn-danger">Hapus</button></form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5"><div class="empty-state py-4"><h6>Belum ada jurnal</h6></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $journals->links() }}
    </div>
</div>
@endsection
