<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('shift_templates', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->id();
      $table->unsignedBigInteger('location_id')->index(); // FK -> locations.id
      $table->string('name'); // Early/Late/Night, etc.
      $table->string('role')->index(); // carer, nurse, lead, etc.
      $table->time('start_time');
      $table->time('end_time');
      $table->unsignedSmallInteger('break_minutes')->default(0);
      $table->unsignedTinyInteger('headcount')->default(1);
      $table->json('skills_json')->nullable(); // e.g. ["meds", "male_only"]
      $table->boolean('paid_flag')->default(true);
      $table->boolean('active')->default(true);
      $table->timestamps();

      $table->foreign('location_id')->references('id')->on('locations')->cascadeOnDelete();
    });
  }
  public function down(): void { Schema::dropIfExists('shift_templates'); }
};
