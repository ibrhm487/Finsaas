<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CashFlowMappingSeeder extends Seeder
{
    protected $newAccountDefinitions = [
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

    public function run(): void
    {
        $company = Company::first();
        if (!$company) return;

        $this->createNewAccounts($company);
        $this->registerCashAccounts($company);
        $this->createMappings($company);
    }

    protected function createNewAccounts($company)
    {
        $existing = DB::table('accounts')
            ->where('company_id', $company->id)
            ->pluck('kode_akun')
            ->toArray();

        $newAccounts = [];
        foreach ($this->newAccountDefinitions as $def) {
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

    protected function registerCashAccounts($company)
    {
        $accounts = DB::table('accounts')
            ->where('company_id', $company->id)
            ->get()
            ->keyBy('kode_akun');

        $cashCodes = ['1111', '1112', '1113', '111', '112'];
        foreach ($cashCodes as $code) {
            if (isset($accounts[$code])) {
                DB::table('cash_accounts')->updateOrInsert(
                    ['company_id' => $company->id, 'account_id' => $accounts[$code]->id],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }

    protected function createMappings($company)
    {
        $accounts = DB::table('accounts')
            ->where('company_id', $company->id)
            ->get()
            ->keyBy('kode_akun');

        $mappings = [
            ['412', 'direct', 'operating', 'Penerimaan Jasa', true],
            ['411', 'direct', 'operating', 'Penerimaan Penjualan', true],
            ['4131', 'direct', 'operating', 'Penerimaan Lain-lain', true],
            ['511', 'direct', 'operating', 'Pembayaran Beban Operasional', false],
            ['512', 'direct', 'operating', 'Pembayaran Gaji', false],
            ['513', 'direct', 'operating', 'Pembayaran Sewa', false],
            ['514', 'direct', 'operating', 'Pembayaran Listrik & Air', false],
            ['5151', 'direct', 'operating', 'Beban Penyusutan (non-kas)', false],
            ['5161', 'direct', 'operating', 'Pembayaran Beban Internet', false],
            ['5171', 'direct', 'operating', 'Pembayaran Pajak', false],
            ['5181', 'direct', 'operating', 'Pembayaran Beban Bunga', false],
            ['5191', 'direct', 'operating', 'Pembayaran Lain-lain', false],
            ['123', 'direct', 'investing', 'Pembelian Peralatan', false],
            ['1211', 'direct', 'investing', 'Pembelian Kendaraan', false],
            ['1221', 'direct', 'investing', 'Pembelian Gedung', false],
            ['311', 'direct', 'financing', 'Setoran Modal', true],
            ['212', 'direct', 'financing', 'Penerimaan Pinjaman Bank', true],
            ['2211', 'direct', 'financing', 'Penerimaan Pinjaman Jangka Panjang', true],
            ['312', 'direct', 'financing', 'Laba Ditahan', true],
            ['121', 'direct', 'operating', 'Penerimaan Piutang', true],
            ['122', 'direct', 'operating', 'Pembelian Perlengkapan', false],
            ['212', 'direct', 'financing', 'Pembayaran Pinjaman', false],

            ['412', 'indirect', 'operating', 'Pendapatan Jasa', true],
            ['411', 'indirect', 'operating', 'Pendapatan Penjualan', true],
            ['4131', 'indirect', 'operating', 'Pendapatan Lain-lain', true],
            ['511', 'indirect', 'operating', 'Beban Operasional', false],
            ['512', 'indirect', 'operating', 'Beban Gaji', false],
            ['513', 'indirect', 'operating', 'Beban Sewa', false],
            ['514', 'indirect', 'operating', 'Beban Listrik & Air', false],
            ['5161', 'indirect', 'operating', 'Beban Internet', false],
            ['5171', 'indirect', 'operating', 'Beban Pajak', false],
            ['5181', 'indirect', 'operating', 'Beban Bunga', false],
            ['5191', 'indirect', 'operating', 'Beban Lain-lain', false],

            ['5151', 'indirect', 'operating', 'Beban Penyusutan', false, true],
            ['1232', 'indirect', 'operating', 'Akum. Penyusutan Peralatan', true, true],
            ['1212', 'indirect', 'operating', 'Akum. Penyusutan Kendaraan', true, true],
            ['1222', 'indirect', 'operating', 'Akum. Penyusutan Gedung', true, true],

            ['121', 'indirect', 'operating', 'Perubahan Piutang Usaha', false],
            ['122', 'indirect', 'operating', 'Perubahan Perlengkapan', false],
            ['1131', 'indirect', 'operating', 'Perubahan Persediaan', false],
            ['211', 'indirect', 'operating', 'Perubahan Hutang Usaha', true],
            ['2131', 'indirect', 'operating', 'Perubahan Hutang Pajak', true],
            ['2141', 'indirect', 'operating', 'Perubahan Hutang Gaji', true],
            ['2151', 'indirect', 'operating', 'Perubahan Pendapatan Diterima Dimuka', true],

            ['123', 'indirect', 'investing', 'Pembelian Peralatan', false],
            ['1211', 'indirect', 'investing', 'Pembelian Kendaraan', false],
            ['1221', 'indirect', 'investing', 'Pembelian Gedung', false],
            ['311', 'indirect', 'financing', 'Setoran Modal', true],
            ['212', 'indirect', 'financing', 'Penerimaan Pinjaman Bank', true],
            ['2211', 'indirect', 'financing', 'Penerimaan Pinjaman Jangka Panjang', true],
            ['312', 'indirect', 'financing', 'Laba Ditahan', true],
        ];

        foreach ($mappings as $map) {
            $account = $accounts[$map[0]] ?? null;
            if (!$account) continue;

            DB::table('cash_flow_mappings')->updateOrInsert(
                [
                    'company_id' => $company->id,
                    'account_id' => $account->id,
                    'method' => $map[1],
                ],
                [
                    'activity' => $map[2],
                    'cash_flow_category' => $map[3],
                    'is_inflow' => $map[4],
                    'is_non_cash' => $map[5] ?? false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
