<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ['code', 'name', 'type', 'description'];

    // type: 'asset' | 'liability' | 'equity' | 'revenue' | 'expense'

    public function journalLines()
    {
        return $this->hasMany(JournalLine::class);
    }

    public function getBalance(): float
    {
        $debits  = $this->journalLines()->sum('debit');
        $credits = $this->journalLines()->sum('credit');

        if (in_array($this->type, ['asset', 'expense'])) {
            return $debits - $credits;
        }
        return $credits - $debits;
    }
}
