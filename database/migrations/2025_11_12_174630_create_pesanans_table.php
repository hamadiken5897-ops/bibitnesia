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
        Schema::create('pesanans', function (Blueprint $table) {
            // Primary key
            $table->string('id_pesanan', 25)->primary();

            // Relasi ke user (pembeli)
            $table->string('id_user', 25);
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade');

            // Kolom utama
            $table->date('tanggal_pesanan');
            $table->integer('total_harga');
            $table->enum('status_pesanan', [
                'Menunggu Pembayaran',
                'Menunggu konfirmasi penjual',
                'Pesanan ditolak',
                'Pesanan sedang diproses',
                'Pesanan dalam pengiriman',
                'Pesanan selesai'
            ])->default('Menunggu Pembayaran');

            $table->text('catatan')->nullable();

            $table->string('id_detail_pesanan', 25);

            $table->date('tgl_konfirmasi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
