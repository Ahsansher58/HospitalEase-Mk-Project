<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('business_categories', function (Blueprint $table) {
          $table->id();
          $table->string('name', 100);
          $table->integer('main_category_id')->nullable();  // If this is a foreign key, you can add `->unsigned()`
          $table->boolean('is_sub_category')->default(0);  // Use boolean for true/false flags
          $table->integer('order_no')->unsigned();  // Optionally, you can make this unsigned
          $table->timestamps();
      });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_categorise');
    }
};
