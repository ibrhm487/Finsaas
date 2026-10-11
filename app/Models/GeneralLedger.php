<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralLedger extends Model
{
    protected $fillable = [
        'company_id',
        'akun_id',
        'tanggal',
        'nomor_jurnal',
        'keterangan',
        'debit',
        'kredit',
        'saldo',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'debit' => 'decimal:2',
            'kredit' => 'decimal:2',
            'saldo' => 'decimal:2',
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
