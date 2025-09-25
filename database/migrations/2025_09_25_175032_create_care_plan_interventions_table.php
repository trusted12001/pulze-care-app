<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('care_plan_interventions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->foreignId('care_plan_goal_id')->constrained('care_plan_goals')->cascadeOnDelete();

            $table->text('description');               // what staff must do
            $table->string('frequency')->nullable();   // each_shift|daily|weekly|prn
            $table->unsignedBigInteger('assigned_to_user_id')->nullable()->index();
            $table->foreign('assigned_to_user_id')->references('id')->on('users')->nullOnDelete();
            $table->string('assigned_to_role')->nullable();

            $table->boolean('link_to_assignment')->default(false); // integrate later
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('care_plan_interventions');
    }
};
