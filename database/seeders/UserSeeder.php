<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::first();
        $adminRole = Role::where('slug', 'admin')->first();
        $akuntanRole = Role::where('slug', 'akuntan')->first();
        $manajerRole = Role::where('slug', 'manajer')->first();
        $direkturRole = Role::where('slug', 'direktur')->first();

        if (!$company) return;

        $users = [
            [
                'name' => 'Admin FinSaaS',
                'email' => 'admin@finsaas.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole?->id,
                'company_id' => $company->id,
                'status' => 'active',
            ],
            [
                'name' => 'Akuntan FinSaaS',
                'email' => 'akuntan@finsaas.com',
                'password' => Hash::make('password'),
                'role_id' => $akuntanRole?->id,
                'company_id' => $company->id,
                'status' => 'active',
            ],
            [
                'name' => 'Manajer FinSaaS',
                'email' => 'manajer@finsaas.com',
                'password' => Hash::make('password'),
                'role_id' => $manajerRole?->id,
                'company_id' => $company->id,
                'status' => 'active',
            ],
            [
                'name' => 'Direktur FinSaaS',
                'email' => 'direktur@finsaas.com',
                'password' => Hash::make('password'),
                'role_id' => $direkturRole?->id,
                'company_id' => $company->id,
                'status' => 'active',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
