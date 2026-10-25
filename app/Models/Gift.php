<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gift extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'token',
        'recipient_name',
        'recipient_email',
        'message',
        'status',
        'claimed_by_user_id',
        'opened_at',
        'claimed_at',
        'notified_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'claimed_at' => 'datetime',
            'notified_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function claimedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimed_by_user_id');
    }

    public function isClaimable(): bool
    {
        return $this->status === 'claimable'
            && $this->expires_at !== null
            && $this->expires_at->isFuture()
            && $this->claimed_at === null;
    }
}
