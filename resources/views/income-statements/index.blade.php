@php $breadcrumbs = ['Laba Rugi' => route('income-statements.index')]; @endphp
@extends('layouts.app')
@section('title', 'Laporan Laba Rugi')
@section('content')
<div class="page-header">
    <h4>Laporan Laba Rugi</h4>
    <div>
        <a href="{{ route('exports.income-statement.pdf', request()->query()) }}" class="btn btn-danger">PDF</a>
        <a href="{{ route('exports.income-statement.excel', request()->query()) }}" class="btn btn-success">Excel</a>
        <button onclick="window.print()" class="btn btn-secondary">Print</button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3"><input type="date" name="from" class="form-control" value="{{ $from }}"></div>
            <div class="col-md-3"><input type="date" name="to" class="form-control" value="{{ $to }}"></div>
            <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
            <div class="col-md-2"><a href="{{ route('income-statements.index') }}" class="btn btn-secondary w-100">Reset</a></div>
        </form>

        <div class="row">
            <div class="col-md-6">
                <h6 class="fw-bold mb-3" style="color:var(--success);">PENDAPATAN</h6>
                <table class="table">
                    @foreach($pendapatan as $p)
                    <tr><td>{{ $p->nama_akun }}</td><td class="text-end">{{ number_format($p->total, 2) }}</td></tr>
                    @endforeach
                    <tr class="fw-bold"><td>TOTAL PENDAPATAN</td><td class="text-end" style="color:var(--success);">Rp {{ number_format($totalPendapatan, 2) }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold mb-3" style="color:var(--danger);">BEBAN</h6>
                <table class="table">
                    @foreach($beban as $b)
                    <tr><td>{{ $b->nama_akun }}</td><td class="text-end">{{ number_format($b->total, 2) }}</td></tr>
                    @endforeach
                    <tr class="fw-bold"><td>TOTAL BEBAN</td><td class="text-end" style="color:var(--danger);">Rp {{ number_format($totalBeban, 2) }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card mt-3" style="background:rgba(16,185,129,0.05);border:2px solid var(--success);">
            <div class="card-body text-center">
                <h5 class="fw-bold" style="color:var(--success);">
                    LABA BERSIH: Rp {{ number_format($labaBersih, 2) }}
                </h5>
            </div>
        </div>
    </div>
</div>
@endsection
