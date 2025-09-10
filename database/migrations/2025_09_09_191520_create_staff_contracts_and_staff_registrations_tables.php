<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // staff_contracts
        if (!Schema::hasTable('staff_contracts')) {
            Schema::create('staff_contracts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->string('contract_ref', 100)->nullable();
                $table->enum('contract_type', ['permanent','fixed_term','bank','casual','agency'])->default('permanent');
                $table->decimal('hours_per_week', 5, 2)->nullable();
                $table->boolean('wtd_opt_out')->default(false); // Working Time Directive (48h)
                $table->date('start_date');
                $table->date('end_date')->nullable();

                $table->string('job_grade_band', 50)->nullable();
                $table->string('pay_scale', 50)->nullable();
                $table->decimal('fte_salary', 12, 2)->nullable();
                $table->decimal('hourly_rate', 10, 2)->nullable();
                $table->string('cost_centre', 50)->nullable();

                $table->text('notes')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['tenant_id', 'staff_profile_id']);
                $table->index(['tenant_id', 'contract_type']);
                $table->index(['tenant_id', 'start_date']);
                $table->index(['tenant_id', 'end_date']);

                $table->foreign('staff_profile_id')
                    ->references('id')->on('staff_profiles')
                    ->cascadeOnDelete();
            });
        }

        // staff_registrations
        if (!Schema::hasTable('staff_registrations')) {
            Schema::create('staff_registrations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->enum('body', ['NMC','HCPC','GMC','GPhC','SWE','Other'])->default('Other');
                $table->string('pin_number', 120)->nullable();
                $table->enum('status', ['active','lapsed','suspended','pending'])->default('active');
                $table->date('first_registered_at')->nullable();
                $table->date('expires_at')->nullable();
                $table->date('revalidation_due_at')->nullable();

                $table->text('notes')->nullable();
                $table->unsignedBigInteger('evidence_file_id')->nullable(); // if you later add a documents table

                $table->timestamps();
                $table->softDeletes();

                $table->index(['tenant_id', 'staff_profile_id']);
                $table->index(['tenant_id', 'body']);
                $table->index(['tenant_id', 'status']);
                $table->index(['tenant_id', 'expires_at']);
                $table->index(['tenant_id', 'revalidation_due_at']);

                $table->foreign('staff_profile_id')
                    ->references('id')->on('staff_profiles')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('staff_registrations')) {
            Schema::dropIfExists('staff_registrations');
        }
        if (Schema::hasTable('staff_contracts')) {
            Schema::dropIfExists('staff_contracts');
        }
    }
};
