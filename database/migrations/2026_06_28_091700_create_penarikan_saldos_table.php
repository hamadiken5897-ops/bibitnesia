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
        Schema::create('penarikan_saldos', function (Blueprint $table) {
            $table->id('id_penarikan');
            
            // Siapa yang menarik uang?
            $table->string('user_id', 25);
            $table->enum('role', ['penjual', 'kurir']);
            
            // Data Bank (Snapshot agar history tidak berubah jika user ganti rekening)
            $table->string('nama_bank', 50);
            $table->string('no_rekening', 50);
            $table->string('nama_pemilik_rekening', 100);

            $table->decimal('jumlah_penarikan', 15, 2);
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->dateTime('tgl_pengajuan');
            $table->dateTime('tgl_selesai')->nullable();
            
            // Catatan admin (misal alasan ditolak, atau link bukti transfer manual)
            $table->text('catatan_admin')->nullable();
            
            // Midtrans Payout ID (jika menggunakan Midtrans Iris)
            $table->string('payout_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penarikan_saldos');
    }
};
