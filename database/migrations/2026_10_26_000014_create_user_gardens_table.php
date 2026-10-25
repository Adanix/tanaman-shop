<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_gardens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('plant_id')->constrained('plants')->restrictOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->restrictOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained('order_items')->restrictOnDelete();
            $table->unsignedSmallInteger('unit_number')->nullable();

            $table->string('nickname', 120)->nullable();
            $table->enum('location_type', ['indoor', 'outdoor'])->default('indoor');
            $table->timestamp('acquired_at');
            $table->timestamp('last_watered_at')->nullable();
            $table->timestamp('next_watering_at')->nullable();
            $table->timestamp('last_fertilized_at')->nullable();
            $table->timestamp('next_fertilizing_at')->nullable();
            $table->timestamp('last_watering_reminder_sent_at')->nullable();
            $table->timestamp('last_fertilizing_reminder_sent_at')->nullable();
            $table->string('health_status', 40)->nullable()->default('healthy');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['order_item_id', 'unit_number']);
            $table->index(['user_id', 'next_watering_at']);
            $table->index(['user_id', 'next_fertilizing_at']);
            $table->index(['location_type', 'next_watering_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_gardens');
    }
};
