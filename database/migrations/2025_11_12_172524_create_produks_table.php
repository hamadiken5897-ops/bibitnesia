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
        Schema::create('produks', function (Blueprint $table) {
            // Primary key
            $table->string('id_produk', 25)->primary();

            // Relasi ke tabel penjuals
            $table->string('id_penjual', 25);
            $table->foreign('id_penjual')
                  ->references('id_penjual')
                  ->on('penjuals')
                  ->onDelete('cascade');

            // Kolom data produk
            $table->string('nama_produk', 255);
            $table->enum('kategori', ['Tanaman_hias', 'sayur', 'buah']);
            $table->text('deskripsi');
            $table->string('foto_produk', 255)->nullable();
            $table->enum('status', ['tersedia', 'habis'])->default('tersedia');
            $table->integer('stok');
            $table->integer('harga');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
