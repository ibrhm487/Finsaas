<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\JournalDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BalanceSheetExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $companyId = auth()->user()->company_id;
        $periode = $this->request->periode ?? now()->format('Y-m-d');

        $data = collect();
        $data->push(['', 'ASET', '', '']);

        $aset = Account::where('company_id', $companyId)->where('kategori', 'Aset')->get();
        $totalAset = 0;
        foreach ($aset as $a) {
            $total = JournalDetail::where('akun_id', $a->id)
                ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '<=', $periode))
                ->sum('debit') - JournalDetail::where('akun_id', $a->id)
                ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '<=', $periode))
                ->sum('kredit');
            $data->push([$a->kode_akun, $a->nama_akun, '', $total]);
            $totalAset += $total;
        }
        $data->push(['', 'TOTAL ASET', '', $totalAset]);
        $data->push(['', '', '', '']);

        $data->push(['', 'KEWAJIBAN', '', '']);
        $kewajiban = Account::where('company_id', $companyId)->where('kategori', 'Kewajiban')->get();
        $totalKewajiban = 0;
        foreach ($kewajiban as $k) {
            $total = JournalDetail::where('akun_id', $k->id)
                ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '<=', $periode))
                ->sum('kredit') - JournalDetail::where('akun_id', $k->id)
                ->whereHas('journalHeader', fn($h) => $h->whereDate('tanggal', '<=', $periode))
                ->sum('debit');
            $data->push([$k->kode_akun, $k->nama_akun, '', $total]);
            $totalKewajiban += $total;
        }
        $data->push(['', 'TOTAL KEWAJIBAN', '', $totalKewajiban]);
        $data->push(['', '', '', '']);

        $data->push(['', 'MODAL', '', '']);
        $data->push(['', 'Laba Ditahan', '', '0']);
        $data->push(['', 'TOTAL MODAL', '', '0']);

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
