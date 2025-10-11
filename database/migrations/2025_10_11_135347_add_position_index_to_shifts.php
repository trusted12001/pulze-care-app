<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Add the column if missing
        Schema::table('shifts', function (Blueprint $table) {
            if (!Schema::hasColumn('shifts', 'position_index')) {
                $table->unsignedSmallInteger('position_index')->default(1)->after('status');
            }
        });

        // 2) Create the unique index if it doesn't exist (no Doctrine required)
        $database = Schema::getConnection()->getDatabaseName();
        $indexExists = DB::table('information_schema.statistics')
            ->where('table_schema', $database)
            ->where('table_name', 'shifts')
            ->where('index_name', 'shifts_unique_slot')
            ->exists();

        if (! $indexExists) {
            Schema::table('shifts', function (Blueprint $table) {
                $table->unique(
                    ['rota_period_id','location_id','role','start_at','end_at','position_index'],
                    'shifts_unique_slot'
                );
            });
        }
    }

    public function down(): void
    {
        // Drop the unique index if present, then the column
        $database = Schema::getConnection()->getDatabaseName();
        $indexExists = DB::table('information_schema.statistics')
            ->where('table_schema', $database)
            ->where('table_name', 'shifts')
            ->where('index_name', 'shifts_unique_slot')
            ->exists();

        Schema::table('shifts', function (Blueprint $table) use ($indexExists) {
            if ($indexExists) {
                $table->dropUnique('shifts_unique_slot');
            }
            if (Schema::hasColumn('shifts', 'position_index')) {
                $table->dropColumn('position_index');
            }
        });
    }
};
