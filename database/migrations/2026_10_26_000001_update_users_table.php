<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'customer'])->default('customer')->after('password');
            $table->string('phone', 40)->nullable()->after('role');
            $table->string('city', 120)->nullable()->after('phone');
            $table->string('address', 255)->nullable()->after('city');
            $table->string('postal_code', 32)->nullable()->after('address');
            $table->string('timezone', 64)->default('Asia/Jakarta')->after('postal_code');
            $table->boolean('is_active')->default(true)->after('timezone');

            $table->index('role');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['is_active']);
            $table->dropColumn(['role', 'phone', 'city', 'address', 'postal_code', 'timezone', 'is_active']);
        });
    }
};
