<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Akses penuh ke seluruh sistem'],
            ['name' => 'Akuntan', 'slug' => 'akuntan', 'description' => 'Mengelola jurnal dan laporan keuangan'],
            ['name' => 'Manajer', 'slug' => 'manajer', 'description' => 'Melihat laporan dan dashboard'],
            ['name' => 'Direktur', 'slug' => 'direktur', 'description' => 'Melihat laporan eksekutif'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
