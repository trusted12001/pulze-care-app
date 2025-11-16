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
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->text('hazard')->nullable();
            $table->text('controls')->nullable();
            $table->text('residual_likelihood')->nullable();
            $table->text('residual_severity')->nullable();
            $table->text('residual_score')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_assessment', function (Blueprint $table) {
            //
        });
    }
};
