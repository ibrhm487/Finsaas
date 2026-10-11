<?php

namespace App\Http\Controllers;

use App\Exports\JournalExport;
use App\Exports\TrialBalanceExport;
use App\Exports\IncomeStatementExport;
use App\Exports\BalanceSheetExport;
use App\Exports\CashFlowExport;
use App\Exports\GeneralLedgerExport;
use App\Models\Account;
use App\Models\JournalDetail;
use App\Models\JournalHeader;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function journalPdf(JournalHeader $journal)
    {
        $journal->load('details.akun', 'user');
        $pdf = Pdf::loadView('exports.journal-pdf', compact('journal'));
        return $pdf->download('jurnal-' . $journal->nomor_jurnal . '.pdf');
    }

    public function journalExcel(JournalHeader $journal)
    {
        return (new JournalExport($journal))->download('jurnal-' . $journal->nomor_jurnal . '.xlsx');
    }

    public function generalLedgerPdf(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $accounts = Account::where('company_id', $companyId)->orderBy('kode_akun')->get();
        $ledgers = [];

        if ($request->akun_id) {
            $query = \App\Models\GeneralLedger::where('company_id', $companyId)
                ->where('akun_id', $request->akun_id)
                ->with('akun');

            if ($request->from) $query->whereDate('tanggal', '>=', $request->from);
            if ($request->to) $query->whereDate('tanggal', '<=', $request->to);

            $ledgers = $query->orderBy('tanggal')->get();
        }

        $selectedAkun = $request->akun_id ? Account::find($request->akun_id) : null;

        $saldoAwal = 0;
        if ($selectedAkun && $request->from) {
            $saldoAwal = \App\Models\GeneralLedger::where('company_id', $companyId)
                ->where('akun_id', $request->akun_id)
                ->whereDate('tanggal', '<', $request->from)
                ->sum(DB::raw('debit - kredit'));
        }

        $pdf = Pdf::loadView('exports.general-ledger-pdf', compact('ledgers', 'selectedAkun', 'saldoAwal', 'request'));
        return $pdf->download('buku-besar.pdf');
    }

    public function trialBalancePdf(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $query = JournalDetail::select('akun_id', DB::raw('SUM(debit) as total_debit'), DB::raw('SUM(kredit) as total_kredit'))
            ->whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId))
            ->groupBy('akun_id')
            ->with('akun');

        if ($request->from) $query->whereHas('journalHeader', fn($q) => $q->whereDate('tanggal', '>=', $request->from));
        if ($request->to) $query->whereHas('journalHeader', fn($q) => $q->whereDate('tanggal', '<=', $request->to));

        $balances = $query->get()->map(function ($item) {
            $akun = $item->akun;
            if ($akun) {
                $item->debit = max(0, $item->total_debit - $item->total_kredit);
                $item->kredit = max(0, $item->total_kredit - $item->total_debit);
            }
            return $item;
        });

        $totalDebit = $balances->sum('debit');
        $totalKredit = $balances->sum('kredit');

        $pdf = Pdf::loadView('exports.trial-balance-pdf', compact('balances', 'totalDebit', 'totalKredit'));
        return $pdf->download('neraca-saldo.pdf');
    }

    public function trialBalanceExcel(Request $request)
    {
        return (new TrialBalanceExport($request))->download('neraca-saldo.xlsx');
    }

    public function incomeStatementPdf(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to = $request->to ?? now()->format('Y-m-d');

        $pendapatan = Account::where('company_id', $companyId)->where('kategori', 'Pendapatan')->get()
            ->map(function ($akun) use ($from, $to) {
                $akun->total = JournalDetail::where('akun_id', $akun->id)
                    ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
                    ->sum('kredit') - JournalDetail::where('akun_id', $akun->id)
                    ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
                    ->sum('debit');
                return $akun;
            });

        $beban = Account::where('company_id', $companyId)->where('kategori', 'Beban')->get()
            ->map(function ($akun) use ($from, $to) {
                $akun->total = JournalDetail::where('akun_id', $akun->id)
                    ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
                    ->sum('debit') - JournalDetail::where('akun_id', $akun->id)
                    ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
                    ->sum('kredit');
                return $akun;
            });

        $totalPendapatan = $pendapatan->sum('total');
        $totalBeban = $beban->sum('total');
        $labaBersih = $totalPendapatan - $totalBeban;

        $pdf = Pdf::loadView('exports.income-statement-pdf', compact('pendapatan', 'beban', 'totalPendapatan', 'totalBeban', 'labaBersih', 'from', 'to'));
        return $pdf->download('laba-rugi.pdf');
    }

    public function incomeStatementExcel(Request $request)
    {
        return (new IncomeStatementExport($request))->download('laba-rugi.xlsx');
    }

    public function balanceSheetPdf(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $periode = $request->periode ?? now()->format('Y-m-d');

        $aset = Account::where('company_id', $companyId)->where('kategori', 'Aset')->get()
            ->map(function ($akun) use ($periode) {
                $akun->total = JournalDetail::where('akun_id', $akun->id)
                    ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '<=', $periode))
                    ->sum('debit') - JournalDetail::where('akun_id', $akun->id)
                    ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '<=', $periode))
                    ->sum('kredit');
                return $akun;
            });

        $kewajiban = Account::where('company_id', $companyId)->where('kategori', 'Kewajiban')->get()
            ->map(function ($akun) use ($periode) {
                $akun->total = JournalDetail::where('akun_id', $akun->id)
                    ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '<=', $periode))
                    ->sum('kredit') - JournalDetail::where('akun_id', $akun->id)
                    ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '<=', $periode))
                    ->sum('debit');
                return $akun;
            });

        $totalAset = $aset->sum('total');
        $totalKewajiban = $kewajiban->sum('total');
        $labaDitahan = $this->getLabaDitahan($companyId, $periode);
        $totalModal = $labaDitahan;

        $pdf = Pdf::loadView('exports.balance-sheet-pdf', compact('aset', 'kewajiban', 'totalAset', 'totalKewajiban', 'totalModal', 'labaDitahan', 'periode'));
        return $pdf->download('neraca.pdf');
    }

    public function balanceSheetExcel(Request $request)
    {
        return (new BalanceSheetExport($request))->download('neraca.xlsx');
    }

    public function cashFlowPdf(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to = $request->to ?? now()->format('Y-m-d');

        $pendapatanAkun = Account::where('company_id', $companyId)->where('kategori', 'Pendapatan')->pluck('id');
        $bebanAkun = Account::where('company_id', $companyId)->where('kategori', 'Beban')->pluck('id');

        $arusMasukOperasi = JournalDetail::whereIn('akun_id', $pendapatanAkun)
            ->whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId)
                ->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
            ->sum('kredit');

        $arusKeluarOperasi = JournalDetail::whereIn('akun_id', $bebanAkun)
            ->whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId)
                ->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
            ->sum('debit');

        $operasi = ['masuk' => $arusMasukOperasi, 'keluar' => $arusKeluarOperasi, 'bersih' => $arusMasukOperasi - $arusKeluarOperasi];
        $investasi = ['masuk' => 0, 'keluar' => 0, 'bersih' => 0];
        $pendanaan = ['masuk' => 0, 'keluar' => 0, 'bersih' => 0];
        $saldo_akhir = $operasi['bersih'];

        $pdf = Pdf::loadView('exports.cash-flow-pdf', compact('operasi', 'investasi', 'pendanaan', 'saldo_akhir', 'from', 'to'));
        return $pdf->download('arus-kas.pdf');
    }

    public function cashFlowExcel(Request $request)
    {
        return (new CashFlowExport($request))->download('arus-kas.xlsx');
    }

    private function getLabaDitahan($companyId, $periode)
    {
        $pendapatan = JournalDetail::whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId)->whereDate('tanggal', '<=', $periode))
            ->whereHas('akun', fn($q) => $q->where('kategori', 'Pendapatan'))->sum('kredit');

        $beban = JournalDetail::whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId)->whereDate('tanggal', '<=', $periode))
            ->whereHas('akun', fn($q) => $q->where('kategori', 'Beban'))->sum('debit');

        return $pendapatan - $beban;
    }
}
