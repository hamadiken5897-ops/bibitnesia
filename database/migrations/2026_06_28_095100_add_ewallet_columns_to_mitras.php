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
        Schema::table('penjuals', function (Blueprint $table) {
            $table->string('ewallet_name', 50)->nullable()->after('nama_pemilik_rekening');
            $table->string('ewallet_phone', 50)->nullable()->after('ewallet_name');
            $table->string('ewallet_owner', 100)->nullable()->after('ewallet_phone');
        });

        Schema::table('kurirs', function (Blueprint $table) {
            $table->string('ewallet_name', 50)->nullable()->after('nama_pemilik_rekening');
            $table->string('ewallet_phone', 50)->nullable()->after('ewallet_name');
            $table->string('ewallet_owner', 100)->nullable()->after('ewallet_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjuals', function (Blueprint $table) {
            $table->dropColumn(['ewallet_name', 'ewallet_phone', 'ewallet_owner']);
        });

        Schema::table('kurirs', function (Blueprint $table) {
            $table->dropColumn(['ewallet_name', 'ewallet_phone', 'ewallet_owner']);
        });
    }
};
