<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();

            // Explicit FK to singular table `service_users`
            $table->unsignedBigInteger('service_user_id');
            $table->foreign('service_user_id')
                ->references('id')->on('service_users')
                ->onDelete('cascade');

            // Risk type
            $table->unsignedBigInteger('risk_type_id');
            $table->foreign('risk_type_id')
                ->references('id')->on('risk_types')
                ->onDelete('cascade');

            $table->string('title');
            $table->text('context')->nullable();

            $table->unsignedTinyInteger('likelihood')->default(1);
            $table->unsignedTinyInteger('severity')->default(1);
            $table->unsignedTinyInteger('risk_score')->default(1);

            $table->string('risk_band')->default('low');
            $table->string('status')->default('draft');

            $table->date('next_review_date')->nullable();
            $table->string('review_frequency')->nullable();

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')
                ->references('id')->on('users')
                ->nullOnDelete(); // SET NULL on user delete

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('risk_assessments');
    }
};
