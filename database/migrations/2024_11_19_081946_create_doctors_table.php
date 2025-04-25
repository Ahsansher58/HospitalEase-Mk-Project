<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorsTable extends Migration
{
  public function up()
  {
    Schema::create('doctors', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('hospital_id'); // Reference to the hospital
      $table->foreign('hospital_id')->references('id')->on('users')->onDelete('cascade');
      $table->string('name');
      $table->string('degree'); // New degree column
      $table->string('experience');
      $table->text('specialisation'); // JSON field
      $table->string('profile_image')->nullable();
      $table->text('short_intro')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('doctors');
  }
}
