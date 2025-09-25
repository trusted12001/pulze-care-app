<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('risk_controls', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->foreignId('risk_assessment_id')->constrained('risk_assessments')->cascadeOnDelete();

            $table->string('control_type')->nullable();         // eliminate|substitute|engineering|admin|ppe|clinical
            $table->text('description');                         // what staff must do
            $table->boolean('mandatory')->default(false);
            $table->string('evidence_type')->nullable();         // photo|signature|reading
            $table->string('frequency')->nullable();             // each_shift|daily|weekly|prn

            $table->unsignedBigInteger('assigned_to_user_id')->nullable()->index();
            $table->string('assigned_to_role')->nullable();
            $table->boolean('link_to_assignment')->default(false);

            $table->unsignedTinyInteger('effectiveness_rating')->nullable(); // 1–5
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        Schema::table('risk_controls', function (Blueprint $table) {
            $table->foreign('assigned_to_user_id')->references('id')->on('users')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('risk_controls'); }
};
