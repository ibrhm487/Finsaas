@php $breadcrumbs = ['Neraca' => route('balance-sheets.index')]; @endphp
@extends('layouts.app')
@section('title', 'Neraca')
@section('content')
<div class="page-header">
    <h4>Neraca</h4>
    <div>
        <a href="{{ route('exports.balance-sheet.pdf', request()->query()) }}" class="btn btn-danger">PDF</a>
        <a href="{{ route('exports.balance-sheet.excel', request()->query()) }}" class="btn btn-success">Excel</a>
        <button onclick="window.print()" class="btn btn-secondary">Print</button>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3"><input type="date" name="periode" class="form-control" value="{{ $periode }}"></div>
            <div class="col-md-1"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
            <div class="col-md-2"><a href="{{ route('balance-sheets.index') }}" class="btn btn-secondary w-100">Reset</a></div>
        </form>

        <div class="row">
            <div class="col-md-4">
                <h6 class="fw-bold mb-3" style="color:var(--accent);">ASET</h6>
                <table class="table">
                    @foreach($aset as $a)
                    <tr><td>{{ $a->nama_akun }}</td><td class="text-end">{{ number_format($a->total, 2) }}</td></tr>
                    @endforeach
                    <tr class="fw-bold"><td>TOTAL ASET</td><td class="text-end">Rp {{ number_format($totalAset, 2) }}</td></tr>
                </table>
            </div>
            <div class="col-md-4">
                <h6 class="fw-bold mb-3" style="color:var(--warning);">KEWAJIBAN</h6>
                <table class="table">
                    @foreach($kewajiban as $k)
                    <tr><td>{{ $k->nama_akun }}</td><td class="text-end">{{ number_format($k->total, 2) }}</td></tr>
                    @endforeach
                    <tr class="fw-bold"><td>TOTAL KEWAJIBAN</td><td class="text-end">Rp {{ number_format($totalKewajiban, 2) }}</td></tr>
                </table>

                <h6 class="fw-bold mt-4 mb-3" style="color:var(--primary);">MODAL</h6>
                <table class="table">
                    @foreach($modal as $m)
                    <tr><td>{{ $m->nama_akun }}</td><td class="text-end">{{ number_format($m->total, 2) }}</td></tr>
                    @endforeach
                    <tr><td>Laba Ditahan</td><td class="text-end">Rp {{ number_format($labaDitahan, 2) }}</td></tr>
                    <tr class="fw-bold"><td>TOTAL MODAL</td><td class="text-end">Rp {{ number_format($totalModal, 2) }}</td></tr>
                </table>
            </div>
            <div class="col-md-4">
                <div class="card" style="background:rgba(16,185,129,0.05);border:2px solid var(--success);">
                    <div class="card-body text-center">
                        <h6 class="fw-bold" style="color:var(--success);">PERSAMAAN DASAR AKUNTANSI</h6>
                        <hr>
                        <p class="mb-1">Aset: <strong>Rp {{ number_format($totalAset, 2) }}</strong></p>
                        <p class="mb-1">=</p>
                        <p class="mb-1">Kewajiban: <strong>Rp {{ number_format($totalKewajiban, 2) }}</strong></p>
                        <p class="mb-1">+</p>
                        <p class="mb-1">Modal: <strong>Rp {{ number_format($totalModal, 2) }}</strong></p>
                        <hr>
                        <p class="fw-bold">Kewajiban + Modal = <span style="color:var(--success);">Rp {{ number_format($totalKewajiban + $totalModal, 2) }}</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
