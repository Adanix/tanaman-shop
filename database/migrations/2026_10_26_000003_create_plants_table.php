<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->string('sku', 80)->unique();
            $table->string('name', 160);
            $table->string('scientific_name', 160)->nullable();
            $table->string('slug', 180)->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('low_stock_threshold')->default(5);
            $table->string('image_path', 500)->nullable();
            $table->enum('light_requirement', ['low', 'medium', 'bright_indirect', 'direct_sun'])->default('bright_indirect');
            $table->enum('default_location_type', ['indoor', 'outdoor'])->default('indoor');
            $table->unsignedTinyInteger('water_interval_days')->default(7);
            $table->unsignedSmallInteger('fertilize_interval_days')->default(30);
            $table->string('temperature_range', 80)->nullable();
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'expert'])->default('beginner');
            $table->boolean('is_pet_friendly')->default(false);
            $table->decimal('co2_absorption_rate', 10, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('category_id');
            $table->index('is_active');
            $table->index(['is_active', 'stock']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};
