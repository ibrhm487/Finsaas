<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFlowReport extends Model
{
    protected $fillable = [
        'company_id',
        'periode_start',
        'periode_end',
        'method',
        'operating_activities',
        'investing_activities',
        'financing_activities',
        'net_operating',
        'net_investing',
        'net_financing',
        'net_increase',
        'beginning_cash',
        'ending_cash',
        'generated_by',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'periode_start' => 'date',
            'periode_end' => 'date',
            'operating_activities' => 'array',
            'investing_activities' => 'array',
            'financing_activities' => 'array',
            'net_operating' => 'decimal:2',
            'net_investing' => 'decimal:2',
            'net_financing' => 'decimal:2',
            'net_increase' => 'decimal:2',
            'beginning_cash' => 'decimal:2',
            'ending_cash' => 'decimal:2',
            'generated_at' => 'datetime',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
