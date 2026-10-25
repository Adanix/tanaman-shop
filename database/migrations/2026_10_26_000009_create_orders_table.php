<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('order_number', 40)->unique();
            $table->string('tracking_token', 128)->unique();
            $table->string('customer_name', 100);
            $table->string('customer_email', 255);
            $table->string('customer_phone', 40)->nullable();

            // Fulfillment
            $table->enum('fulfillment_type', ['delivery', 'store_pickup'])->default('delivery');
            $table->string('shipping_address', 255)->nullable();
            $table->string('shipping_city', 120)->nullable();
            $table->string('shipping_postal_code', 32)->nullable();
            $table->string('shipping_carrier', 120)->nullable();
            $table->string('tracking_number', 160)->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->foreignId('delivered_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            // Financial Snapshot
            $table->decimal('items_subtotal', 12, 2);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->char('currency_code', 3)->default('USD');

            // Statuses
            $table->enum('payment_method', ['online_stripe', 'bank_transfer', 'pay_in_store']);
            $table->enum('payment_status', [
                'pending',
                'awaiting_verification',
                'paid',
                'rejected',
                'pay_in_store_pending',
                'failed',
                'expired',
                'cancelled',
                'refunded'
            ])->default('pending');
            $table->enum('order_status', [
                'awaiting_payment',
                'awaiting_verification',
                'processing',
                'ready_for_pickup',
                'shipped',
                'delivered',
                'cancelled'
            ])->default('awaiting_payment');

            // Expiry & Cancellation
            $table->timestamp('payment_expires_at')->nullable();
            $table->timestamp('stock_restored_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->foreignId('cancelled_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('cancel_reason', 500)->nullable();

            // Stripe Details
            $table->string('stripe_checkout_session_id', 255)->nullable()->unique();
            $table->text('stripe_checkout_url')->nullable();
            $table->string('stripe_payment_intent_id', 255)->nullable()->unique();

            // Bank Transfer Details
            $table->foreignId('bank_account_id')->nullable()->constrained('bank_accounts')->nullOnDelete();
            $table->string('bank_name_snapshot', 120)->nullable();
            $table->string('account_number_snapshot', 120)->nullable();
            $table->string('account_holder_snapshot', 160)->nullable();
            $table->string('sender_bank_name', 120)->nullable();
            $table->string('sender_account_name', 160)->nullable();
            $table->decimal('transfer_amount', 12, 2)->nullable();
            $table->string('payment_proof_path', 500)->nullable();
            $table->timestamp('payment_submitted_at')->nullable();
            $table->foreignId('payment_verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('payment_verified_at')->nullable();
            $table->string('payment_rejection_reason', 500)->nullable();

            // Pickup & Gift Details
            $table->char('pickup_code', 6)->nullable()->unique();
            $table->timestamp('picked_up_at')->nullable();
            $table->enum('pickup_payment_method', ['cash', 'card_terminal', 'other'])->nullable();
            $table->boolean('is_gift')->default(false);

            $table->timestamps();

            // Indexes
            $table->index(['payment_status', 'payment_expires_at']);
            $table->index(['order_status', 'created_at']);
            $table->index(['customer_email', 'created_at']);
            $table->index('user_id');
            $table->index('fulfillment_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
