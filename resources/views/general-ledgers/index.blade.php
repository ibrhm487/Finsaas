@php $breadcrumbs = ['Buku Besar' => route('general-ledgers.index')]; @endphp
@extends('layouts.app')
@section('title', 'Buku Besar')
@section('content')
<div class="page-header"><h4>Buku Besar</h4></div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <select name="akun_id" class="form-select">
                    <option value="">Semua Akun</option>
                    @foreach($accounts as $a)
                    <option value="{{ $a->id }}" {{ request('akun_id') == $a->id ? 'selected' : '' }}>{{ $a->kode_akun }} - {{ $a->nama_akun }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3"><input type="date" name="from" class="form-control" value="{{ request('from') }}" placeholder="Dari"></div>
            <div class="col-md-3"><input type="date" name="to" class="form-control" value="{{ request('to') }}" placeholder="Sampai"></div>
            <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
            <div class="col-md-2"><a href="{{ route('general-ledgers.index') }}" class="btn btn-secondary w-100">Reset</a></div>
        </form>

        @if($selectedAkun)
        <div class="mb-3">
            <strong>Akun:</strong> {{ $selectedAkun->kode_akun }} - {{ $selectedAkun->nama_akun }}<br>
            <strong>Saldo Awal:</strong> Rp {{ number_format($saldoAwal, 2) }}
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr><th>Tanggal</th><th>No. Jurnal</th><th>Keterangan</th><th class="text-end">Debit</th><th class="text-end">Kredit</th><th class="text-end">Saldo</th></tr>
                </thead>
                <tbody>
                    @forelse($ledgers as $l)
                    <tr>
                        <td>{{ $l->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $l->nomor_jurnal }}</td>
                        <td>{{ $l->keterangan ?? '-' }}</td>
                        <td class="text-end">{{ $l->debit > 0 ? number_format($l->debit, 2) : '-' }}</td>
                        <td class="text-end">{{ $l->kredit > 0 ? number_format($l->kredit, 2) : '-' }}</td>
                        <td class="text-end fw-bold">{{ number_format($l->saldo, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6"><div class="empty-state py-4"><h6>Belum ada data</h6></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $ledgers->links() }}
    </div>
</div>
@endsection
