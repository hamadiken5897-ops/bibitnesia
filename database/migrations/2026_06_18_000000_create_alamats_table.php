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
        Schema::create('alamats', function (Blueprint $table) {
            $table->id();
            
            $table->string('id_user', 25);
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
            
            $table->string('nama_penerima');
            $table->string('no_telepon');
            
            $table->unsignedBigInteger('id_provinsi');
            $table->foreign('id_provinsi')->references('id_provinsi')->on('provinsis')->onDelete('restrict');
            
            $table->string('kota');
            $table->string('kecamatan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->text('detail_alamat');
            $table->boolean('is_utama')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alamats');
    }
};
