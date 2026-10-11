<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrialBalance extends Model
{
    protected $fillable = [
        'company_id',
        'akun_id',
        'periode',
        'debit',
        'kredit',
    ];

    protected function casts(): array
    {
        return [
            'periode' => 'date',
            'debit' => 'decimal:2',
            'kredit' => 'decimal:2',
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
