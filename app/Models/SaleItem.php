<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id', 'product_id', 'quantity',
        'unit_price', 'cost_price', 'subtotal'
    ];

    protected $casts = [
        'unit_price'  => 'decimal:2',
        'cost_price'  => 'decimal:2',
        'subtotal'    => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function getProfit(): float
    {
        return ($this->unit_price - $this->cost_price) * $this->quantity;
    }
}
