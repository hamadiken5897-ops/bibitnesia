<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_kurirs', function (Blueprint $table) {
            $table->id();
            $table->string('id_kurir', 25);
            $table->string('id_pesanan', 25);
            $table->decimal('jumlah', 15, 2);
            $table->timestamps();

            $table->foreign('id_kurir')->references('id_kurir')->on('kurirs')->onDelete('cascade');
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanans')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kurirs');
    }
};
