<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('shift_assignments', function (Blueprint $table) {
      $table->engine = 'InnoDB';
      $table->id();
      $table->unsignedBigInteger('shift_id')->index();
      $table->unsignedBigInteger('staff_id')->index(); // users.id
      $table->string('status')->default('assigned'); // assigned|accepted|swapped|cancelled
      $table->text('notes')->nullable();
      $table->timestamps();

      $table->unique(['shift_id','staff_id']);
      $table->foreign('shift_id')->references('id')->on('shifts')->cascadeOnDelete();
      $table->foreign('staff_id')->references('id')->on('users')->cascadeOnDelete();
    });
  }
  public function down(): void { Schema::dropIfExists('shift_assignments'); }
};
