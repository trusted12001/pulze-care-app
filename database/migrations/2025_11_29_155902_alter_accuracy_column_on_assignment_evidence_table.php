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
        Schema::table('assignment_evidence', function (Blueprint $table) {
            // Change from decimal(6,2) to decimal(12,4)
            $table->decimal('accuracy', 12, 4)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assignment_evidence', function (Blueprint $table) {
            // Back to the original definition:
            $table->decimal('accuracy', 6, 2)->nullable()->change();
        });
    }
};
