<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('risk_assessment_profiles', function (Blueprint $table) {
            $table->id();

            $table->foreignId('service_user_id')
                  ->constrained('service_users')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();

            $table->string('title')->default('Comprehensive Risk Assessment');
            $table->enum('status', ['draft','active','archived'])->default('draft');

            $table->date('start_date')->nullable();
            $table->date('next_review_date')->nullable();
            $table->string('review_frequency')->nullable(); // e.g. "24 weeks"
            $table->text('summary')->nullable();

            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['service_user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_assessment_profiles');
    }
};
