<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlowMapping extends Model
{
    protected $fillable = [
        'company_id',
        'account_id',
        'activity',
        'cash_flow_category',
        'method',
        'is_inflow',
        'is_non_cash',
    ];

    protected function casts(): array
    {
        return [
            'is_inflow' => 'boolean',
            'is_non_cash' => 'boolean',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    public function scopeByActivity($query, $activity)
    {
        return $query->where('activity', $activity);
    }
}
