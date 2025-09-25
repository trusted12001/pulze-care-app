<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('care_plan_signoffs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->foreignId('care_plan_id')->constrained('care_plans')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id');
            $table->string('role_label')->nullable();    // e.g., Key Worker, Nurse, Manager
            $table->unsignedInteger('version_at_sign')->default(1);
            $table->timestamp('signed_at')->nullable();
            $table->string('pin_last4')->nullable();     // optional, for audit only (never store full PIN)

            $table->timestamps();
        });

        Schema::table('care_plan_signoffs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
    public function down(): void { Schema::dropIfExists('care_plan_signoffs'); }
};
