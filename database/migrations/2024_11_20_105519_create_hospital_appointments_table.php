<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHospitalAppointmentsTable extends Migration
{
  public function up()
  {
    Schema::create('hospital_appointments', function (Blueprint $table) {
      $table->id();
      $table->string('patient_name');
      $table->string('phone_number');
      $table->string('email');
      $table->date('appointment_date');
      $table->unsignedBigInteger('hospital_id'); // Hospital foreign key
      $table->timestamps();

      // Add a foreign key constraint (assuming hospitals table exists)
      $table->foreign('hospital_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  public function down()
  {
    Schema::dropIfExists('hospital_appointments');
  }
}
