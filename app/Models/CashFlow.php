<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    protected $fillable = [
        'company_id',
        'akun_id',
        'periode',
        'kategori',
        'arus_masuk',
        'arus_keluar',
        'saldo_akhir',
    ];

    protected function casts(): array
    {
        return [
            'periode' => 'date',
            'arus_masuk' => 'decimal:2',
            'arus_keluar' => 'decimal:2',
            'saldo_akhir' => 'decimal:2',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function akun()
    {
        return $this->belongsTo(Account::class, 'akun_id');
    }
}
