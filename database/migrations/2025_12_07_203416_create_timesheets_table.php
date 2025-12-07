<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();

            // Which shift this record is for
            $table->foreignId('shift_id')
                ->constrained()
                ->cascadeOnDelete();

            // Which staff member (User) actually worked
            $table->foreignId('staff_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Optional: store location redundantly for easier reporting/filtering
            $table->foreignId('location_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Check-in / Check-out timestamps
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('check_out_at')->nullable();

            // GPS coordinates at check-in/out (optional but ready)
            $table->decimal('check_in_lat', 10, 7)->nullable();
            $table->decimal('check_in_lng', 10, 7)->nullable();
            $table->decimal('check_out_lat', 10, 7)->nullable();
            $table->decimal('check_out_lng', 10, 7)->nullable();

            // Status: pending, in_progress, completed, no_show, cancelled, etc.
            $table->string('status', 30)->default('pending');

            // Calculated worked minutes (actual) vs scheduled (from shift)
            $table->integer('worked_minutes')->nullable();

            // Optional notes (e.g. "left early", "agency cover", etc.)
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};
