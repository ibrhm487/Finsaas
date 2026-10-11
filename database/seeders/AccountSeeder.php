<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Company;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();

        if (!$company) return;

        $accounts = [
            ['kode_akun' => '111', 'nama_akun' => 'Kas', 'kategori' => 'Aset', 'saldo_normal' => 'Debit'],
            ['kode_akun' => '112', 'nama_akun' => 'Bank', 'kategori' => 'Aset', 'saldo_normal' => 'Debit'],
            ['kode_akun' => '121', 'nama_akun' => 'Piutang Usaha', 'kategori' => 'Aset', 'saldo_normal' => 'Debit'],
            ['kode_akun' => '211', 'nama_akun' => 'Hutang Usaha', 'kategori' => 'Kewajiban', 'saldo_normal' => 'Kredit'],
            ['kode_akun' => '311', 'nama_akun' => 'Modal', 'kategori' => 'Modal', 'saldo_normal' => 'Kredit'],
            ['kode_akun' => '411', 'nama_akun' => 'Pendapatan', 'kategori' => 'Pendapatan', 'saldo_normal' => 'Kredit'],
            ['kode_akun' => '511', 'nama_akun' => 'Beban Operasional', 'kategori' => 'Beban', 'saldo_normal' => 'Debit'],
            ['kode_akun' => '512', 'nama_akun' => 'Beban Gaji', 'kategori' => 'Beban', 'saldo_normal' => 'Debit'],
            ['kode_akun' => '513', 'nama_akun' => 'Beban Sewa', 'kategori' => 'Beban', 'saldo_normal' => 'Debit'],
            ['kode_akun' => '412', 'nama_akun' => 'Pendapatan Jasa', 'kategori' => 'Pendapatan', 'saldo_normal' => 'Kredit'],
            ['kode_akun' => '122', 'nama_akun' => 'Perlengkapan', 'kategori' => 'Aset', 'saldo_normal' => 'Debit'],
            ['kode_akun' => '123', 'nama_akun' => 'Peralatan', 'kategori' => 'Aset', 'saldo_normal' => 'Debit'],
            ['kode_akun' => '212', 'nama_akun' => 'Hutang Bank', 'kategori' => 'Kewajiban', 'saldo_normal' => 'Kredit'],
            ['kode_akun' => '312', 'nama_akun' => 'Laba Ditahan', 'kategori' => 'Modal', 'saldo_normal' => 'Kredit'],
            ['kode_akun' => '514', 'nama_akun' => 'Beban Listrik & Air', 'kategori' => 'Beban', 'saldo_normal' => 'Debit'],
        ];

        foreach ($accounts as $account) {
            $account['company_id'] = $company->id;
            $account['status'] = 'active';
            Account::create($account);
        }
    }
}
