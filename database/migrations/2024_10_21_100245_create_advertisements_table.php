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
    Schema::create('advertisements', function (Blueprint $table) {
      $table->id();
      $table->string('campaign_name');
      $table->integer('placement')->unsigned();
      $table->integer('option')->unsigned();
      $table->string('image_code');
      $table->dateTime('start_date');
      $table->dateTime('end_date');
      $table->unsignedInteger('status')->default(1)->comment('0: Blocked, 1: Active');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('advertisements');
  }
};
