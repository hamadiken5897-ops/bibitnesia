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
        Schema::create('users', function (Blueprint $table) {
            // ID utama
            $table->string('id_user', 25)->primary();

            // Kolom-kolom sesuai SQL
            $table->string('nama', 25);
            $table->string('email', 255)->unique();
            $table->string('password', 255); // lebih aman, ubah panjang ke 255
            $table->string('no_telepon', 25)-> nullable();
            $table->string('alamat', 255)-> nullable();
            $table->enum('role', ['pembeli', 'penjual', 'kurir', 'admin']);
            $table->date('tanggal_daftar');
            $table->enum('status_akun', ['AKTIF', 'NONAKTIF', 'BANNED']);
            $table->dateTime('terakhir_login')->nullable();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
