<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BalanceSheet extends Model
{
    protected $fillable = [
        'company_id',
        'akun_id',
        'periode',
        'aset',
        'kewajiban',
        'modal',
    ];

    protected function casts(): array
    {
        return [
            'periode' => 'date',
            'aset' => 'decimal:2',
            'kewajiban' => 'decimal:2',
            'modal' => 'decimal:2',
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
