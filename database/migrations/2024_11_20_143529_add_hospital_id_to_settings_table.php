<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHospitalIdToSettingsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('settings', function (Blueprint $table) {
      $table->unsignedBigInteger('hospital_id')->nullable()->after('id'); // Add the column after 'id'

      // If you have a hospitals table, you can optionally add a foreign key:
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
    Schema::table('settings', function (Blueprint $table) {
      $table->dropColumn('hospital_id');
      // If you added a foreign key, drop it as well:
      // $table->dropForeign(['hospital_id']);
    });
  }
}
