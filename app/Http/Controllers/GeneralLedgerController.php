<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\JournalDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralLedgerController extends Controller
{
    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $accounts = Account::where('company_id', $companyId)->orderBy('kode_akun')->get();

        $query = JournalDetail::select(
                'journal_details.*',
                'journal_headers.tanggal',
                'journal_headers.nomor_jurnal',
                'journal_headers.keterangan as keterangan_jurnal'
            )
            ->join('journal_headers', 'journal_details.journal_header_id', '=', 'journal_headers.id')
            ->where('journal_headers.company_id', $companyId)
            ->with('akun');

        if ($request->akun_id) {
            $query->where('journal_details.akun_id', $request->akun_id);
        }
        if ($request->from) {
            $query->whereDate('journal_headers.tanggal', '>=', $request->from);
        }
        if ($request->to) {
            $query->whereDate('journal_headers.tanggal', '<=', $request->to);
        }

        $ledgers = $query->orderBy('journal_headers.tanggal')
            ->orderBy('journal_headers.id')
            ->paginate(50)
            ->withQueryString();

        $selectedAkun = $request->akun_id ? Account::find($request->akun_id) : null;

        $saldoAwal = 0;
        if ($selectedAkun && $request->from) {
            $saldoAwal = JournalDetail::join('journal_headers', 'journal_details.journal_header_id', '=', 'journal_headers.id')
                ->where('journal_headers.company_id', $companyId)
                ->where('journal_details.akun_id', $request->akun_id)
                ->whereDate('journal_headers.tanggal', '<', $request->from)
                ->sum(DB::raw('journal_details.debit - journal_details.kredit'));
        }

        $runningSaldo = $saldoAwal;
        $ledgers->getCollection()->transform(function ($item) use (&$runningSaldo, $selectedAkun) {
            $item->tanggal = Carbon::parse($item->tanggal);
            $item->keterangan = $item->keterangan_jurnal;
            if ($selectedAkun) {
                if ($selectedAkun->saldo_normal === 'Debit') {
                    $runningSaldo += $item->debit - $item->kredit;
                } else {
                    $runningSaldo += $item->kredit - $item->debit;
                }
                $item->saldo = $runningSaldo;
            } else {
                $item->saldo = 0;
            }
            return $item;
        });

        return view('general-ledgers.index', compact('accounts', 'ledgers', 'selectedAkun', 'saldoAwal'));
    }
}
