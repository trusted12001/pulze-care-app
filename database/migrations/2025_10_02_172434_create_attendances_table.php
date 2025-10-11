<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('attendances', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->id();
      $table->unsignedBigInteger('shift_assignment_id')->index();
      $table->timestamp('check_in_at')->nullable();
      $table->json('check_in_latlng')->nullable();   // {"lat":..,"lng":..}
      $table->boolean('within_geofence_in')->default(false);
      $table->timestamp('check_out_at')->nullable();
      $table->json('check_out_latlng')->nullable();
      $table->boolean('within_geofence_out')->default(false);
      $table->integer('variance_minutes')->nullable(); // actual - planned minus breaks
      $table->string('exception_reason')->nullable();  // late/no show/etc.
      $table->timestamps();

      $table->foreign('shift_assignment_id')->references('id')->on('shift_assignments')->cascadeOnDelete();
    });
  }
  public function down(): void { Schema::dropIfExists('attendances'); }
};
