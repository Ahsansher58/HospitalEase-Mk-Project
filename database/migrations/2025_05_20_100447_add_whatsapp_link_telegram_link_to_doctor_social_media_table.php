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
        Schema::table('doctor_social_media', function (Blueprint $table) {
            $table->text('whatsapp_link')->after('instagram_link')->nullable();
            $table->text('telegram_link')->after('whatsapp_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctor_social_media', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_link', 'telegram_link']);
        });
    }
};
