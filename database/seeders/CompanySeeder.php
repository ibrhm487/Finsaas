<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        Company::create([
            'nama' => 'PT. FinSaaS Indonesia',
            'alamat' => 'Jl. Sudirman No. 123, Jakarta Pusat',
            'email' => 'info@finsaas.com',
            'telepon' => '021-12345678',
            'npwp' => '01.234.567.8-999.000',
        ]);
    }
}
