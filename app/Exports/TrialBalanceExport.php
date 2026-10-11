<?php

namespace App\Exports;

use App\Models\JournalDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TrialBalanceExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $companyId = auth()->user()->company_id;

        $query = JournalDetail::selectRaw('akun_id, SUM(debit) as total_debit, SUM(kredit) as total_kredit')
            ->whereHas('journalHeader', fn($q) => $q->where('company_id', $companyId))
            ->groupBy('akun_id')
            ->with('akun');

        if ($this->request->from) $query->whereHas('journalHeader', fn($q) => $q->whereDate('tanggal', '>=', $this->request->from));
        if ($this->request->to) $query->whereHas('journalHeader', fn($q) => $q->whereDate('tanggal', '<=', $this->request->to));

        return $query->get()->map(function ($item) {
            $totalDebit = max(0, $item->total_debit - $item->total_kredit);
            $totalKredit = max(0, $item->total_kredit - $item->total_debit);
            return [
                $item->akun?->kode_akun,
                $item->akun?->nama_akun,
                $totalDebit > 0 ? $totalDebit : 0,
                $totalKredit > 0 ? $totalKredit : 0,
            ];
        });
    }

    public function headings(): array
    {
        return ['Kode Akun', 'Nama Akun', 'Debit', 'Kredit'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
