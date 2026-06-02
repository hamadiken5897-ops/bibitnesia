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
        Schema::create('pesans', function (Blueprint $table) {
            $table->id();
            $table->string('id_pengirim', 25);
            $table->string('id_penerima', 25);
            $table->string('id_produk', 25)->nullable(); // Konteks pesan (bisa null jika hanya chat biasa)
            $table->text('isi_pesan');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('id_pengirim')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_penerima')->references('id_user')->on('users')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesans');
    }
};
