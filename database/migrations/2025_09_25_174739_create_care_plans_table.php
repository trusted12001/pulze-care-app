<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('care_plans', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->unsignedBigInteger('service_user_id')->index();
            $table->foreign('service_user_id')
                  ->references('id')->on('service_users')->onDelete('cascade');

            $table->string('title'); // e.g., "Comprehensive Care Plan"
            $table->string('status')->default('draft'); // draft|active|archived
            $table->unsignedInteger('version')->default(1);

            $table->date('start_date')->nullable();
            $table->date('next_review_date')->nullable();
            $table->string('review_frequency')->nullable(); // e.g. "24 weeks"
            $table->text('summary')->nullable();

            $table->unsignedBigInteger('author_id')->index();
            $table->foreign('author_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('approved_by')->nullable()->index();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }
    public function down(): void {
        Schema::dropIfExists('care_plans');
    }
};
