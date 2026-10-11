@php
    $breadcrumbs = ['Arus Kas' => route('cash-flows.index'), 'Detail Laporan' => '#'];
@endphp
@extends('layouts.app')
@section('title', 'Detail Arus Kas')
@section('content')
<div class="page-header">
    <h4>Laporan Arus Kas - {{ $cashFlow->method === 'direct' ? 'Metode Langsung' : 'Metode Tidak Langsung' }}</h4>
    <div>
        <a href="{{ route('cash-flows.index') }}" class="btn btn-secondary">Kembali</a>
        <form method="POST" action="{{ route('cash-flows.regenerate', $cashFlow->id) }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning">Regenerate</button>
        </form>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <strong>Periode:</strong> {{ $cashFlow->periode_start->format('d/m/Y') }} - {{ $cashFlow->periode_end->format('d/m/Y') }}
    </div>
    <div class="col-md-3">
        <strong>Metode:</strong> {{ $cashFlow->method === 'direct' ? 'Langsung (Direct)' : 'Tidak Langsung (Indirect)' }}
    </div>
    <div class="col-md-3">
        <strong>Dibuat:</strong> {{ $cashFlow->generated_at->format('d/m/Y H:i') }}
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                @if($cashFlow->method === 'indirect')
                    @php $op = $cashFlow->operating_activities; @endphp
                    <tr class="fw-bold"><td colspan="2">ARUS KAS DARI AKTIVITAS OPERASI (METODE TIDAK LANGSUNG)</td></tr>
                    <tr><td style="padding-left:2rem;">Laba Bersih</td><td class="text-end">Rp {{ number_format($op['net_income'] ?? 0, 2) }}</td></tr>
                    @if(!empty($op['non_cash_items']))
                        <tr><td colspan="2"><em>Penyesuaian untuk rekonsiliasi laba bersih:</em></td></tr>
                        @foreach($op['non_cash_items'] as $item)
                            <tr><td style="padding-left:3rem;">{{ $item['account_name'] }}</td><td class="text-end">Rp {{ number_format($item['amount'], 2) }}</td></tr>
                        @endforeach
                    @endif
                    @if(!empty($op['working_capital_items']))
                        <tr><td colspan="2"><em>Perubahan Aset & Liabilitas Operasi:</em></td></tr>
                        @foreach($op['working_capital_items'] as $item)
                            <tr>
                                <td style="padding-left:3rem;">{{ $item['account_name'] }}</td>
                                <td class="text-end">Rp {{ number_format($item['change'], 2) }}</td>
                            </tr>
                        @endforeach
                    @endif
                    <tr class="fw-bold"><td>Arus Kas Bersih - Operasi</td><td class="text-end" style="color:var(--success);">Rp {{ number_format($cashFlow->net_operating, 2) }}</td></tr>
                @else
                    @php $op = $cashFlow->operating_activities; @endphp
                    <tr class="fw-bold"><td colspan="2">ARUS KAS DARI AKTIVITAS OPERASI (METODE LANGSUNG)</td></tr>
                    @if(!empty($op['items']))
                        @foreach($op['items'] as $item)
                            <tr>
                                <td style="padding-left:2rem;">{{ $item['type'] === 'inflow' ? 'Penerimaan' : 'Pembayaran' }} - {{ $item['category'] }}</td>
                                <td class="text-end">Rp {{ number_format($item['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    @endif
                    @if(($op['total_inflow'] ?? 0) > 0)
                        <tr><td style="padding-left:2rem;font-weight:500;">Total Penerimaan Kas - Operasi</td><td class="text-end">Rp {{ number_format($op['total_inflow'] ?? 0, 2) }}</td></tr>
                    @endif
                    @if(($op['total_outflow'] ?? 0) > 0)
                        <tr><td style="padding-left:2rem;font-weight:500;">Total Pengeluaran Kas - Operasi</td><td class="text-end">Rp {{ number_format($op['total_outflow'] ?? 0, 2) }}</td></tr>
                    @endif
                    <tr class="fw-bold"><td>Arus Kas Bersih - Operasi</td><td class="text-end" style="color:var(--success);">Rp {{ number_format($cashFlow->net_operating, 2) }}</td></tr>
                @endif

                @php $inv = $cashFlow->investing_activities; @endphp
                <tr class="fw-bold mt-3"><td colspan="2">ARUS KAS DARI AKTIVITAS INVESTASI</td></tr>
                @if(!empty($inv['items']))
                    @foreach($inv['items'] as $item)
                        <tr>
                            <td style="padding-left:2rem;">{{ $item['type'] === 'inflow' ? 'Penerimaan' : 'Pembayaran' }} - {{ $item['category'] ?? $item['account_name'] ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format($item['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr><td style="padding-left:2rem;" colspan="2"><em>Tidak ada transaksi investasi</em></td></tr>
                @endif
                <tr class="fw-bold"><td>Arus Kas Bersih - Investasi</td><td class="text-end" style="color:var(--warning);">Rp {{ number_format($cashFlow->net_investing, 2) }}</td></tr>

                @php $fin = $cashFlow->financing_activities; @endphp
                <tr class="fw-bold mt-3"><td colspan="2">ARUS KAS DARI AKTIVITAS PENDANAAN</td></tr>
                @if(!empty($fin['items']))
                    @foreach($fin['items'] as $item)
                        <tr>
                            <td style="padding-left:2rem;">{{ $item['type'] === 'inflow' ? 'Penerimaan' : 'Pembayaran' }} - {{ $item['category'] ?? $item['account_name'] ?? '-' }}</td>
                            <td class="text-end">Rp {{ number_format($item['amount'], 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr><td style="padding-left:2rem;" colspan="2"><em>Tidak ada transaksi pendanaan</em></td></tr>
                @endif
                <tr class="fw-bold"><td>Arus Kas Bersih - Pendanaan</td><td class="text-end" style="color:var(--accent);">Rp {{ number_format($cashFlow->net_financing, 2) }}</td></tr>

                <tr class="fw-bold" style="font-size:1.1rem;border-top:3px solid var(--primary);">
                    <td colspan="2">RINGKASAN</td>
                </tr>
                <tr><td style="padding-left:2rem;">Saldo Awal Kas</td><td class="text-end">Rp {{ number_format($cashFlow->beginning_cash, 2) }}</td></tr>
                <tr><td style="padding-left:2rem;">Kenaikan/(Penurunan) Bersih Kas</td><td class="text-end">Rp {{ number_format($cashFlow->net_increase, 2) }}</td></tr>
                <tr class="fw-bold" style="font-size:1.1rem;"><td>Saldo Akhir Kas</td><td class="text-end" style="color:var(--success);">Rp {{ number_format($cashFlow->ending_cash, 2) }}</td></tr>
            </table>
        </div>
    </div>
</div>
@endsection
