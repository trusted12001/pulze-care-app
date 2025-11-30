<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['care', 'documentation', 'operations', 'training']);
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['draft', 'scheduled', 'in_progress', 'submitted', 'verified', 'closed', 'declined', 'expired', 'overdue'])->default('draft');
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resident_id')->nullable()->constrained('service_users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('assigned_to')->constrained('users');
            $table->foreignId('shift_id')->nullable()->constrained('shifts')->nullOnDelete();
            $table->timestamp('window_start')->nullable();
            $table->timestamp('window_end')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->boolean('requires_gps')->default(true);
            $table->boolean('requires_signature')->default(false);
            $table->boolean('requires_photo')->default(false);
            $table->text('recurrence_rule')->nullable(); // iCal RRULE
            $table->foreignId('parent_id')->nullable()->constrained('assignments')->nullOnDelete();
            $table->unsignedInteger('sla_minutes')->default(0);
            $table->enum('risk_level', ['none', 'low', 'medium', 'high'])->default('none');
            $table->json('metadata')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['assigned_to', 'status', 'due_at']);
            $table->index(['location_id', 'status']);
            $table->index(['resident_id', 'status']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
