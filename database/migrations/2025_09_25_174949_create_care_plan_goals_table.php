<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('care_plan_goals', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->foreignId('care_plan_section_id')->constrained('care_plan_sections')->cascadeOnDelete();
            $table->string('title');
            $table->text('success_criteria')->nullable();
            $table->date('target_date')->nullable();
            $table->string('status')->default('open'); // open|in_progress|completed
            $table->unsignedTinyInteger('display_order')->default(1);

            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('care_plan_goals');
    }
};
