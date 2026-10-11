<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $periode = $request->periode ?? now()->format('Y-m-d');

        $aset = Account::where('company_id', $companyId)
            ->where('kategori', 'Aset')
            ->with(['journalDetails' => function ($q) use ($periode) {
                $q->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '<=', $periode));
            }])
            ->get()
            ->map(function ($akun) {
                $akun->total = $akun->journalDetails->sum('debit') - $akun->journalDetails->sum('kredit');
                return $akun;
            });

        $kewajiban = Account::where('company_id', $companyId)
            ->where('kategori', 'Kewajiban')
            ->with(['journalDetails' => function ($q) use ($periode) {
                $q->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '<=', $periode));
            }])
            ->get()
            ->map(function ($akun) {
                $akun->total = $akun->journalDetails->sum('kredit') - $akun->journalDetails->sum('debit');
                return $akun;
            });

        $modal = Account::where('company_id', $companyId)
            ->where('kategori', 'Modal')
            ->with(['journalDetails' => function ($q) use ($periode) {
                $q->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '<=', $periode));
            }])
            ->get()
            ->map(function ($akun) {
                $akun->total = $akun->journalDetails->sum('kredit') - $akun->journalDetails->sum('debit');
                return $akun;
            });

        $totalAset = $aset->sum('total');
        $totalKewajiban = $kewajiban->sum('total');
        $totalModal = $modal->sum('total');

        $labaDitahan = $this->getLabaDitahan($companyId, $periode);
        $totalModal += $labaDitahan;

        return view('balance-sheets.index', compact(
            'aset', 'kewajiban', 'modal', 'totalAset', 'totalKewajiban', 'totalModal', 'periode', 'labaDitahan'
        ));
    }

    private function getLabaDitahan($companyId, $periode)
    {
        $pendapatan = JournalDetail::whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId)
                ->whereDate('tanggal', '<=', $periode))
            ->whereHas('akun', fn($q) => $q->where('kategori', 'Pendapatan'))
            ->sum('kredit');

        $beban = JournalDetail::whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId)
                ->whereDate('tanggal', '<=', $periode))
            ->whereHas('akun', fn($q) => $q->where('kategori', 'Beban'))
            ->sum('debit');

        return $pendapatan - $beban;
    }
}
