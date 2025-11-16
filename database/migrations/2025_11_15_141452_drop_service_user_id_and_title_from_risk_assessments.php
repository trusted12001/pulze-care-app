<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            // Drop foreign key constraint first before dropping the column
            $table->dropForeign(['service_user_id']);
            $table->dropColumn('service_user_id');
            $table->dropColumn('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('risk_assessments', function (Blueprint $table) {
            $table->unsignedBigInteger('service_user_id')->after('id');
            $table->foreign('service_user_id')
                ->references('id')->on('service_users')
                ->onDelete('cascade');
            $table->string('title')->after('service_user_id');
        });
    }
};
