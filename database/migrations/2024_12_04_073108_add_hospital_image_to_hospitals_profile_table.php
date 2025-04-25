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
    Schema::table('hospitals_profile', function (Blueprint $table) {
      $table->string('hospital_image')->nullable()->after('hospital_name');
    });
  }

  public function down()
  {
    Schema::table('hospitals_profile', function (Blueprint $table) {
      $table->dropColumn('hospital_image');
    });
  }
};
