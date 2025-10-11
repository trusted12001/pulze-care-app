<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('shifts', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->id();
      $table->unsignedBigInteger('rota_period_id')->index();
      $table->unsignedBigInteger('location_id')->index();
      $table->string('role')->index();
    $table->dateTime('start_at');
    $table->dateTime('end_at');
      $table->unsignedSmallInteger('break_minutes')->default(0);
      $table->json('skills_json')->nullable();
      $table->string('status')->default('draft'); // mirrors period by default
      $table->text('notes')->nullable();
      $table->timestamps();

      $table->foreign('rota_period_id')->references('id')->on('rota_periods')->cascadeOnDelete();
      $table->foreign('location_id')->references('id')->on('locations')->cascadeOnDelete();
    });
  }
  public function down(): void { Schema::dropIfExists('shifts'); }
};
