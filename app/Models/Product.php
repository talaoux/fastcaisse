<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference',
        'barcode',
        'name',
        'description',
        'category',
        'purchase_price',
        'selling_price',
        'stock',
        'minimum_stock',
        'image',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    /**
     * Scope a query to only include active products.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include products with low stock.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowStock($query)
    {
        return $query->whereColumn('stock', '<=', 'minimum_stock');
    }

    /**
     * Get the product image URL.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }

        // Image par défaut (placeholder SVG)
        return 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><rect width="200" height="200" fill="#f1f5f9"/><rect x="50" y="60" width="100" height="80" rx="8" fill="#cbd5e1"/><path d="M70 100 L90 120 L130 80" stroke="#94a3b8" stroke-width="4" fill="none" stroke-linecap="round" stroke-linejoin="round"/><circle cx="100" cy="100" r="60" fill="none" stroke="#e2e8f0" stroke-width="2"/></svg>');
    }

    /**
     * Get the formatted purchase price.
     *
     * @return string
     */
    public function getFormattedPurchasePriceAttribute()
    {
        return number_format($this->purchase_price, 0, ',', ' ') . ' Ar';
    }

    /**
     * Get the formatted selling price.
     *
     * @return string
     */
    public function getFormattedSellingPriceAttribute()
    {
        return number_format($this->selling_price, 0, ',', ' ') . ' Ar';
    }

    /**
     * Get the profit margin.
     *
     * @return float
     */
    public function getProfitMarginAttribute()
    {
        if ($this->purchase_price > 0) {
            return round((($this->selling_price - $this->purchase_price) / $this->purchase_price) * 100, 2);
        }

        return 0;
    }

    /**
     * Get the profit amount.
     *
     * @return float
     */
    public function getProfitAttribute()
    {
        return $this->selling_price - $this->purchase_price;
    }

    /**
     * Check if product is in stock.
     *
     * @return bool
     */
    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }

    /**
     * Check if product is low stock.
     *
     * @return bool
     */
    public function getIsLowStockAttribute()
    {
        return $this->stock > 0 && $this->stock <= $this->minimum_stock;
    }

    /**
     * Check if product is out of stock.
     *
     * @return bool
     */
    public function getIsOutOfStockAttribute()
    {
        return $this->stock <= 0;
    }
}