<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id')->index();
            $table->string('name');
            $table->enum('type', ['care_home','supported_living','day_centre','domiciliary'])->default('domiciliary');

            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode', 20)->nullable();
            $table->string('country', 100)->nullable();

            $table->string('phone', 40)->nullable();
            $table->string('email', 150)->nullable();

            $table->enum('status', ['active','inactive'])->default('active');

            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->unsignedSmallInteger('geofence_radius_m')->nullable(); // e.g., 40–250

            $table->timestamps();
            $table->softDeletes();

            // Optional: uncomment if you want a DB-level FK to tenants
            // $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();

            // Optional helpful index for lookups
            $table->index(['tenant_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
