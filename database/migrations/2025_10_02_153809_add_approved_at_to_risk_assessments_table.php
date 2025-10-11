<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            if (!Schema::hasColumn('risk_assessments', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            if (Schema::hasColumn('risk_assessments', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
        });
    }
};
