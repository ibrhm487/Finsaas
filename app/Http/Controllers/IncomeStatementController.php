<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IncomeStatementController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to = $request->to ?? now()->format('Y-m-d');

        $pendapatan = Account::where('company_id', $companyId)
            ->where('kategori', 'Pendapatan')
            ->with(['journalDetails' => function ($q) use ($from, $to) {
                $q->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)
                    ->whereDate('tanggal', '<=', $to));
            }])
            ->get()
            ->map(function ($akun) {
                $akun->total = $akun->journalDetails->sum('kredit') - $akun->journalDetails->sum('debit');
                return $akun;
            });

        $beban = Account::where('company_id', $companyId)
            ->where('kategori', 'Beban')
            ->with(['journalDetails' => function ($q) use ($from, $to) {
                $q->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)
                    ->whereDate('tanggal', '<=', $to));
            }])
            ->get()
            ->map(function ($akun) {
                $akun->total = $akun->journalDetails->sum('debit') - $akun->journalDetails->sum('kredit');
                return $akun;
            });

        $totalPendapatan = $pendapatan->sum('total');
        $totalBeban = $beban->sum('total');
        $labaBersih = $totalPendapatan - $totalBeban;

        return view('income-statements.index', compact(
            'pendapatan', 'beban', 'totalPendapatan', 'totalBeban', 'labaBersih', 'from', 'to'
        ));
    }
}
