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
        Schema::create('penjuals', function (Blueprint $table) {
            // Primary key
            $table->string('id_penjual', 25)->primary();

            // Foreign key ke users
            $table->string('id_user', 25);
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade');

            // Kolom lain
            $table->string('nama_penjual', 25);
            $table->string('no_teleponPJ', 25);
            $table->text('alamatPJ');
            $table->date('tanggal_daftar');
            $table->enum('status_verifikasi', ['Menunggu', 'Disetujui', 'Ditolak']);
            $table->date('tgl_verifikasi')->nullable(); // bisa dibuat nullable jika belum diverifikasi
            $table->text('deskripsi_pj');
            $table->decimal('rating', 3, 2)->default(0.00);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjuals');
    }
};
