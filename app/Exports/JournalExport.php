<?php

namespace App\Exports;

use App\Models\JournalDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JournalExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $journal;

    public function __construct($journal)
    {
        $this->journal = $journal;
    }

    public function collection()
    {
        return $this->journal->details->map(function ($d) {
            return [
                $d->akun?->kode_akun,
                $d->akun?->nama_akun,
                $d->debit > 0 ? $d->debit : 0,
                $d->kredit > 0 ? $d->kredit : 0,
            ];
        });
    }

    public function headings(): array
    {
        return [
            ['Nomor Jurnal: ' . $this->journal->nomor_jurnal, '', '', ''],
            ['Tanggal: ' . $this->journal->tanggal->format('d/m/Y'), '', '', ''],
            ['', '', '', ''],
            ['Kode Akun', 'Nama Akun', 'Debit', 'Kredit'],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [4 => ['font' => ['bold' => true]]];
    }
}
