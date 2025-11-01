<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->foreignId('risk_assessment_profile_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('risk_assessment_profiles')
                  ->nullOnDelete();

            // (Optional) If you plan to derive service_user_id from the profile going forward,
            // you can leave service_user_id in place for backward compatibility.
            $table->index('risk_assessment_profile_id');
        });
    }

    public function down(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('risk_assessment_profile_id');
        });
    }
};
