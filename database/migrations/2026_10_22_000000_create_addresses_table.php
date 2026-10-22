<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('street');
            $table->string('postal_code', 20);
            $table->string('phone', 20)->nullable();
            $table->string('label', 50)->nullable();
            $table->boolean('is_default')->default(false);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
