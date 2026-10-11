<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $companies = DB::table('companies')->get();
        if ($companies->isEmpty()) return;

        foreach ($companies as $company) {
            $existing = DB::table('accounts')
                ->where('company_id', $company->id)
                ->pluck('kode_akun')
                ->toArray();

            $newAccounts = [];

            $definitions = [
                ['1111', 'Kas Besar', 'Aset', 'Debit'],
                ['1112', 'Bank BCA', 'Aset', 'Debit'],
                ['1113', 'Bank Mandiri', 'Aset', 'Debit'],
                ['1131', 'Persediaan Barang', 'Aset', 'Debit'],
                ['1211', 'Kendaraan', 'Aset', 'Debit'],
                ['1212', 'Akum. Penyusutan Kendaraan', 'Aset', 'Kredit'],
                ['1221', 'Gedung', 'Aset', 'Debit'],
                ['1222', 'Akum. Penyusutan Gedung', 'Aset', 'Kredit'],
                ['1232', 'Akum. Penyusutan Peralatan', 'Aset', 'Kredit'],
                ['2131', 'Hutang Pajak', 'Kewajiban', 'Kredit'],
                ['2141', 'Hutang Gaji', 'Kewajiban', 'Kredit'],
                ['2151', 'Pendapatan Diterima Dimuka', 'Kewajiban', 'Kredit'],
                ['2211', 'Hutang Bank Jangka Panjang', 'Kewajiban', 'Kredit'],
                ['3131', 'Ikhtisar Laba Rugi', 'Modal', 'Kredit'],
                ['4131', 'Pendapatan Lain-lain', 'Pendapatan', 'Kredit'],
                ['5151', 'Beban Penyusutan', 'Beban', 'Debit'],
                ['5161', 'Beban Internet', 'Beban', 'Debit'],
                ['5171', 'Beban Pajak', 'Beban', 'Debit'],
                ['5181', 'Beban Bunga', 'Beban', 'Debit'],
                ['5191', 'Beban Lain-lain', 'Beban', 'Debit'],
            ];

            foreach ($definitions as $def) {
                if (!in_array($def[0], $existing)) {
                    $newAccounts[] = [
                        'company_id' => $company->id,
                        'kode_akun' => $def[0],
                        'nama_akun' => $def[1],
                        'kategori' => $def[2],
                        'saldo_normal' => $def[3],
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($newAccounts)) {
                DB::table('accounts')->insert($newAccounts);
            }
        }
    }

    public function down(): void
    {
        $codes = [
            '1111','1112','1113','1131','1211','1212',
            '1221','1222','1232','2131','2211','3131',
            '5151','5161','5171','5181','5191',
            '2141','2151','4131',
        ];

        DB::table('accounts')->whereIn('kode_akun', $codes)->delete();
    }
};
