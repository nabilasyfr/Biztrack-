<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = [
        'reference', 'description', 'entry_date',
        'sale_id', 'expense_id', 'created_by'
    ];

    protected $casts = ['entry_date' => 'date'];

    public function lines()
    {
        return $this->hasMany(JournalLine::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function expense()
    {
        return $this->belongsTo(Expense::class);
    }

    public function getTotalDebitsAttribute(): float
    {
        return $this->lines->sum('debit');
    }

    public function getTotalCreditsAttribute(): float
    {
        return $this->lines->sum('credit');
    }
}
