<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllergicMedicineTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('allergic_medicine', function (Blueprint $table) {
      $table->id();  // Auto-incrementing ID
      $table->string('medicine_name');  // Name of the medicine
      $table->text('symptoms_reactions');  // Symptoms and reactions
      $table->boolean('status')->default(true);  // Status, default is active (true)
      $table->timestamps();  // Created at and updated at timestamps
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('allergic_medicine');
  }
}
