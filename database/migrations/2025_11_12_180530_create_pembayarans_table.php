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
        Schema::create('pembayarans', function (Blueprint $table) {
            // Primary key
            $table->string('id_pembayaran', 25)->primary();

            // Relasi ke pesanan
            $table->string('id_pesanan', 25);
            $table->foreign('id_pesanan')
                  ->references('id_pesanan')
                  ->on('pesanans')
                  ->onDelete('cascade');

            // Kolom data pembayaran
            $table->enum('metode_pembayaran', ['VA BANK', 'E-Wallet', 'QRIS']);
            $table->integer('total_bayar');
            $table->date('tanggal_pembayaran');
            $table->enum('status_validasi', ['Pending', 'Lunas'])->default('Pending');
            $table->date('tgl_validasi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
