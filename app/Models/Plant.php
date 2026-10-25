<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plant extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'scientific_name',
        'slug',
        'description',
        'price',
        'stock',
        'low_stock_threshold',
        'image_path',
        'light_requirement',
        'default_location_type',
        'water_interval_days',
        'fertilize_interval_days',
        'temperature_range',
        'difficulty_level',
        'is_pet_friendly',
        'co2_absorption_rate',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'low_stock_threshold' => 'integer',
            'water_interval_days' => 'integer',
            'fertilize_interval_days' => 'integer',
            'is_pet_friendly' => 'boolean',
            'co2_absorption_rate' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PlantImage::class)->orderBy('sort_order');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function gardens(): HasMany
    {
        return $this->hasMany(UserGarden::class);
    }

    public function isInStock(): bool
    {
        return $this->is_active && $this->stock > 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_threshold;
    }
}
