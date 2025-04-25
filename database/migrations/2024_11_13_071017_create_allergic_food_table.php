<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up()
  {
    Schema::create('allergic_food', function (Blueprint $table) {
      $table->id(); // Creates an auto-incrementing primary key named 'id'
      $table->string('medicine_name');
      $table->text('symptoms_reactions')->nullable(); // Text field for symptoms/reactions, allowing NULL values
      $table->boolean('status')->default(1); // Boolean field with default value 1
      $table->timestamps(); // Creates 'created_at' and 'updated_at' columns
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('allergic_food');
  }
};
