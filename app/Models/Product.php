<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','name','unit','price','stock','min_stock','is_active'];
    protected $casts = ['price' => 'decimal:2', 'is_active' => 'boolean'];

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function saleItems(): HasMany { return $this->hasMany(SaleItem::class); }
    public function stockMovements(): HasMany { return $this->hasMany(StockMovement::class); }
}