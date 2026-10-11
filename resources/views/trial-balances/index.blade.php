@php $breadcrumbs = ['Neraca Saldo' => route('trial-balances.index')]; @endphp
@extends('layouts.app')
@section('title', 'Neraca Saldo')
@section('content')
<div class="page-header">
    <h4>Neraca Saldo</h4>
    <div>
        <a href="{{ route('exports.trial-balance.pdf', request()->query()) }}" class="btn btn-danger">PDF</a>
        <a href="{{ route('exports.trial-balance.excel', request()->query()) }}" class="btn btn-success">Excel</a>
        <button onclick="window.print()" class="btn btn-secondary">Print</button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3"><input type="date" name="from" class="form-control" value="{{ request('from') }}"></div>
            <div class="col-md-3"><input type="date" name="to" class="form-control" value="{{ request('to') }}"></div>
            <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
            <div class="col-md-2"><a href="{{ route('trial-balances.index') }}" class="btn btn-secondary w-100">Reset</a></div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover" id="printTable">
                <thead>
                    <tr><th>Kode</th><th>Nama Akun</th><th class="text-end">Debit</th><th class="text-end">Kredit</th></tr>
                </thead>
                <tbody>
                    @forelse($balances as $b)
                    <tr>
                        <td>{{ $b->akun?->kode_akun }}</td>
                        <td>{{ $b->akun?->nama_akun }}</td>
                        <td class="text-end">{{ $b->debit > 0 ? number_format($b->debit, 2) : '-' }}</td>
                        <td class="text-end">{{ $b->kredit > 0 ? number_format($b->kredit, 2) : '-' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4"><div class="empty-state py-4"><h6>Belum ada data</h6></div></td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="fw-bold"><td colspan="2" class="text-end">Total</td><td class="text-end">{{ number_format($totalDebit, 2) }}</td><td class="text-end">{{ number_format($totalKredit, 2) }}</td></tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
