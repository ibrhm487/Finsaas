<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\JournalDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class IncomeStatementExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $companyId = auth()->user()->company_id;
        $from = $this->request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to = $this->request->to ?? now()->format('Y-m-d');

        $data = collect();
        $data->push(['', 'PENDAPATAN', '', '']);

        $pendapatan = Account::where('company_id', $companyId)->where('kategori', 'Pendapatan')->get();
        foreach ($pendapatan as $p) {
            $total = JournalDetail::where('akun_id', $p->id)
                ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
                ->sum('kredit') - JournalDetail::where('akun_id', $p->id)
                ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
                ->sum('debit');
            $data->push([$p->kode_akun, $p->nama_akun, '', $total]);
        }

        $totalPendapatan = $pendapatan->sum(function ($p) use ($from, $to) {
            return JournalDetail::where('akun_id', $p->id)
                ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
                ->sum('kredit') - JournalDetail::where('akun_id', $p->id)
                ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
                ->sum('debit');
        });
        $data->push(['', 'TOTAL PENDAPATAN', '', $totalPendapatan]);
        $data->push(['', '', '', '']);

        $data->push(['', 'BEBAN', '', '']);
        $beban = Account::where('company_id', $companyId)->where('kategori', 'Beban')->get();
        foreach ($beban as $b) {
            $total = JournalDetail::where('akun_id', $b->id)
                ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
                ->sum('debit') - JournalDetail::where('akun_id', $b->id)
                ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
                ->sum('kredit');
            $data->push([$b->kode_akun, $b->nama_akun, '', $total]);
        }

        $totalBeban = $beban->sum(function ($b) use ($from, $to) {
            return JournalDetail::where('akun_id', $b->id)
                ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
                ->sum('debit') - JournalDetail::where('akun_id', $b->id)
                ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
                ->sum('kredit');
        });
        $data->push(['', 'TOTAL BEBAN', '', $totalBeban]);
        $data->push(['', '', '', '']);
        $data->push(['', 'LABA BERSIH', '', $totalPendapatan - $totalBeban]);

        return $data;
    }

    public function headings(): array
    {
        return ['Kode', 'Nama Akun', '', 'Jumlah'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
