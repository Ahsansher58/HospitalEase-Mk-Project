<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToHospitalsProfileTable extends Migration
{
  public function up()
  {
    Schema::table('hospitals_profile', function (Blueprint $table) {
      $table->string('hospital_name')->after('id'); // Add after a specific column (e.g., 'id')
      $table->text('description')->nullable()->after('hospital_name');
      $table->json('departments')->nullable()->after('description');
      $table->string('phone', 15)->nullable()->after('departments');
      $table->string('emergency_contact', 15)->nullable()->after('phone');
      $table->string('email')->nullable()->after('emergency_contact');
      $table->string('website')->nullable()->after('email');
      $table->string('location')->nullable()->after('website');
      $table->json('facilities')->nullable()->after('location');
    });
  }

  public function down()
  {
    Schema::table('hospitals_profile', function (Blueprint $table) {
      $table->dropColumn([
        'hospital_name',
        'description',
        'departments',
        'phone',
        'emergency_contact',
        'email',
        'website',
        'location',
        'facilities',
      ]);
    });
  }
}
