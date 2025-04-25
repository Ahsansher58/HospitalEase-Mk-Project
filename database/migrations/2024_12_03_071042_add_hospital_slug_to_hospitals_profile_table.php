<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHospitalSlugToHospitalsProfileTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('hospitals_profile', function (Blueprint $table) {
      $table->string('hospital_slug')->unique()->nullable()->after('location');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('hospitals_profile', function (Blueprint $table) {
      $table->dropColumn('hospital_slug');
    });
  }
}
