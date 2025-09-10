<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('staff_employment_checks')) {
            Schema::create('staff_employment_checks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                $table->enum('check_type', [
                    'dbs_basic','dbs_enhanced','dbs_adult_first',
                    'barred_list_adult','barred_list_child',
                    'rtw_passport','rtw_share_code',
                    'proof_of_address','reference','oh_clearance'
                ]);

                $table->enum('result', ['pass','fail','pending'])->default('pending');
                $table->string('reference_no', 120)->nullable();

                $table->date('issued_at')->nullable();
                $table->date('expires_at')->nullable();

                $table->unsignedBigInteger('evidence_file_id')->nullable(); // future documents FK
                $table->unsignedBigInteger('checked_by_user_id')->nullable(); // who verified it

                $table->text('notes')->nullable();

                $table->timestamps();
                $table->softDeletes();

                $table->index(['tenant_id', 'staff_profile_id']);
                $table->index(['tenant_id', 'check_type']);
                $table->index(['tenant_id', 'result']);
                $table->index(['tenant_id', 'expires_at']);

                $table->foreign('staff_profile_id')
                    ->references('id')->on('staff_profiles')
                    ->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_employment_checks');
    }
};
