<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('hospitals_profile', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('hospital_id'); // Foreign key to hospitals table
      $table->json('values'); // JSON format
      $table->timestamps();

      // Add foreign key constraint if needed
      // $table->foreign('hospital_id')->references('id')->on('hospitals')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('hospitals_profile');
  }
};
