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
    Schema::table('user_profile', function (Blueprint $table) {
      $table->string('marital_status')->nullable();  // Add the marital_status column
    });
  }

  public function down()
  {
    Schema::table('user_profile', function (Blueprint $table) {
      $table->dropColumn('marital_status');  // Drop the marital_status column if needed
    });
  }
};
