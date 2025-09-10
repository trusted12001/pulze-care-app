<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // -------------------------
        // Payroll
        // -------------------------
        if (!Schema::hasTable('staff_payroll')) {
            Schema::create('staff_payroll', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->string('ni_number', 15); // will be encrypted in model cast
                $table->string('tax_code', 10)->nullable();
                $table->enum('starter_declaration', ['a','b','c'])->nullable();
                $table->enum('student_loan_plan', ['none','plan1','plan2','plan4','plan5'])->default('none');
                $table->boolean('postgrad_loan')->default(false);
                $table->string('payroll_number', 50)->nullable();

                $table->timestamps();

                $table->unique(['tenant_id','staff_profile_id'], 'staff_payroll_unique_per_staff');
                $table->index(['tenant_id','tax_code']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Bank Accounts
        // -------------------------
        if (!Schema::hasTable('staff_bank_accounts')) {
            Schema::create('staff_bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->string('account_holder', 255);
                $table->string('sort_code', 20);      // encrypted cast
                $table->string('account_number', 20); // encrypted cast
                $table->string('building_society_roll', 50)->nullable();

                $table->timestamps();

                $table->index(['tenant_id','staff_profile_id']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Training
        // -------------------------
        if (!Schema::hasTable('staff_training_records')) {
            Schema::create('staff_training_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->string('module_code', 100);
                $table->string('module_title', 255);
                $table->string('provider', 255)->nullable();
                $table->date('completed_at')->nullable();
                $table->date('valid_until')->nullable();
                $table->integer('score')->nullable();
                $table->unsignedBigInteger('evidence_file_id')->nullable();

                $table->timestamps();

                $table->index(['tenant_id','staff_profile_id']);
                $table->index(['tenant_id','module_code']);
                $table->index(['tenant_id','valid_until']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Supervisions / Appraisals
        // -------------------------
        if (!Schema::hasTable('staff_supervisions_appraisals')) {
            Schema::create('staff_supervisions_appraisals', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->enum('type', ['supervision','appraisal','probation_review']);
                $table->date('held_at');
                $table->unsignedBigInteger('manager_user_id')->nullable();
                $table->text('summary');
                $table->date('next_due_at')->nullable();

                $table->timestamps();

                $table->index(['tenant_id','staff_profile_id']);
                $table->index(['tenant_id','type']);
                $table->index(['tenant_id','held_at']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
                $table->foreign('manager_user_id')->references('id')->on('users')->nullOnDelete();
            });
        }

        // -------------------------
        // Qualifications
        // -------------------------
        if (!Schema::hasTable('staff_qualifications')) {
            Schema::create('staff_qualifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->string('level', 50);
                $table->string('title', 255);
                $table->string('institution', 255)->nullable();
                $table->date('awarded_at')->nullable();
                $table->unsignedBigInteger('certificate_file_id')->nullable();

                $table->timestamps();

                $table->index(['tenant_id','staff_profile_id']);
                $table->index(['tenant_id','level']);
                $table->index(['tenant_id','awarded_at']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Occupational Health Clearance
        // -------------------------
        if (!Schema::hasTable('staff_occhealth_clearance')) {
            Schema::create('staff_occhealth_clearance', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->boolean('cleared_for_role');
                $table->date('assessed_at');
                $table->text('restrictions')->nullable();
                $table->date('review_due_at')->nullable();

                $table->timestamps();

                $table->index(['tenant_id','staff_profile_id']);
                $table->index(['tenant_id','assessed_at']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Immunisations
        // -------------------------
        if (!Schema::hasTable('staff_immunisations')) {
            Schema::create('staff_immunisations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->enum('vaccine', ['HepB','MMR','Varicella','TB_BCG','Flu','Covid19','Tetanus','Pertussis','Other']);
                $table->string('dose', 50)->nullable();
                $table->date('administered_at');
                $table->unsignedBigInteger('evidence_file_id')->nullable();
                $table->text('notes')->nullable();

                $table->timestamps();

                $table->index(['tenant_id','staff_profile_id']);
                $table->index(['tenant_id','vaccine']);
                $table->index(['tenant_id','administered_at']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Leave Entitlements
        // -------------------------
        if (!Schema::hasTable('staff_leave_entitlements')) {
            Schema::create('staff_leave_entitlements', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->date('period_start');
                $table->date('period_end');
                $table->decimal('annual_leave_days', 5, 2);
                $table->string('sick_pay_scheme', 100)->nullable();

                $table->timestamps();

                $table->unique(['tenant_id','staff_profile_id','period_start','period_end'], 'staff_leave_entitlement_unique_period');
                $table->index(['tenant_id','period_end']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Leave Records
        // -------------------------
        if (!Schema::hasTable('staff_leave_records')) {
            Schema::create('staff_leave_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->enum('type', ['annual','sick','unpaid','maternity','paternity','compassionate','study','other']);
                $table->date('start_date');
                $table->date('end_date');
                $table->decimal('hours', 6, 2)->nullable();
                $table->text('reason')->nullable();
                $table->unsignedBigInteger('fit_note_file_id')->nullable();

                $table->timestamps();

                $table->index(['tenant_id','staff_profile_id']);
                $table->index(['tenant_id','type']);
                $table->index(['tenant_id','start_date']);
                $table->index(['tenant_id','end_date']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Availability Preferences
        // -------------------------
        if (!Schema::hasTable('staff_availability_preferences')) {
            Schema::create('staff_availability_preferences', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->tinyInteger('day_of_week'); // 0=Sun ... 6=Sat
                $table->time('available_from')->nullable();
                $table->time('available_to')->nullable();
                $table->enum('preference', ['prefer','okay','avoid'])->default('okay');

                $table->timestamps();

                $table->unique(['tenant_id','staff_profile_id','day_of_week'], 'staff_availability_unique_day');
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Emergency Contacts
        // -------------------------
        if (!Schema::hasTable('staff_emergency_contacts')) {
            Schema::create('staff_emergency_contacts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->string('name', 255);
                $table->string('relationship', 100);
                $table->string('phone', 50);
                $table->string('email', 255)->nullable();
                $table->text('address')->nullable();

                $table->timestamps();

                $table->index(['tenant_id','staff_profile_id']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Equality Data
        // -------------------------
        if (!Schema::hasTable('staff_equality_data')) {
            Schema::create('staff_equality_data', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->string('ethnicity', 100)->nullable();
                $table->string('religion', 100)->nullable();
                $table->boolean('disability')->default(false);
                $table->string('gender_identity', 100)->nullable();
                $table->string('sexual_orientation', 100)->nullable();
                $table->enum('data_source', ['self_declared','not_provided'])->default('self_declared');

                $table->timestamps();

                $table->unique(['tenant_id','staff_profile_id'], 'staff_equality_unique_per_staff');
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Adjustments
        // -------------------------
        if (!Schema::hasTable('staff_adjustments')) {
            Schema::create('staff_adjustments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->string('type', 100);
                $table->date('in_place_from')->nullable();
                $table->text('notes')->nullable();

                $table->timestamps();

                $table->index(['tenant_id','staff_profile_id']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Driving Licences
        // -------------------------
        if (!Schema::hasTable('staff_driving_licences')) {
            Schema::create('staff_driving_licences', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->string('licence_number', 50)->nullable();
                $table->string('categories', 50)->nullable();
                $table->date('expires_at')->nullable();
                $table->boolean('business_insurance_confirmed')->default(false);
                $table->unsignedBigInteger('document_file_id')->nullable();

                $table->timestamps();

                $table->index(['tenant_id','staff_profile_id']);
                $table->index(['tenant_id','expires_at']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Disciplinary Records
        // -------------------------
        if (!Schema::hasTable('staff_disciplinary_records')) {
            Schema::create('staff_disciplinary_records', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->enum('type', ['informal','formal','warning','dismissal']);
                $table->date('opened_at');
                $table->date('closed_at')->nullable();
                $table->text('summary');
                $table->text('outcome')->nullable();

                $table->timestamps();

                $table->index(['tenant_id','staff_profile_id']);
                $table->index(['tenant_id','type']);
                $table->foreign('staff_profile_id')->references('id')->on('staff_profiles')->cascadeOnDelete();
            });
        }

        // -------------------------
        // Documents (morph to owner)
        // -------------------------
        if (!Schema::hasTable('documents')) {
            Schema::create('documents', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');

                $table->string('owner_type', 100);
                $table->unsignedBigInteger('owner_id');

                $table->string('category', 50);
                $table->string('filename', 255);
                $table->string('path', 255);
                $table->string('mime', 100);
                $table->unsignedBigInteger('uploaded_by')->nullable();
                $table->string('hash', 64)->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['tenant_id','owner_type','owner_id'], 'documents_owner_idx');
                $table->index(['tenant_id','category']);
                $table->index(['tenant_id','uploaded_by']);

                // FK optional: uploaded_by → users
                $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'staff_payroll','staff_bank_accounts','staff_training_records','staff_supervisions_appraisals',
            'staff_qualifications','staff_occhealth_clearance','staff_immunisations',
            'staff_leave_entitlements','staff_leave_records','staff_availability_preferences',
            'staff_emergency_contacts','staff_equality_data','staff_adjustments','staff_driving_licences',
            'staff_disciplinary_records','documents',
        ];
        foreach ($tables as $t) {
            if (Schema::hasTable($t)) {
                Schema::dropIfExists($t);
            }
        }
    }
};
