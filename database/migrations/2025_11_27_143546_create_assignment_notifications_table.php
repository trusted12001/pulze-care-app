<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('assignment_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->enum('channel', ['push', 'email', 'in_app', 'sms'])->default('in_app');
            $table->foreignId('to_user_id')->constrained('users');
            $table->timestamp('sent_at')->nullable();
            $table->string('status')->default('queued');
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('assignment_notifications');
    }
};
