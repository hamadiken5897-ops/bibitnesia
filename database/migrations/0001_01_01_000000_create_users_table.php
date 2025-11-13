<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id_user', 25)->primary();
            $table->string('nama', 100);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('no_telepon', 25)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->enum('role', ['pembeli', 'penjual', 'kurir', 'admin'])->default('pembeli');
            $table->date('tanggal_daftar');
            $table->enum('status_akun', ['AKTIF', 'NONAKTIF', 'BANNED'])->default('AKTIF');
            $table->dateTime('terakhir_login')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
