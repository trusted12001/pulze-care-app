<?php

// database/migrations/2025_09_23_100000_create_risk_types_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('risk_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g. Falls, Choking
            $table->text('default_guidance')->nullable();
            $table->json('default_matrix')->nullable(); // reserve for specialised scoring rules
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('risk_types'); }
};
