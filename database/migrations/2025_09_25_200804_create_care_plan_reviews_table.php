<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('care_plan_reviews', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->foreignId('care_plan_id')->constrained('care_plans')->cascadeOnDelete();
            $table->unsignedBigInteger('reviewed_by');
            $table->date('review_date')->nullable();
            $table->string('reason')->nullable(); // scheduled|change_in_needs|incident|other
            $table->text('summary')->nullable();  // what changed / notes

            // Optional suggest a next review date / frequency update
            $table->date('next_review_date_suggested')->nullable();
            $table->string('review_frequency_suggested')->nullable();

            $table->timestamps();
        });

        Schema::table('care_plan_reviews', function (Blueprint $table) {
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('care_plan_reviews'); }
};
