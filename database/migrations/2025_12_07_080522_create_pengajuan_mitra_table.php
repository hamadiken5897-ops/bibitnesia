<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanMitraTable extends Migration
{
    public function up()
    {
        Schema::create('pengajuan_mitra', function (Blueprint $table) {
            $table->bigIncrements('id_pengajuan');

            // relasi ke tabel users
            $table->string('id_user', 25);

            $table->foreign('id_user')
                ->references('id_user')
                ->on('users')
                ->onDelete('cascade');


            // daftar sebagai apa
            $table->enum('role_pengajuan', ['penjual', 'kurir']);

            // dokumen wajib
            $table->string('ktp');
            $table->string('foto_selfie');
            $table->string('no_rekening');

            // data umum
            $table->text('alamat');
            $table->string('no_hp');

            // jika daftar kurir
            $table->string('sim')->nullable();
            $table->string('stnk')->nullable();
            $table->string('skck')->nullable();
            $table->string('foto_kendaraan')->nullable();
            $table->enum('tipe_kendaraan', ['motor', 'mobil', 'truk', 'cargo'])->nullable();
            $table->string('merek_kendaraan')->nullable();

            // jika daftar penjual berkebun
            $table->string('foto_kebun')->nullable();
            $table->string('portofolio')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('alamat_kebun')->nullable();

            // status verifikasi admin
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])
                ->default('Menunggu');

            // jika ditolak atau catatan admin
            $table->text('catatan_admin')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_mitra');
    }
}
