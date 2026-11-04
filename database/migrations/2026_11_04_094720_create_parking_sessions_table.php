<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parking_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code', 30)->unique();
            $table->foreignId('vehicle_id')->constrained()->restrictOnDelete();
            $table->foreignId('check_in_by')->constrained('users')->restrictOnDelete();
            $table->foreignId('check_out_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('hourly_rate');
            $table->timestamp('check_in_at');
            $table->timestamp('check_out_at')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('total_fee')->nullable();
            $table->string('status', 20)->default('parked');
            $table->timestamps();

            $table->index('vehicle_id');
            $table->index('status');
            $table->index('check_in_at');
        });

        DB::statement("CREATE UNIQUE INDEX parking_sessions_active_vehicle_unique
            ON parking_sessions (vehicle_id)
            WHERE status = 'parked'");
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_sessions');
    }
};
