<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Who verified the assignment
            $table->unsignedBigInteger('verified_by')->nullable()->after('assigned_to');

            // When it was verified
            $table->timestamp('verified_at')->nullable()->after('status');

            // Optional comment from verifier
            $table->text('verification_comment')->nullable()->after('verified_at');

            // Optional: FK to users table (only if you want referential integrity)
            // $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('assignments', function (Blueprint $table) {
            // Drop FK first if you added it
            // $table->dropForeign(['verified_by']);

            $table->dropColumn(['verified_by', 'verified_at', 'verification_comment']);
        });
    }
};
