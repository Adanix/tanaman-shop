<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserGarden extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plant_id',
        'order_id',
        'order_item_id',
        'unit_number',
        'nickname',
        'location_type',
        'acquired_at',
        'last_watered_at',
        'next_watering_at',
        'last_fertilized_at',
        'next_fertilizing_at',
        'last_watering_reminder_sent_at',
        'last_fertilizing_reminder_sent_at',
        'health_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'unit_number' => 'integer',
            'acquired_at' => 'datetime',
            'last_watered_at' => 'datetime',
            'next_watering_at' => 'datetime',
            'last_fertilized_at' => 'datetime',
            'next_fertilizing_at' => 'datetime',
            'last_watering_reminder_sent_at' => 'datetime',
            'last_fertilizing_reminder_sent_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function diagnoses(): HasMany
    {
        return $this->hasMany(AiDiagnosis::class);
    }

    public function isOutdoor(): bool
    {
        return $this->location_type === 'outdoor';
    }

    public function isWateringDue(): bool
    {
        return $this->next_watering_at !== null && $this->next_watering_at->isPast();
    }
}
