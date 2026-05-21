<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'name', 'amount', 'expense_date', 'notes', 'created_by'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function journalEntry()
    {
        return $this->hasOne(JournalEntry::class);
    }
}
