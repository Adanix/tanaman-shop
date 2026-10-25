<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'tracking_token',
        'customer_name',
        'customer_email',
        'customer_phone',
        'fulfillment_type',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'shipping_carrier',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'delivered_by_user_id',
        'items_subtotal',
        'shipping_amount',
        'tax_amount',
        'total_amount',
        'currency_code',
        'payment_method',
        'payment_status',
        'order_status',
        'payment_expires_at',
        'stock_restored_at',
        'cancelled_at',
        'cancelled_by_user_id',
        'cancel_reason',
        'stripe_checkout_session_id',
        'stripe_checkout_url',
        'stripe_payment_intent_id',
        'bank_account_id',
        'bank_name_snapshot',
        'account_number_snapshot',
        'account_holder_snapshot',
        'sender_bank_name',
        'sender_account_name',
        'transfer_amount',
        'payment_proof_path',
        'payment_submitted_at',
        'payment_verified_by',
        'payment_verified_at',
        'payment_rejection_reason',
        'pickup_code',
        'picked_up_at',
        'pickup_payment_method',
        'is_gift',
    ];

    protected function casts(): array
    {
        return [
            'items_subtotal' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'transfer_amount' => 'decimal:2',
            'is_gift' => 'boolean',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'payment_expires_at' => 'datetime',
            'stock_restored_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'payment_submitted_at' => 'datetime',
            'payment_verified_at' => 'datetime',
            'picked_up_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function gift(): HasOne
    {
        return $this->hasOne(Gift::class);
    }

    public function refund(): HasOne
    {
        return $this->hasOne(Refund::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function deliveredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by_user_id');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by_user_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_verified_by');
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isCancelled(): bool
    {
        return $this->order_status === 'cancelled' || $this->payment_status === 'cancelled';
    }

    public function isDelivered(): bool
    {
        return $this->order_status === 'delivered';
    }

    public function isStorePickup(): bool
    {
        return $this->fulfillment_type === 'store_pickup';
    }

    public function isDelivery(): bool
    {
        return $this->fulfillment_type === 'delivery';
    }

    public function getSubtotalAttribute(): float
    {
        return (float) ($this->items_subtotal ?? 0);
    }

    public function getShippingFeeAttribute(): float
    {
        return (float) ($this->shipping_amount ?? 0);
    }

    public function getCarrierAttribute(): ?string
    {
        return $this->shipping_carrier;
    }

    public function canBeCancelledByCustomer(): bool
    {
        // PRD 4.9: Customer can cancel unpaid orders in 'pending', 'rejected', or 'pay_in_store_pending'.
        // Cannot cancel when 'awaiting_verification'.
        return in_array($this->payment_status, ['pending', 'rejected', 'pay_in_store_pending'], true)
            && $this->order_status !== 'cancelled'
            && $this->payment_status !== 'awaiting_verification';
    }

    public function canBeRefunded(): bool
    {
        return $this->isPaid() && (!$this->refund || $this->refund->status === 'failed');
    }
}
