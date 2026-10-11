<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'nama',
        'logo',
        'alamat',
        'email',
        'telepon',
        'npwp',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }

    public function journalHeaders()
    {
        return $this->hasMany(JournalHeader::class);
    }

    public function generalLedgers()
    {
        return $this->hasMany(GeneralLedger::class);
    }

    public function trialBalances()
    {
        return $this->hasMany(TrialBalance::class);
    }

    public function incomeStatements()
    {
        return $this->hasMany(IncomeStatement::class);
    }

    public function balanceSheets()
    {
        return $this->hasMany(BalanceSheet::class);
    }

    public function cashFlows()
    {
        return $this->hasMany(CashFlow::class);
    }

    public function cashFlowMappings()
    {
        return $this->hasMany(CashFlowMapping::class);
    }

    public function cashFlowReports()
    {
        return $this->hasMany(CashFlowReport::class);
    }

    public function cashAccounts()
    {
        return $this->hasMany(CashAccount::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }
}
