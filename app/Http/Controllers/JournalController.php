<?php

namespace App\Http\Controllers;

use App\Models\JournalHeader;
use App\Models\JournalDetail;
use App\Models\Account;
use App\Models\GeneralLedger;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $query = JournalHeader::where('company_id', auth()->user()->company_id)
            ->with('user');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nomor_jurnal', 'like', "%{$request->search}%")
                  ->orWhere('keterangan', 'like', "%{$request->search}%");
            });
        }

        if ($request->from) $query->whereDate('tanggal', '>=', $request->from);
        if ($request->to) $query->whereDate('tanggal', '<=', $request->to);

        $journals = $query->orderByDesc('tanggal')->paginate(20)->withQueryString();

        return view('journals.index', compact('journals'));
    }

    public function create()
    {
        $accounts = Account::active()
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('kode_akun')
            ->get();

        $lastJournal = JournalHeader::where('company_id', auth()->user()->company_id)
            ->whereYear('created_at', now()->year)
            ->count();

        $nomorJurnal = 'JURNAL-' . now()->format('Ymd') . '-' . str_pad($lastJournal + 1, 4, '0', STR_PAD_LEFT);

        return view('journals.create', compact('accounts', 'nomorJurnal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_jurnal' => 'required|string|max:50',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'akun_id' => 'required|array|min:2',
            'akun_id.*' => 'required|exists:accounts,id',
            'debit' => 'required|array',
            'debit.*' => 'numeric|min:0',
            'kredit' => 'required|array',
            'kredit.*' => 'numeric|min:0',
        ]);

        $totalDebit = array_sum($request->debit);
        $totalKredit = array_sum($request->kredit);

        if (abs($totalDebit - $totalKredit) > 0.01) {
            return back()->withErrors(['msg' => 'Total debit harus sama dengan total kredit.'])
                ->withInput();
        }

        DB::transaction(function () use ($request, $totalDebit) {
            $header = JournalHeader::create([
                'company_id' => auth()->user()->company_id,
                'nomor_jurnal' => $request->nomor_jurnal,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id(),
            ]);

            foreach ($request->akun_id as $i => $akunId) {
                $debit = $request->debit[$i] ?? 0;
                $kredit = $request->kredit[$i] ?? 0;

                if ($debit == 0 && $kredit == 0) continue;

                JournalDetail::create([
                    'journal_header_id' => $header->id,
                    'akun_id' => $akunId,
                    'debit' => $debit,
                    'kredit' => $kredit,
                ]);
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'aksi' => 'Create',
                'module' => 'Jurnal Umum',
                'ip_address' => $request->ip(),
                'data' => json_encode(['id' => $header->id, 'nomor' => $header->nomor_jurnal]),
            ]);
        });

        Cache::forget("dashboard_data_" . auth()->user()->company_id);

        return redirect()->route('journals.index')->with('success', 'Jurnal berhasil ditambahkan.');
    }

    public function show(JournalHeader $journal)
    {
        $this->authorizeAccess($journal);
        $journal->load('details.akun', 'user');
        return view('journals.show', compact('journal'));
    }

    public function edit(JournalHeader $journal)
    {
        $this->authorizeAccess($journal);
        $journal->load('details.akun');
        $accounts = Account::active()
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('kode_akun')
            ->get();
        return view('journals.edit', compact('journal', 'accounts'));
    }

    public function update(Request $request, JournalHeader $journal)
    {
        $this->authorizeAccess($journal);

        $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
            'akun_id' => 'required|array|min:2',
            'akun_id.*' => 'required|exists:accounts,id',
            'debit' => 'required|array',
            'debit.*' => 'numeric|min:0',
            'kredit' => 'required|array',
            'kredit.*' => 'numeric|min:0',
        ]);

        $totalDebit = array_sum($request->debit);
        $totalKredit = array_sum($request->kredit);

        if (abs($totalDebit - $totalKredit) > 0.01) {
            return back()->withErrors(['msg' => 'Total debit harus sama dengan total kredit.'])->withInput();
        }

        DB::transaction(function () use ($request, $journal) {
            $journal->update([
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
            ]);

            $journal->details()->delete();

            foreach ($request->akun_id as $i => $akunId) {
                $debit = $request->debit[$i] ?? 0;
                $kredit = $request->kredit[$i] ?? 0;
                if ($debit == 0 && $kredit == 0) continue;

                JournalDetail::create([
                    'journal_header_id' => $journal->id,
                    'akun_id' => $akunId,
                    'debit' => $debit,
                    'kredit' => $kredit,
                ]);
            }

            AuditLog::create([
                'user_id' => Auth::id(),
                'aksi' => 'Update',
                'module' => 'Jurnal Umum',
                'ip_address' => request()->ip(),
                'data' => json_encode(['id' => $journal->id, 'nomor' => $journal->nomor_jurnal]),
            ]);
        });

        Cache::forget("dashboard_data_" . $journal->company_id);

        return redirect()->route('journals.index')->with('success', 'Jurnal berhasil diperbarui.');
    }

    public function destroy(JournalHeader $journal)
    {
        $this->authorizeAccess($journal);

        DB::transaction(function () use ($journal) {
            $journal->details()->delete();
            $journal->delete();

            AuditLog::create([
                'user_id' => Auth::id(),
                'aksi' => 'Delete',
                'module' => 'Jurnal Umum',
                'ip_address' => request()->ip(),
            ]);
        });

        Cache::forget("dashboard_data_" . $journal->company_id);

        return redirect()->route('journals.index')->with('success', 'Jurnal berhasil dihapus.');
    }

    private function authorizeAccess($journal)
    {
        if ($journal->company_id !== auth()->user()->company_id) {
            abort(403);
        }
    }
}
