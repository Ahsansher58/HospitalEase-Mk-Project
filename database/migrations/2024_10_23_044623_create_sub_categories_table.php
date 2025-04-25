<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategoriesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('sub_categories', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('main_category_id');
      $table->string('sub_category_name');
      $table->unsignedInteger('sort_order');
      $table->unsignedInteger('type');  // Type is now an unsigned integer
      $table->string('option');
      $table->text('value')->nullable();
      $table->boolean('status')->default(true); // 1 for active, 0 for inactive
      $table->timestamps();

      // Foreign key constraint
      $table->foreign('main_category_id')->references('id')->on('main_categories')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('sub_categories');
  }
}
