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
        Schema::create('admins', function (Blueprint $table) {
            // Primary key
            $table->string('id_admin', 25)->primary();

            // Relasi ke tabel users
            $table->string('id_user', 25);
            $table->foreign('id_user')
                  ->references('id_user')
                  ->on('users')
                  ->onDelete('cascade');

            // Kolom lainnya
            $table->enum('jabatan', ['admin', 'super_admin']);
            $table->date('tgl_bergabung');
            $table->enum('status_admin', ['AKTIF', 'NONAKTIF']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
