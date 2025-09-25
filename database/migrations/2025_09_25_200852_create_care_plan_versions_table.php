<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('care_plan_versions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->foreignId('care_plan_id')->constrained('care_plans')->cascadeOnDelete();
            $table->unsignedInteger('version');              // v2, v3, ...
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('change_note')->nullable();         // why we bumped
            $table->json('snapshot')->nullable();            // optional: sections/goals/interventions JSON

            $table->timestamps();
        });

        Schema::table('care_plan_versions', function (Blueprint $table) {
            $table->unique(['care_plan_id','version']);
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('care_plan_versions'); }
};
