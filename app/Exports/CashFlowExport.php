<?php

namespace App\Exports;

use App\Models\Account;
use App\Models\JournalDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CashFlowExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
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

        $pendapatanAkun = Account::where('company_id', $companyId)->where('kategori', 'Pendapatan')->pluck('id');
        $bebanAkun = Account::where('company_id', $companyId)->where('kategori', 'Beban')->pluck('id');

        $arusMasuk = JournalDetail::whereIn('akun_id', $pendapatanAkun)
            ->whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId)->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
            ->sum('kredit');

        $arusKeluar = JournalDetail::whereIn('akun_id', $bebanAkun)
            ->whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId)->whereDate('tanggal', '>=', $from)->whereDate('tanggal', '<=', $to))
            ->sum('debit');

        $data = collect();
        $data->push(['AKTIVITAS OPERASI', '', '']);
        $data->push(['Arus Kas Masuk', '', $arusMasuk]);
        $data->push(['Arus Kas Keluar', '', $arusKeluar]);
        $data->push(['Arus Kas Bersih - Operasi', '', $arusMasuk - $arusKeluar]);
        $data->push(['', '', '']);
        $data->push(['AKTIVITAS INVESTASI', '', '']);
        $data->push(['Arus Kas Masuk', '', 0]);
        $data->push(['Arus Kas Keluar', '', 0]);
        $data->push(['Arus Kas Bersih - Investasi', '', 0]);
        $data->push(['', '', '']);
        $data->push(['AKTIVITAS PENDANAAN', '', '']);
        $data->push(['Arus Kas Masuk', '', 0]);
        $data->push(['Arus Kas Keluar', '', 0]);
        $data->push(['Arus Kas Bersih - Pendanaan', '', 0]);
        $data->push(['', '', '']);
        $data->push(['SALDO AKHIR KAS', '', $arusMasuk - $arusKeluar]);

        return $data;
    }

    public function headings(): array
    {
        return ['Keterangan', '', 'Jumlah'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
