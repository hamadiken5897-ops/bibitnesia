<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('laporan_penjual', function (Blueprint $table) {
            $table->increments('id_laporan');

            $table->string('id_penjual', 25);   // 🔥 STRING
            $table->string('id_pesanan', 25);   // samakan dgn pesanan

            $table->decimal('jumlah', 12, 2);
            $table->timestamps();

            $table->foreign('id_penjual')
                ->references('id_penjual')
                ->on('penjuals')
                ->onDelete('cascade');

            $table->foreign('id_pesanan')
                ->references('id_pesanan')
                ->on('pesanans')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_penjual');
    }
};
