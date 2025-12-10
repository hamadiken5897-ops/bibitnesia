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
        Schema::create('notifikasi_user', function (Blueprint $table) {
            $table->bigIncrements('id_notif');
            $table->string('id_user', 25);
        
            $table->string('judul');          // Contoh: "Pengajuan Disetujui"
            $table->text('pesan');            // Contoh: "Selamat, Anda diterima sebagai penjual"
            $table->string('redirect_url');   // Contoh: "/penjual/dashboard#pengajuan"
            $table->boolean('is_read')->default(false);
        
            $table->timestamps();
        
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi_user');
    }
};
