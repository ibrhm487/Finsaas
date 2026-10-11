@php
    $breadcrumbs = ['Dashboard' => route('dashboard')];
@endphp
@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-header">
    <h4>Dashboard</h4>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <div class="stat-label">Total Pendapatan</div>
                    <div class="stat-value">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(16,185,129,0.1);color:var(--success);">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                </div>
            </div>
            <div class="stat-trend" style="color:var(--success);">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/></svg>
                Periode ini
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <div class="stat-label">Total Pengeluaran</div>
                    <div class="stat-value">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(239,68,68,0.1);color:var(--danger);">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                </div>
            </div>
            <div class="stat-trend" style="color:var(--danger);">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/></svg>
                Periode ini
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <div class="stat-label">Laba Bersih</div>
                    <div class="stat-value">Rp {{ number_format($labaBersih ?? 0, 0, ',', '.') }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(59,130,246,0.1);color:var(--accent);">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="stat-trend" style="color:var(--success);">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/></svg>
                Periode ini
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <div class="stat-label">Jumlah Transaksi</div>
                    <div class="stat-value">{{ $jumlahTransaksi ?? 0 }}</div>
                </div>
                <div class="stat-icon" style="background:rgba(245,158,11,0.1);color:var(--warning);">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <div class="stat-trend" style="color:var(--accent);">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/></svg>
                Total transaksi
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Perbandingan Pendapatan vs Beban</div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="incomeExpenseChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Cash Flow</div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="cashFlowChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Aktivitas Terbaru</div>
            <div class="card-body p-0">
                @if(isset($recentActivities) && count($recentActivities) > 0)
                <div class="list-group list-group-flush">
                    @foreach($recentActivities as $log)
                    <div class="list-group-item px-3 py-2 d-flex align-items-center gap-3 border-0" style="font-size:0.8rem;">
                        <div style="width:8px;height:8px;border-radius:50%;background:var(--accent);flex-shrink:0;"></div>
                        <div>
                            <strong>{{ $log->user?->name ?? 'System' }}</strong> {{ $log->aksi }} <span class="text-muted">{{ $log->module }}</span>
                            <br><small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h6>Belum ada aktivitas</h6>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Top 5 Akun Pengeluaran</div>
            <div class="card-body p-0">
                @if(isset($topExpenses) && count($topExpenses) > 0)
                <div class="list-group list-group-flush">
                    @foreach($topExpenses as $expense)
                    <div class="list-group-item px-3 py-2 d-flex justify-content-between align-items-center border-0">
                        <span style="font-size:0.8rem;">{{ $expense->nama_akun ?? $expense->akun?->nama_akun }}</span>
                        <span class="fw-bold" style="font-size:0.85rem;color:var(--danger);">Rp {{ number_format($expense->total ?? $expense->debit, 0, ',', '.') }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h6>Belum ada data pengeluaran</h6>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const colors = {
    primary: '#3B82F6',
    success: '#10B981',
    danger: '#EF4444',
    warning: '#F59E0B'
};

const pendapatanData = @json($chartPendapatan ?? []);
const bebanData = @json($chartBeban ?? []);

if (pendapatanData.labels) {
    new Chart(document.getElementById('incomeExpenseChart'), {
        type: 'bar',
        data: {
            labels: pendapatanData.labels,
            datasets: [
                { label: 'Pendapatan', data: pendapatanData.values, backgroundColor: 'rgba(16,185,129,0.6)', borderColor: colors.success, borderWidth: 2 },
                { label: 'Beban', data: bebanData.values, backgroundColor: 'rgba(239,68,68,0.6)', borderColor: colors.danger, borderWidth: 2 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top', labels: { boxWidth: 12, padding: 12 } } },
            scales: { y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } } }
        }
    });
}

const cashFlowData = @json($chartCashFlow ?? []);
if (cashFlowData.labels) {
    new Chart(document.getElementById('cashFlowChart'), {
        type: 'doughnut',
        data: {
            labels: ['Arus Masuk', 'Arus Keluar'],
            datasets: [{ data: [cashFlowData.masuk ?? 0, cashFlowData.keluar ?? 0], backgroundColor: [colors.success, colors.danger], borderWidth: 0 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12 } } }
        }
    });
}
</script>
@endpush
