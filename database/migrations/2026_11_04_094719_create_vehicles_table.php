<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_type_id')->constrained()->restrictOnDelete();
            $table->string('plate_number', 20)->unique();
            $table->string('owner_name', 100)->nullable();
            $table->string('brand', 50)->nullable();
            $table->string('color', 30)->nullable();
            $table->timestamps();

            $table->index('vehicle_type_id');
            $table->index('plate_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
