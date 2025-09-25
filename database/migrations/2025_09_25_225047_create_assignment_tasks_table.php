<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assignment_tasks', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->string('title');
            $table->unsignedBigInteger('service_user_id')->nullable()->index();
            $table->foreign('service_user_id')->references('id')->on('service_users')->nullOnDelete();

            $table->unsignedBigInteger('assignee_user_id')->nullable()->index();
            $table->foreign('assignee_user_id')->references('id')->on('users')->nullOnDelete();

            $table->unsignedBigInteger('risk_control_id')->nullable()->index();
            $table->foreign('risk_control_id')->references('id')->on('risk_controls')->cascadeOnDelete();

            $table->string('frequency')->nullable();       // each_shift|daily|weekly|prn
            $table->timestamp('due_at')->nullable();
            $table->string('status')->default('pending');  // pending|completed|missed
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('completed_by')->nullable();
            $table->foreign('completed_by')->references('id')->on('users')->nullOnDelete();

            $table->string('evidence_type')->nullable();
            $table->text('evidence_path')->nullable();

            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('assignment_tasks'); }
};
