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
            $table->string('nama_bank', 50)->nullable()->after('rating');
            $table->string('no_rekening', 50)->nullable()->after('nama_bank');
            $table->string('nama_pemilik_rekening', 100)->nullable()->after('no_rekening');
        });

        Schema::table('kurirs', function (Blueprint $table) {
            $table->string('nama_bank', 50)->nullable()->after('daerah');
            $table->string('no_rekening', 50)->nullable()->after('nama_bank');
            $table->string('nama_pemilik_rekening', 100)->nullable()->after('no_rekening');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjuals', function (Blueprint $table) {
            $table->dropColumn(['nama_bank', 'no_rekening', 'nama_pemilik_rekening']);
        });

        Schema::table('kurirs', function (Blueprint $table) {
            $table->dropColumn(['nama_bank', 'no_rekening', 'nama_pemilik_rekening']);
        });
    }
};
