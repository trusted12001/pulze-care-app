<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('shift_templates', function (Blueprint $table) {
            // set default 0 and keep not-null
            $table->unsignedInteger('break_minutes')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('shift_templates', function (Blueprint $table) {
            // revert (remove default). If your old state was different, adjust.
            $table->unsignedInteger('break_minutes')->default(null)->change();
        });
    }
};
