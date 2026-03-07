<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tenant_id')->unique();
            $table->string('logo_path')->nullable(); // stored path like: tenant-logos/xyz.png
            $table->text('office_address')->nullable(); // official office address for documents

            $table->timestamps();

            $table->foreign('tenant_id')
                ->references('id')->on('tenants')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_settings');
    }
};
