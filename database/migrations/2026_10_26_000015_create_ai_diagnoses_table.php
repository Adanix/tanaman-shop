<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('user_garden_id')->nullable()->constrained('user_gardens')->nullOnDelete();
            $table->string('image_path', 500);
            $table->enum('status', ['queued', 'processing', 'completed', 'failed'])->default('queued');
            $table->text('diagnosis')->nullable();
            $table->string('severity', 40)->nullable();
            $table->json('ai_response')->nullable();
            $table->json('recommended_plant_ids')->nullable();
            $table->string('model', 120)->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_diagnoses');
    }
};
