<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shift_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('shift_templates', 'days_of_week_json')) {
                $table->json('days_of_week_json')->nullable()->after('headcount'); // e.g., ["mon","tue","wed"]
            }
            if (!Schema::hasColumn('shift_templates', 'notes')) {
                $table->text('notes')->nullable()->after('skills_json');
            }
        });
    }

    public function down(): void
    {
        Schema::table('shift_templates', function (Blueprint $table) {
            if (Schema::hasColumn('shift_templates', 'days_of_week_json')) {
                $table->dropColumn('days_of_week_json');
            }
            if (Schema::hasColumn('shift_templates', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
