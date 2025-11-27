<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void
    {
        Schema::create('assignment_signoffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('signer_id')->constrained('users');
            $table->string('role')->nullable();
            $table->enum('method', ['screen_sign', 'initials', 'pin'])->default('screen_sign');
            $table->timestamp('signed_at');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('assignment_signoffs');
    }
};
