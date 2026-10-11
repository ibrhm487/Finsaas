<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    protected $fillable = [
        'journal_header_id',
        'akun_id',
        'debit',
        'kredit',
    ];

    protected function casts(): array
    {
        return [
            'debit' => 'decimal:2',
            'kredit' => 'decimal:2',
        ];
    }

    public function journalHeader()
    {
        return $this->belongsTo(JournalHeader::class);
    }

    public function akun()
    {
        return $this->belongsTo(Account::class, 'akun_id');
    }
}
