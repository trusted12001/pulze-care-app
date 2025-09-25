<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('care_plan_sections', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->foreignId('care_plan_id')->constrained('care_plans')->cascadeOnDelete();
            $table->string('name');             // e.g., Health, Nutrition, Medication...
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('display_order')->default(1);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('care_plan_sections');
    }
};
