<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeStatement extends Model
{
    protected $fillable = [
        'company_id',
        'akun_id',
        'periode',
        'pendapatan',
        'beban',
        'laba_bersih',
    ];

    protected function casts(): array
    {
        return [
            'periode' => 'date',
            'pendapatan' => 'decimal:2',
            'beban' => 'decimal:2',
            'laba_bersih' => 'decimal:2',
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
