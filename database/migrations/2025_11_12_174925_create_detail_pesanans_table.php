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
        Schema::create('detail_pesanans', function (Blueprint $table) {
            // Primary key
            $table->string('id_detailpesanan', 25)->primary();

            // Relasi ke pesanan
            $table->string('id_pesanan', 25);
            $table->foreign('id_pesanan')
                  ->references('id_pesanan')
                  ->on('pesanans')
                  ->onDelete('cascade');

            // Relasi ke produk
            $table->string('id_produk', 25);
            $table->foreign('id_produk')
                  ->references('id_produk')
                  ->on('produks')
                  ->onDelete('cascade');

            // Kolom transaksi
            $table->integer('harga_satuan');
            $table->integer('jumlah');
            $table->integer('ongkir');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans');
    }
};
