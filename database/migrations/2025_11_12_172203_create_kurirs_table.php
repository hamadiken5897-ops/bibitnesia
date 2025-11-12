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
        Schema::create('kurirs', function (Blueprint $table) {
            // Primary key
            $table->string('id_kurir', 25)->primary();

            // Relasi ke users
            $table->string('id_user', 25);
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade');

            // Kolom lainnya
            $table->string('nama_pt', 225);
            $table->enum('tipe_kendaraan', ['motor', 'mobil', 'truk', 'cargo']);
            $table->enum('status_kurir', ['AKTIF', 'NONAKTIF'])->default('NONAKTIF');
            $table->string('daerah', 225);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kurirs');
    }
};
