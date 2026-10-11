<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AuditLog;
use App\Models\JournalDetail;
use App\Models\JournalHeader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $companyId = auth()->user()->company_id;

        $cacheKey = "dashboard_data_{$companyId}";

        $data = Cache::remember($cacheKey, 300, function () use ($companyId) {
            $pendapatanAkun = Account::where('company_id', $companyId)
                ->where('kategori', 'Pendapatan')
                ->pluck('id');

            $bebanAkun = Account::where('company_id', $companyId)
                ->where('kategori', 'Beban')
                ->pluck('id');

            $totalPendapatan = JournalDetail::whereIn('akun_id', $pendapatanAkun)
                ->whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId))
                ->sum('kredit');

            $totalPengeluaran = JournalDetail::whereIn('akun_id', $bebanAkun)
                ->whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId))
                ->sum('debit');

            $labaBersih = $totalPendapatan - $totalPengeluaran;

            $jumlahTransaksi = JournalHeader::where('company_id', $companyId)->count();

            $recentActivities = AuditLog::whereHas('user', fn($q) => $q->where('company_id', $companyId))
                ->with('user')
                ->latest()
                ->take(10)
                ->get();

            $topExpenses = JournalDetail::select('akun_id', DB::raw('SUM(debit) as total'))
                ->whereIn('akun_id', $bebanAkun)
                ->whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId))
                ->groupBy('akun_id')
                ->with('akun')
                ->orderByDesc('total')
                ->take(5)
                ->get();

            $months = collect(range(1, 12))->map(fn($m) => str_pad($m, 2, '0', STR_PAD_LEFT));

            $chartPendapatan = [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                'values' => $months->map(fn($m) => (float) JournalDetail::whereIn('akun_id', $pendapatanAkun)
                    ->whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId)
                        ->whereRaw("DATE_FORMAT(tanggal, '%m') = ?", [$m]))
                    ->sum('kredit')),
            ];

            $chartBeban = [
                'labels' => $chartPendapatan['labels'],
                'values' => $months->map(fn($m) => (float) JournalDetail::whereIn('akun_id', $bebanAkun)
                    ->whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId)
                        ->whereRaw("DATE_FORMAT(tanggal, '%m') = ?", [$m]))
                    ->sum('debit')),
            ];

            $chartCashFlow = [
                'labels' => ['Arus Masuk', 'Arus Keluar'],
                'masuk' => (float) $totalPendapatan,
                'keluar' => (float) $totalPengeluaran,
            ];

            return compact(
                'totalPendapatan', 'totalPengeluaran', 'labaBersih',
                'jumlahTransaksi', 'recentActivities', 'topExpenses',
                'chartPendapatan', 'chartBeban', 'chartCashFlow'
            );
        });

        return view('dashboard.index', $data);
    }
}
