<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('risk_reviews', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->foreignId('risk_assessment_id')->constrained('risk_assessments')->cascadeOnDelete();
            $table->unsignedBigInteger('reviewed_by');
            $table->date('review_date')->nullable();
            $table->string('reason')->nullable(); // scheduled|incident|health_change|other

            $table->unsignedTinyInteger('likelihood_new')->nullable();
            $table->unsignedTinyInteger('severity_new')->nullable();
            $table->unsignedTinyInteger('score_new')->nullable();
            $table->string('band_new')->nullable(); // low|medium|high

            $table->text('recommendations')->nullable();
            $table->string('outcome')->nullable(); // continue|modify|archive

            $table->timestamps();
        });

        Schema::table('risk_reviews', function (Blueprint $table) {
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('risk_reviews'); }
};
