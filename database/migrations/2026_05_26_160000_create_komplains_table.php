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
        Schema::create('komplains', function (Blueprint $table) {
            $table->string('id_komplain', 25)->primary();
            $table->string('id_user', 25); // Pelapor (Pembeli/User)
            $table->string('id_pesanan', 25)->nullable(); // Transaksi terkait
            $table->string('id_terlapor', 25)->nullable(); // User yang dilaporkan (Penjual/Kurir)
            $table->string('judul_laporan', 255);
            $table->text('deskripsi_laporan');
            $table->string('bukti_foto', 255)->nullable();
            $table->enum('status', ['MENUNGGU', 'DIPROSES', 'SELESAI', 'DITOLAK'])->default('MENUNGGU');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanans')->onDelete('set null');
            $table->foreign('id_terlapor')->references('id_user')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komplains');
    }
};
