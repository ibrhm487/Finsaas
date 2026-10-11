<?php

namespace App\Exports;

use App\Models\GeneralLedger;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GeneralLedgerExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $companyId = auth()->user()->company_id;
        $query = GeneralLedger::where('company_id', $companyId)->with('akun');

        if ($this->request->akun_id) $query->where('akun_id', $this->request->akun_id);
        if ($this->request->from) $query->whereDate('tanggal', '>=', $this->request->from);
        if ($this->request->to) $query->whereDate('tanggal', '<=', $this->request->to);

        return $query->orderBy('tanggal')->get()->map(function ($l) {
            return [
                $l->tanggal->format('d/m/Y'),
                $l->nomor_jurnal,
                $l->keterangan ?? '-',
                $l->debit > 0 ? $l->debit : 0,
                $l->kredit > 0 ? $l->kredit : 0,
                $l->saldo,
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'No. Jurnal', 'Keterangan', 'Debit', 'Kredit', 'Saldo'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}
