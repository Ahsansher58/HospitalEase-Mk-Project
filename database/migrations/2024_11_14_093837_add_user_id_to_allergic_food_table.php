<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToAllergicFoodTable extends Migration
{
  public function up()
  {
    Schema::table('allergic_food', function (Blueprint $table) {
      // Add the user_id column, assuming it will reference the 'id' column in the 'users' table
      $table->unsignedBigInteger('user_id')->nullable(); // Set as nullable, or remove nullable() if it should be required

      // If you want to add a foreign key constraint, you can do so like this:
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  public function down()
  {
    Schema::table('allergic_food', function (Blueprint $table) {
      $table->dropColumn('user_id'); // Drop the user_id column if this migration is rolled back
    });
  }
}
