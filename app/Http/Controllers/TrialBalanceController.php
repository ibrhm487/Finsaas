<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrialBalanceController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $query = JournalDetail::select(
                'akun_id',
                DB::raw('SUM(debit) as total_debit'),
                DB::raw('SUM(kredit) as total_kredit')
            )
            ->whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId))
            ->groupBy('akun_id')
            ->with('akun');

        if ($request->from) {
            $query->whereHas('journalHeader', fn($q) => $q->whereDate('tanggal', '>=', $request->from));
        }
        if ($request->to) {
            $query->whereHas('journalHeader', fn($q) => $q->whereDate('tanggal', '<=', $request->to));
        }

        $balances = $query->get()->map(function ($item) {
            $akun = $item->akun;
            if ($akun) {
                if ($akun->saldo_normal === 'Debit') {
                    $item->debit = max(0, $item->total_debit - $item->total_kredit);
                    $item->kredit = max(0, $item->total_kredit - $item->total_debit);
                } else {
                    $item->debit = max(0, $item->total_debit - $item->total_kredit);
                    $item->kredit = max(0, $item->total_kredit - $item->total_debit);
                }
            }
            return $item;
        });

        $totalDebit = $balances->sum('debit');
        $totalKredit = $balances->sum('kredit');

        return view('trial-balances.index', compact('balances', 'totalDebit', 'totalKredit'));
    }
}
