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
        Schema::create('pengirimen', function (Blueprint $table) {
            // Primary key
            $table->string('id_pengiriman', 25)->primary();

            // Relasi ke pesanan
            $table->string('id_pesanan', 25);
            $table->foreign('id_pesanan')
                  ->references('id_pesanan')
                  ->on('pesanans')
                  ->onDelete('cascade');

            // Data pengiriman
            $table->enum('kurir', ['jne', 'jnt', 'parcel', 'ninja express']);
            $table->string('no_resi', 255);
            $table->text('alamat_tujuan');
            $table->date('tanggal_pengiriman');
            $table->date('estimasi_tiba');
            $table->enum('status_pengiriman', [
                'dikemas',
                'diproses',
                'dikirim',
                'selesai',
                'dibatalkan'
            ])->default('dikemas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengirimen');
    }
};
