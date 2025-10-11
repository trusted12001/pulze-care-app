<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('rota_periods', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->id();
      $table->unsignedBigInteger('location_id')->index();
      $table->date('start_date');
      $table->date('end_date');
      $table->string('status')->default('draft'); // draft|published|locked
      $table->unsignedBigInteger('generated_by')->nullable();
      $table->timestamp('published_at')->nullable();
      $table->timestamps();
      $table->foreign('location_id')->references('id')->on('locations')->cascadeOnDelete();
      $table->foreign('generated_by')->references('id')->on('users')->nullOnDelete();
    });
  }
  public function down(): void { Schema::dropIfExists('rota_periods'); }
};
