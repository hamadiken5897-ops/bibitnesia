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
        Schema::create('banneds', function (Blueprint $table) {
            // Primary key
            $table->string('id_banned', 25)->primary();

            // Relasi ke users
            $table->string('id_user', 25);
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade');

            // Kolom lainnya
            $table->enum('status', ['SEMENTARA', 'PERMANEN']);
            $table->date('tgl_banned');
            $table->date('tgl_berakhir')->nullable(); // boleh kosong jika permanen
            $table->text('alasan');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banneds');
    }
};
