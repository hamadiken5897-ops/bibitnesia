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
        Schema::table('users', function (Blueprint $table) {
            $table->string('otp_code', 10)->nullable()->after('password');
            $table->dateTime('otp_expires_at')->nullable()->after('otp_code');
            $table->string('google_id')->nullable()->after('id_user');
            
            // Kolom ini akan dipakai jika kita ingin memastikan email verified, tapi untuk sekarang opsional.
            // $table->timestamp('email_verified_at')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp_code', 'otp_expires_at', 'google_id']);
        });
    }
};
