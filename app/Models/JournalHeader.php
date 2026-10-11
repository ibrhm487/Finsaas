<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalHeader extends Model
{
    protected $fillable = [
        'company_id',
        'nomor_jurnal',
        'tanggal',
        'keterangan',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }
}
