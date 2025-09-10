<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('staff_visas')) {
            Schema::create('staff_visas', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                // Core status
                $table->enum('status', ['active','expired','pending','revoked'])->default('active');

                // Category/route (broad buckets)
                $table->enum('immigration_category', [
                    'settled','pre_settled','skilled_worker','student','family','british','irish','other'
                ])->default('other');

                // Identity / docs
                $table->string('nationality', 100)->nullable();
                $table->string('passport_number', 50)->nullable();
                $table->date('passport_expires_at')->nullable();

                // Visa/permit details
                $table->string('visa_number', 100)->nullable();
                $table->string('brp_number', 50)->nullable();
                $table->date('brp_expires_at')->nullable();
                $table->string('share_code', 20)->nullable(); // GOV.UK RTW share code
                $table->string('sponsor_licence_number', 50)->nullable();
                $table->string('cos_number', 50)->nullable();

                // Issue data
                $table->string('issued_country', 100)->nullable();
                $table->date('issued_at')->nullable();
                $table->date('expires_at')->nullable();

                // Work restrictions
                $table->decimal('weekly_hours_cap', 4, 1)->nullable(); // e.g., 20.0
                $table->boolean('term_time_only')->default(false);
                $table->text('restrictions')->nullable();

                // Audit / attachments
                $table->unsignedBigInteger('evidence_file_id')->nullable();
                $table->unsignedBigInteger('checked_by_user_id')->nullable();
                $table->text('notes')->nullable();

                $table->timestamps();
                $table->softDeletes();

                // Indexes
                $table->index(['tenant_id','staff_profile_id']);
                $table->index(['tenant_id','status']);
                $table->index(['tenant_id','immigration_category']);
                $table->index(['tenant_id','expires_at']);

                // FK
                $table->foreign('staff_profile_id')
                    ->references('id')->on('staff_profiles')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_visas');
    }
};
