<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();

            // tenancy
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();

            // 1–1 with users
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            // employment
            $table->string('job_title')->nullable();
            $table->enum('employment_status', ['active', 'on_leave', 'terminated'])->default('active');

            // compliance (UK)
            $table->string('dbs_number')->nullable();
            $table->date('dbs_issued_at')->nullable();
            $table->dateTime('mandatory_training_completed_at')->nullable();
            $table->string('nmc_pin')->nullable();   // nurses
            $table->string('gphc_pin')->nullable();  // pharmacists (optional)
            $table->dateTime('right_to_work_verified_at')->nullable();

            // contact
            $table->string('phone')->nullable();
            $table->string('work_email')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'employment_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_profiles');
    }
};
