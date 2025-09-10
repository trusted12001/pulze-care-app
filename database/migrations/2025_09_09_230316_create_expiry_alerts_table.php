<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('expiry_alerts')) {
            Schema::create('expiry_alerts', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tenant_id');
                $table->unsignedBigInteger('staff_profile_id');

                // What was alerted
                $table->string('resource_type', 50); // visa|registration|registration_revalidation|employment_check
                $table->unsignedBigInteger('resource_id');

                // Window (e.g., 90/60/30) and when we sent it
                $table->unsignedSmallInteger('window_days');
                $table->date('alert_date'); // date it was triggered for (expiry/revalidation date)

                $table->timestamps();

                $table->index(['tenant_id', 'staff_profile_id']);
                $table->unique(
                    ['resource_type','resource_id','window_days'],
                    'expiry_alerts_unique_window'
                );
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('expiry_alerts');
    }
};
