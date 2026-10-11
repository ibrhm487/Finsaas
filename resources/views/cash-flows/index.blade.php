@php $breadcrumbs = ['Arus Kas (PSAK)' => route('cash-flows.index')]; @endphp
@extends('layouts.app')
@section('title', 'Arus Kas')
@section('content')
<div class="page-header">
    <h4>Laporan Arus Kas (PSAK / IAS 7)</h4>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('cash-flows.generate') }}" class="row g-3 mb-3">
            @csrf
            <div class="col-md-3">
                <label class="form-label">Periode Awal</label>
                <input type="date" name="from" class="form-control" value="{{ old('from', now()->startOfYear()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Periode Akhir</label>
                <input type="date" name="to" class="form-control" value="{{ old('to', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Metode</label>
                <select name="method" class="form-select" required>
                    <option value="direct" {{ old('method') === 'direct' ? 'selected' : '' }}>Langsung (Direct)</option>
                    <option value="indirect" {{ old('method') === 'indirect' ? 'selected' : '' }}>Tidak Langsung (Indirect)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Generate Laporan</button>
            </div>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mt-3">{{ session('success') }}</div>
@endif

@if($reports->isNotEmpty())
<div class="card mt-3">
    <div class="card-body">
        <h5>Riwayat Laporan</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Metode</th>
                        <th>Arus Operasi</th>
                        <th>Arus Investasi</th>
                        <th>Arus Pendanaan</th>
                        <th>Kenaikan Bersih</th>
                        <th>Saldo Akhir</th>
                        <th>Dibuat</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $r)
                    <tr>
                        <td>{{ $r->periode_start->format('d/m/Y') }} - {{ $r->periode_end->format('d/m/Y') }}</td>
                        <td>{{ $r->method === 'direct' ? 'Direct' : 'Indirect' }}</td>
                        <td class="text-end">Rp {{ number_format($r->net_operating, 2) }}</td>
                        <td class="text-end">Rp {{ number_format($r->net_investing, 2) }}</td>
                        <td class="text-end">Rp {{ number_format($r->net_financing, 2) }}</td>
                        <td class="text-end">Rp {{ number_format($r->net_increase, 2) }}</td>
                        <td class="text-end">Rp {{ number_format($r->ending_cash, 2) }}</td>
                        <td>{{ $r->generated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('cash-flows.show', $r->id) }}" class="btn btn-sm btn-primary">Lihat</a>
                            <form method="POST" action="{{ route('cash-flows.regenerate', $r->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">Regenerate</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif
@endsection
