<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserProfileTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_profile', function (Blueprint $table) {
      $table->id(); // Primary key
      $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key for users
      $table->integer('height')->nullable(); // Height in cm or inches
      $table->integer('weight')->nullable(); // Weight in kg or lbs
      $table->string('address')->nullable(); // Street address
      $table->string('locality')->nullable(); // Locality or neighborhood
      $table->string('city')->nullable(); // City
      $table->string('state')->nullable(); // State
      $table->string('pincode', 10)->nullable(); // Postal code
      $table->string('country')->nullable(); // Country
      $table->timestamps(); // created_at and updated_at
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('user_profile');
  }
}
