<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = [
        'company_id',
        'kode_akun',
        'nama_akun',
        'kategori',
        'saldo_normal',
        'status',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function journalDetails()
    {
        return $this->hasMany(JournalDetail::class, 'akun_id');
    }

    public function generalLedgers()
    {
        return $this->hasMany(GeneralLedger::class, 'akun_id');
    }

    public function trialBalances()
    {
        return $this->hasMany(TrialBalance::class, 'akun_id');
    }

    public function incomeStatements()
    {
        return $this->hasMany(IncomeStatement::class, 'akun_id');
    }

    public function balanceSheets()
    {
        return $this->hasMany(BalanceSheet::class, 'akun_id');
    }

    public function cashFlows()
    {
        return $this->hasMany(CashFlow::class, 'akun_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByCategory($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }
}
