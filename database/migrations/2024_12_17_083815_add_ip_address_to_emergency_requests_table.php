<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIpAddressToEmergencyRequestsTable extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('emergency_requests', function (Blueprint $table) {
      $table->string('ip_address', 45)->nullable()->after('date_time'); // Supports IPv4 and IPv6
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('emergency_requests', function (Blueprint $table) {
      $table->dropColumn('ip_address');
    });
  }
}
