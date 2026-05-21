<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'category', 'supplier',
        'selling_price', 'cost_price', 'stock', 'min_stock'
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function inventoryLogs()
    {
        return $this->hasMany(InventoryLog::class);
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    public function getProfitMarginAttribute(): float
    {
        if ($this->selling_price <= 0) return 0;
        return (($this->selling_price - $this->cost_price) / $this->selling_price) * 100;
    }
}
