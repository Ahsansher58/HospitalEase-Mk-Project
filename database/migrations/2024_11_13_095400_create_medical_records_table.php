<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('medical_records', function (Blueprint $table) {
      $table->id(); // Primary key with auto-increment
      $table->unsignedBigInteger('user_id'); // Foreign key to users table
      $table->string('report_category');
      $table->string('report_name');
      $table->date('report_date');
      $table->string('report_file'); // File path or filename
      $table->boolean('status')->default(1); // Status, defaulting to 1 (active)
      $table->timestamps(); // created_at and updated_at columns

      // Define foreign key constraint for user_id
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('medical_records', function (Blueprint $table) {
      // Drop the foreign key constraint before dropping the table
      $table->dropForeign(['user_id']);
    });

    Schema::dropIfExists('medical_records');
  }
};
